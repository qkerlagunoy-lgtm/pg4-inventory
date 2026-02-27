<?php

namespace App\Http\Controllers;

use App\Models\ItemRequest;
use App\Models\RequestItem;
use App\Models\Item;
use App\Models\Category;
use App\Models\User;
use App\Models\Issuance;
use App\Models\IssuanceItem;
use App\Models\Notification;
use App\Models\AuditLog; // ADD THIS
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    // ==================== DASHBOARD ====================
    public function dashboard(): \Illuminate\View\View
    {
        $stats = [
            'pending_requests' => ItemRequest::where('status', 'pending')->count(),
            'urgent_requests' => ItemRequest::where('priority', 'urgent')->count(),
            'approved_requests' => ItemRequest::where('status', 'approved')->count(),
            'rejected_requests' => ItemRequest::where('status', 'rejected')->count(),
            'low_stock_items' => Item::whereColumn('quantity', '<=', 'minimum_quantity')->count(),
            'expiring_soon' => 0, // Add your logic here
        ];
        
        // Get most requested items
        try {
            $mostRequestedItems = DB::table('request_items')
                ->join('items', 'request_items.item_id', '=', 'items.id')
                ->select(
                    'items.name',
                    DB::raw('COUNT(request_items.id) as request_count'),
                    DB::raw('SUM(request_items.quantity) as total_quantity')
                )
                ->groupBy('items.id', 'items.name')
                ->orderBy('request_count', 'desc')
                ->limit(5)
                ->get()
                ->map(function($item) {
                    $totalRequests = DB::table('request_items')->count();
                    $percentage = $totalRequests > 0 ? ($item->request_count / $totalRequests) * 100 : 0;
                    return [
                        'name' => $item->name,
                        'count' => $item->request_count,
                        'quantity' => $item->total_quantity,
                        'percentage' => round($percentage, 1)
                    ];
                });
        } catch (\Exception $e) {
            Log::error('Error fetching most requested items: ' . $e->getMessage());
            $mostRequestedItems = collect([]);
        }
        
        // Get recent critical requests
        $recentRequests = ItemRequest::with('user')
            ->where(function($query) {
                $query->whereIn('status', ['pending', 'urgent'])
                    ->orWhere('priority', 'urgent');
            })
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // ✅ AUDIT LOG: Viewed dashboard
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'viewed',
            'module' => 'admin',
            'description' => 'Viewed admin dashboard',
            'new_data' => $stats,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'performed_at' => now(),
        ]);

        return view('admin.dashboard', compact('stats', 'mostRequestedItems', 'recentRequests'));
    }

    // ==================== ORDER MANAGEMENT ====================
    // Order Management Dashboard
    public function orderDashboard()
    {
        $stats = [
            'pending_requests' => ItemRequest::where('status', 'pending')->count(),
            'approved_requests' => ItemRequest::where('status', 'approved')->count(),
            'rejected_requests' => ItemRequest::where('status', 'rejected')->count(),
            'total_issuances' => Issuance::count(),
            'pending_issuances' => Issuance::where('status', 'pending')->count(),
            'overdue_items' => IssuanceItem::where('status', 'issued')
                ->whereNotNull('due_date')
                ->where('due_date', '<', now())->count(),
        ];
        
        $recentRequests = ItemRequest::with(['user', 'requestItems.item'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
            
        $recentIssuances = Issuance::with(['itemRequest.user', 'issuanceItems.item'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // ✅ AUDIT LOG: Viewed order dashboard
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'viewed',
            'module' => 'orders',
            'description' => 'Viewed order management dashboard',
            'new_data' => $stats,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'performed_at' => now(),
        ]);

        return view('admin.orders.dashboard', compact('stats', 'recentRequests', 'recentIssuances'));
    }

    // Display all pending requests
    public function pendingOrders(Request $request)
    {
        $query = ItemRequest::with(['user', 'requestItems.item.category'])
            ->where('status', 'pending');
            
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('purpose', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        
        $requests = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        // ✅ AUDIT LOG: Viewed pending orders list
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'viewed_list',
            'module' => 'orders',
            'description' => 'Viewed pending requests list',
            'old_data' => ['filters' => $request->only(['search', 'priority'])],
            'new_data' => ['result_count' => $requests->total()],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'performed_at' => now(),
        ]);

        return view('admin.orders.pending', compact('requests'));
    }

    // Display all approved requests
    public function approvedOrders(Request $request)
    {
        $query = ItemRequest::with(['user', 'requestItems.item', 'issuance'])
            ->where('status', 'approved');
            
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('purpose', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                  });
            });
        }
        
        $requests = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();
            
        // ✅ AUDIT LOG: Viewed approved orders list
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'viewed_list',
            'module' => 'orders',
            'description' => 'Viewed approved requests list',
            'old_data' => ['filters' => $request->only(['search'])],
            'new_data' => ['result_count' => $requests->total()],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'performed_at' => now(),
        ]);
        
        return view('admin.orders.approved', compact('requests'));
    }

    // Display all rejected requests
    public function rejectedOrders(Request $request)
    {
        $query = ItemRequest::with(['user', 'requestItems.item'])
            ->where('status', 'rejected');
            
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('purpose', 'like', "%{$search}%");
        }
        
        $requests = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();
            
        // ✅ AUDIT LOG: Viewed rejected orders list
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'viewed_list',
            'module' => 'orders',
            'description' => 'Viewed rejected requests list',
            'old_data' => ['filters' => $request->only(['search'])],
            'new_data' => ['result_count' => $requests->total()],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'performed_at' => now(),
        ]);
        
        return view('admin.orders.rejected', compact('requests'));
    }

    // Review a specific request
    public function reviewOrder($id)
    {
        $request = ItemRequest::with(['user', 'requestItems.item.category'])
            ->findOrFail($id);
            
        $availabilityIssues = [];
        foreach ($request->requestItems as $requestItem) {
            $item = $requestItem->item;
            $available = $item->quantity - $item->minimum_quantity;  
            if ($available < $requestItem->quantity) {
                $availabilityIssues[] = [
                    'item' => $item,
                    'requested' => $requestItem->quantity,
                    'available' => max(0, $available),
                    'shortage' => max(0, $requestItem->quantity - $available),
                ];
            }
        }
        
        // ✅ AUDIT LOG: Viewed request review
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'viewed',
            'module' => 'orders',
            'description' => "Viewed review for request #{$request->id}",
            'model_type' => ItemRequest::class,
            'model_id' => $request->id,
            'new_data' => ['has_availability_issues' => !empty($availabilityIssues)],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'performed_at' => now(),
        ]);
        
        return view('admin.orders.review', compact('request', 'availabilityIssues'));
    }

    // Approve a request
    public function approveOrder(Request $request, $id)
    {
        $validated = $request->validate([
            'scheduled_date' => 'nullable|date|after_or_equal:today',
            'notes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $itemRequest = ItemRequest::findOrFail($id);

            if ($itemRequest->status !== 'pending') {
                return back()->with('error', 'Request has already been processed.');
            }

            $itemRequest->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'scheduled_issue_date' => $validated['scheduled_date'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            RequestItem::where('item_request_id', $id)
                ->update(['status' => 'approved']);

            Notification::create([
                'user_id' => $itemRequest->user_id,
                'type' => 'request_approved',
                'title' => 'Request Approved',
                'message' => "Your request #{$itemRequest->id} has been approved.",
                'data' => ['request_id' => $itemRequest->id],
            ]);

            // ✅ AUDIT LOG: Request approved
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'approved',
                'module' => 'orders',
                'description' => "Approved request #{$itemRequest->id}",
                'model_type' => ItemRequest::class,
                'model_id' => $itemRequest->id,
                'old_data' => ['status' => 'pending'],
                'new_data' => [
                    'status' => 'approved',
                    'scheduled_date' => $validated['scheduled_date'] ?? null,
                    'notes' => $validated['notes'] ?? null
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'performed_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('admin.orders.pending')
                ->with('success', 'Request approved successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // ✅ AUDIT LOG: Approval failed
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'approve_failed',
                'module' => 'orders',
                'description' => "Failed to approve request #{$id}: " . $e->getMessage(),
                'model_type' => ItemRequest::class,
                'model_id' => $id,
                'old_data' => $validated,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'performed_at' => now(),
            ]);
            
            return back()->with('error', 'Failed to approve request: ' . $e->getMessage());
        }
    }

    // Reject a request
    public function rejectOrder(Request $request, $id)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);
        
        DB::beginTransaction();
        try {
            $itemRequest = ItemRequest::findOrFail($id);
            
            if ($itemRequest->status !== 'pending') {
                return back()->with('error', 'Request has already been processed.');
            }
            
            $itemRequest->update([
                'status' => 'rejected',
                'rejected_by' => auth()->id(),
                'rejected_at' => now(),
                'rejection_reason' => $validated['rejection_reason'],
            ]);
            
            // Update request items status
            RequestItem::where('item_request_id', $id)->update(['status' => 'rejected']);
            
            // Create notification for user
            Notification::create([
                'user_id' => $itemRequest->user_id,
                'type' => 'request_rejected',
                'title' => 'Request Rejected',
                'message' => "Your request #{$itemRequest->id} has been rejected. Reason: " . $validated['rejection_reason'],
                'data' => ['request_id' => $itemRequest->id],
            ]);
            
            // ✅ AUDIT LOG: Request rejected
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'rejected',
                'module' => 'orders',
                'description' => "Rejected request #{$itemRequest->id}",
                'model_type' => ItemRequest::class,
                'model_id' => $itemRequest->id,
                'old_data' => ['status' => 'pending'],
                'new_data' => [
                    'status' => 'rejected',
                    'rejection_reason' => $validated['rejection_reason']
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'performed_at' => now(),
            ]);
            
            DB::commit();
            
            return redirect()->route('admin.orders.pending')
                ->with('success', 'Request rejected successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            // ✅ AUDIT LOG: Rejection failed
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'reject_failed',
                'module' => 'orders',
                'description' => "Failed to reject request #{$id}: " . $e->getMessage(),
                'model_type' => ItemRequest::class,
                'model_id' => $id,
                'old_data' => $validated,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'performed_at' => now(),
            ]);
            
            return back()->with('error', 'Failed to reject request: ' . $e->getMessage());
        }
    }

    // Create issuance for approved request
    public function createIssuance($id)
    {
        $request = ItemRequest::with(['user', 'requestItems.item'])
            ->where('status', 'approved')
            ->findOrFail($id);
            
        // Check if already has issuance
        if ($request->issuance) {
            return redirect()->route('admin.orders.issuances.view', $request->issuance->id)
                ->with('info', 'Issuance already exists for this request.');
        }
        
        // ✅ AUDIT LOG: Viewed create issuance form
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'viewed_create_form',
            'module' => 'issuances',
            'description' => "Viewed create issuance form for request #{$id}",
            'model_type' => ItemRequest::class,
            'model_id' => $id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'performed_at' => now(),
        ]);
        
        return view('admin.orders.create-issuance', compact('request'));
    }

    // Process issuance
    public function processIssuance(Request $request, $id)
    {
        $validated = $request->validate([
            'issued_items' => 'required|array',
            'issued_items.*.item_id' => 'required|exists:items,id',
            'issued_items.*.quantity' => 'required|integer|min:1',
            'issued_items.*.due_date' => 'nullable|date|after_or_equal:today',
            'remarks' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            // Load the item request with its items
            $itemRequest = ItemRequest::with(['requestItems.item'])->findOrFail($id);

            // Verify request is approved
            if ($itemRequest->status !== 'approved') {
                throw new \Exception('Only approved requests can be processed for issuance.');
            }

            // Create issuance record
            $issuance = Issuance::create([
                'item_request_id' => $itemRequest->id,
                'issued_by' => auth()->id(),
                'issued_at' => now(),
                'status' => 'pending',
                'remarks' => $validated['remarks'] ?? null,
            ]);

            $itemsProcessed = 0;
            $totalItems = count($itemRequest->requestItems);

            foreach ($validated['issued_items'] as $issuedItem) {
                // Find the corresponding request item
                $requestItem = $itemRequest->requestItems
                    ->where('item_id', $issuedItem['item_id'])
                    ->first();

                if (!$requestItem) {
                    throw new \Exception("Item ID {$issuedItem['item_id']} not found in the original request.");
                }

                // Validate quantity doesn't exceed approved quantity
                $approvedQty = $requestItem->approved_quantity ?? $requestItem->quantity;
                if ($issuedItem['quantity'] > $approvedQty) {
                    throw new \Exception(
                        "Cannot issue more than approved quantity for item {$requestItem->item->name}. " .
                        "Approved: {$approvedQty}, Requested: {$issuedItem['quantity']}"
                    );
                }

                // Check inventory
                $item = $requestItem->item;
                if ($item->quantity < $issuedItem['quantity']) {
                    throw new \Exception(
                        "Insufficient stock for {$item->name}. Available: {$item->quantity}, " .
                        "Requested: {$issuedItem['quantity']}"
                    );
                }

                // Create issuance item record
                IssuanceItem::create([
                    'issuance_id' => $issuance->id,
                    'item_request_id' => $itemRequest->id,
                    'request_item_id' => $requestItem->id,
                    'item_id' => $issuedItem['item_id'],
                    'quantity_issued' => $issuedItem['quantity'],
                    'quantity_returned' => 0,
                    'issue_date' => now()->toDateString(),
                    'due_date' => $issuedItem['due_date'] ?? null,
                    'status' => 'issued',
                    'unit_cost_at_time' => $item->unit_cost ?? 0,
                    'total_cost' => ($item->unit_cost ?? 0) * $issuedItem['quantity'],
                    'notes' => $validated['remarks'] ?? null,
                ]);

                // Reduce inventory
                $item->decrement('quantity', $issuedItem['quantity']);

                $itemsProcessed++;
            }

            // Update issuance status
            if ($itemsProcessed == 0) {
                $issuance->update(['status' => 'pending']);
            } elseif ($itemsProcessed < $totalItems) {
                $issuance->update(['status' => 'partially_completed']);
            } else {
                $issuance->update(['status' => 'completed']);
            }

            // Update item request issuance status
            $this->updateItemRequestIssuanceStatus($itemRequest);

            // Create notification
            Notification::create([
                'user_id' => $itemRequest->user_id,
                'type' => 'items_issued',
                'title' => 'Items Issued',
                'message' => "Items from your request #{$itemRequest->id} have been issued.",
                'data' => [
                    'request_id' => $itemRequest->id,
                    'issuance_id' => $issuance->id,
                    'items_issued' => $itemsProcessed
                ],
            ]);

            // ✅ AUDIT LOG: Issuance processed
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'issued',
                'module' => 'issuances',
                'description' => "Processed issuance for request #{$itemRequest->id}",
                'model_type' => Issuance::class,
                'model_id' => $issuance->id,
                'new_data' => [
                    'issuance_id' => $issuance->id,
                    'items_processed' => $itemsProcessed,
                    'total_items' => $totalItems
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'performed_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('admin.orders.issuances')
                ->with('success', "Issuance created successfully. {$itemsProcessed} item(s) issued.");

        } catch (\Exception $e) {
            DB::rollBack();
            
            // ✅ AUDIT LOG: Issuance failed
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'issue_failed',
                'module' => 'issuances',
                'description' => "Failed to process issuance for request #{$id}: " . $e->getMessage(),
                'model_type' => ItemRequest::class,
                'model_id' => $id,
                'old_data' => $validated,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'performed_at' => now(),
            ]);
            
            \Log::error('Issuance processing failed: ' . $e->getMessage(), [
                'request_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Failed to process issuance: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Helper method to update item request issuance status
     */
    private function updateItemRequestIssuanceStatus(ItemRequest $itemRequest)
    {
        $totalItems = $itemRequest->requestItems->count();
        $issuedCount = IssuanceItem::where('item_request_id', $itemRequest->id)
            ->distinct('request_item_id')
            ->count('request_item_id');
        
        if ($issuedCount == 0) {
            $status = 'not_issued';
        } elseif ($issuedCount < $totalItems) {
            $status = 'partially_issued';
        } else {
            $status = 'fully_issued';
        }
        
        $itemRequest->update([
            'issuance_status' => $status,
            'actual_issue_date' => now(),
            'issued_by' => auth()->id(),
        ]);
    }

    // View all issuances
    public function issuances(Request $request)
    {
        $query = Issuance::with(['itemRequest.user', 'issuanceItems.item']);
        
        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                ->orWhereHas('itemRequest.user', function($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                });
            });
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $issuances = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();
            
        // ✅ AUDIT LOG: Viewed issuances list
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'viewed_list',
            'module' => 'issuances',
            'description' => 'Viewed issuances list',
            'old_data' => ['filters' => $request->only(['search', 'status'])],
            'new_data' => ['result_count' => $issuances->total()],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'performed_at' => now(),
        ]);
            
        return view('admin.orders.issuances', compact('issuances'));
    }

    // View single issuance
    public function viewIssuance($id)
    {
        $issuance = Issuance::with([
            'itemRequest.user',
            'issuanceItems.item.category',
            'issuer'
        ])->findOrFail($id);
        
        // ✅ AUDIT LOG: Viewed issuance details
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'viewed',
            'module' => 'issuances',
            'description' => "Viewed issuance #{$id} details",
            'model_type' => Issuance::class,
            'model_id' => $id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'performed_at' => now(),
        ]);

        return view('admin.orders.view-issuance', compact('issuance'));
    }

    // Track returns
    public function returns(Request $request)
    {
        $query = IssuanceItem::with(['issuance.itemRequest.user', 'item'])
            ->where('status', 'issued')
            ->whereNotNull('due_date');
            
        // Filter overdue items
        if ($request->has('overdue')) {
            $query->where('due_date', '<', now());
        }
        
        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('item', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })->orWhereHas('issuance.itemRequest.user', function($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
                });
            });
        }
        
        $issuanceItems = $query->orderBy('due_date', 'asc')
            ->paginate(15)
            ->withQueryString();
            
        // ✅ AUDIT LOG: Viewed returns list
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'viewed_list',
            'module' => 'returns',
            'description' => 'Viewed returns list',
            'old_data' => ['filters' => $request->only(['search', 'overdue'])],
            'new_data' => ['result_count' => $issuanceItems->total()],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'performed_at' => now(),
        ]);

        return view('admin.orders.returns', compact('issuanceItems'));
    }

    // Process item return
    public function processReturn(Request $request, $id)
    {
        $validated = $request->validate([
            'returned_quantity' => 'required|integer|min:1',
            'condition' => 'required|in:good,damaged,lost',
            'notes' => 'nullable|string|max:500',
        ]);
        
        DB::beginTransaction();
        try {
            $issuanceItem = IssuanceItem::with(['item', 'issuance.itemRequest'])->findOrFail($id);

            if ($validated['returned_quantity'] > $issuanceItem->quantity_issued) {
                throw new \Exception('Returned quantity cannot exceed issued quantity.');
            }
            
            $oldStatus = $issuanceItem->status;
            $oldQuantityReturned = $issuanceItem->quantity_returned;
            
            // Update issuance item
            $issuanceItem->update([
                'quantity_returned' => $validated['returned_quantity'],
                'status' => ($validated['condition'] === 'lost') ? 'lost' : 'returned',
                'notes' => $validated['notes'],
            ]);
            
            // Restock item if in good condition
            if ($validated['condition'] === 'good') {
                $issuanceItem->item->increment('quantity', $validated['returned_quantity']);
            }
            
            // Check if all items in issuance are returned
            $issuance = $issuanceItem->issuance;
            $remainingItems = $issuance->issuanceItems()->where('status', 'issued')->count();
            if ($remainingItems === 0) {
                $issuance->update(['status' => 'completed']);
            }
            
            // Create notification for user
            Notification::create([
                'user_id' => $issuance->itemRequest->user_id,
                'type' => 'item_returned',
                'title' => 'Item Returned',
                'message' => "Item {$issuanceItem->item->name} has been returned.",
                'data' => ['issuance_id' => $issuance->id, 'item_id' => $issuanceItem->item_id],
            ]);
            
            // ✅ AUDIT LOG: Return processed
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'returned',
                'module' => 'returns',
                'description' => "Processed return for item from issuance #{$issuance->id}",
                'model_type' => IssuanceItem::class,
                'model_id' => $id,
                'old_data' => [
                    'status' => $oldStatus,
                    'quantity_returned' => $oldQuantityReturned
                ],
                'new_data' => [
                    'status' => $issuanceItem->status,
                    'quantity_returned' => $validated['returned_quantity'],
                    'condition' => $validated['condition'],
                    'notes' => $validated['notes']
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'performed_at' => now(),
            ]);
            
            DB::commit();
            
            return back()->with('success', 'Return processed successfully.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            // ✅ AUDIT LOG: Return failed
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'return_failed',
                'module' => 'returns',
                'description' => "Failed to process return: " . $e->getMessage(),
                'model_type' => IssuanceItem::class,
                'model_id' => $id,
                'old_data' => $validated,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'performed_at' => now(),
            ]);
            
            return back()->with('error', 'Failed to process return: ' . $e->getMessage());
        }
    }

    // Generate reports
    public function reports(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));
        
        // Monthly statistics
        $monthlyStats = ItemRequest::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('COUNT(*) as total_requests'),
                DB::raw('SUM(CASE WHEN status = "approved" THEN 1 ELSE 0 END) as approved'),
                DB::raw('SUM(CASE WHEN status = "rejected" THEN 1 ELSE 0 END) as rejected'),
                DB::raw('SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending')
            )
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->get();
            
        // Top requested items
        $topItems = RequestItem::join('items', 'request_items.item_id', '=', 'items.id')
            ->select(
                'items.name',
                DB::raw('SUM(request_items.quantity) as total_requested'),
                DB::raw('COUNT(DISTINCT request_items.item_request_id) as request_count')
            )
            ->whereYear('request_items.created_at', $year)
            ->groupBy('items.id', 'items.name')
            ->orderBy('total_requested', 'desc')
            ->limit(10)
            ->get();
            
        // Issuance statistics
        $issuanceStats = IssuanceItem::select(
                DB::raw('SUM(quantity_issued) as total_issued'),
                DB::raw('SUM(quantity_returned) as total_returned'),
                DB::raw('COUNT(DISTINCT issuance_id) as total_issuances')
            )
            ->whereYear('created_at', $year)
            ->first();
            
        // Overdue items
        $overdueItems = IssuanceItem::with(['item', 'issuance.itemRequest.user'])
            ->where('status', 'issued')
            ->whereNotNull('due_date')
            ->where('due_date', '<', now())
            ->count();
            
        // ✅ AUDIT LOG: Viewed reports
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'viewed_report',
            'module' => 'reports',
            'description' => "Viewed reports for year {$year}",
            'new_data' => [
                'year' => $year,
                'month' => $month,
                'total_requests' => $monthlyStats->sum('total_requests'),
                'total_issuances' => $issuanceStats->total_issuances ?? 0
            ],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'performed_at' => now(),
        ]);

        return view('admin.orders.reports', compact(
            'monthlyStats',
            'topItems',
            'issuanceStats',
            'overdueItems',
            'year',
            'month'
        ));
    }

    // Export data
    public function export(Request $request)
    {
        $type = $request->get('type', 'requests');
        $format = $request->get('format', 'csv');
        
        $count = 0;
        switch ($type) {
            case 'requests':
                $data = ItemRequest::with(['user', 'requestItems.item'])->get();
                $count = $data->count();
                $filename = 'requests_' . date('Y-m-d') . '.csv';
                break;
            case 'issuances':
                $data = Issuance::with(['itemRequest.user', 'issuanceItems.item'])->get();
                $count = $data->count();
                $filename = 'issuances_' . date('Y-m-d') . '.csv';
                break;
            case 'returns':
                $data = IssuanceItem::with(['item', 'issuance.itemRequest.user'])
                    ->where('status', 'returned')
                    ->get();
                $count = $data->count();
                $filename = 'returns_' . date('Y-m-d') . '.csv';
                break;
            default:
                return back()->with('error', 'Invalid export type.');
        }
        
        // ✅ AUDIT LOG: Export action
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'exported',
            'module' => $type,
            'description' => "Exported {$type} data",
            'new_data' => [
                'type' => $type,
                'format' => $format,
                'record_count' => $count
            ],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'performed_at' => now(),
        ]);
        
        // Generate CSV (simplified version)
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($data, $type) {
            $file = fopen('php://output', 'w');  
            
            // Write headers based on type
            if ($type === 'requests') {
                fputcsv($file, ['ID', 'User', 'Purpose', 'Status', 'Created At', 'Items']);
                foreach ($data as $request) {
                    fputcsv($file, [
                        $request->id,
                        $request->user->name ?? 'N/A',
                        $request->purpose,
                        $request->status,
                        $request->created_at->format('Y-m-d H:i'),
                        $request->requestItems->count(),
                    ]);
                }
            }
            // Add more cases for other types...
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function inventory(): \Illuminate\View\View
    {
        $items = Item::with('category')->paginate(15);
        
        // ✅ AUDIT LOG: Viewed inventory list
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'viewed_list',
            'module' => 'inventory',
            'description' => 'Viewed inventory list from admin',
            'new_data' => ['item_count' => $items->total()],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'performed_at' => now(),
        ]);
        
        return view('admin.inventory', ['items' => $items]);
    }
}