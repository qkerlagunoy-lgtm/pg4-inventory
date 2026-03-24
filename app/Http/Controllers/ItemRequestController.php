<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemRequest;
use App\Models\RequestItem;
use App\Models\AuditLog; // ADD THIS
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ItemRequestController extends Controller
{
    
    public function index()
    {
        $items = Item::available()->with('category')->get();
        
        // ✅ AUDIT LOG: Viewed available items list
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'viewed_list',
            'module' => 'requests',
            'description' => 'Viewed available items for request',
            'new_data' => ['available_items_count' => $items->count()],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'performed_at' => now(),
        ]);

        return view('requests.index', compact('items'));
    }

    public function cart()
    {
        $cart = session()->get('cart', []);
        // Get item details for cart display
        $cartItems = [];
        foreach ($cart as $itemId => $cartItem) {
            $item = Item::with('category')->find($itemId);
            if ($item) {
                $cartItems[$itemId] = array_merge($cartItem, [
                    'item' => $item,
                    'item_name' => $item->name, 
                    'category_name' => $item->category->name ?? 'Uncategorized',
                ]);
            }
        }

        // ✅ AUDIT LOG: Viewed cart
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'viewed_cart',
            'module' => 'requests',
            'description' => 'Viewed shopping cart',
            'new_data' => ['cart_items_count' => count($cartItems)],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'performed_at' => now(),
        ]);

        return view('requests.cart', compact('cartItems'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:255',
        ]);

        $item = Item::findOrFail($request->item_id);

        // Check if item is available
        if (!$item->isAvailable()) {
            return back()->with('error', 'Item is not available for request');
        }

        // Check if quantity is available
        if ($item->quantity < $request->quantity) {
            return back()->with('error', 'Not enough items available. Only ' . $item->quantity . ' left.');
        }

        $cart = session()->get('cart', []);
        $previousQuantity = 0;

        // If item already in cart, update quantity
        if (isset($cart[$item->id])) {
            $previousQuantity = $cart[$item->id]['quantity'];
            $newQuantity = $cart[$item->id]['quantity'] + $request->quantity;
            
            // Check if total requested exceeds available quantity
            if ($item->quantity < $newQuantity) {
                return back()->with('error', 'Total requested quantity exceeds available stock');
            }
            
            $cart[$item->id]['quantity'] = $newQuantity;
            // Update notes if provided
            if ($request->has('notes')) {
                $cart[$item->id]['notes'] = $request->notes;
            }
        } else {
            $cart[$item->id] = [
                'item_id' => $item->id,
                'quantity' => $request->quantity,
                'notes' => $request->notes ?? '',
            ];
        }

        session()->put('cart', $cart);

        // ✅ AUDIT LOG: Added item to cart
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'added_to_cart',
            'module' => 'requests',
            'description' => "Added item '{$item->name}' to cart",
            'model_type' => Item::class,
            'model_id' => $item->id,
            'old_data' => ['previous_quantity' => $previousQuantity],
            'new_data' => [
                'item_id' => $item->id,
                'item_name' => $item->name,
                'quantity' => $cart[$item->id]['quantity'],
                'notes' => $request->notes ?? ''
            ],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'performed_at' => now(),
        ]);

        return back()->with('success', 'Item added to cart!');
    }

    public function updateCart(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:255',
        ]);

        $cart = session()->get('cart', []);
        
        if (!isset($cart[$itemId])) {
            return back()->with('error', 'Item not found in cart');
        }

        $item = Item::findOrFail($itemId);
        $oldQuantity = $cart[$itemId]['quantity'];
        $oldNotes = $cart[$itemId]['notes'] ?? '';
        
        // Check if item is still available
        if (!$item->isAvailable()) {
            return back()->with('error', 'Item is no longer available');
        }
        
        // Check if new quantity is available
        if ($item->quantity < $request->quantity) {
            return back()->with('error', 'Not enough items available. Only ' . $item->quantity . ' left.');
        }

        $cart[$itemId]['quantity'] = $request->quantity;
        $cart[$itemId]['notes'] = $request->notes ?? $cart[$itemId]['notes'] ?? '';
        
        session()->put('cart', $cart);

        // ✅ AUDIT LOG: Updated cart item
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'updated_cart',
            'module' => 'requests',
            'description' => "Updated cart item '{$item->name}'",
            'model_type' => Item::class,
            'model_id' => $item->id,
            'old_data' => [
                'quantity' => $oldQuantity,
                'notes' => $oldNotes
            ],
            'new_data' => [
                'quantity' => $request->quantity,
                'notes' => $request->notes ?? ''
            ],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'performed_at' => now(),
        ]);

        return back()->with('success', 'Cart updated successfully');
    }

    public function removeFromCart($itemId)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$itemId])) {
            $item = Item::find($itemId);
            $itemData = $cart[$itemId];
            
            unset($cart[$itemId]);
            session()->put('cart', $cart);

            // ✅ AUDIT LOG: Removed item from cart
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'removed_from_cart',
                'module' => 'requests',
                'description' => "Removed item from cart" . ($item ? " '{$item->name}'" : ''),
                'model_type' => $item ? Item::class : null,
                'model_id' => $item ? $item->id : null,
                'old_data' => $itemData,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'performed_at' => now(),
            ]);

            return back()->with('success', 'Item removed from cart');
        }

        return back()->with('error', 'Item not found in cart');
    }

    public function clearCart()
    {
        $cart = session()->get('cart', []);
        $cartCount = count($cart);
        
        session()->forget('cart');

        // ✅ AUDIT LOG: Cleared cart
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'cleared_cart',
            'module' => 'requests',
            'description' => 'Cleared shopping cart',
            'old_data' => ['removed_items_count' => $cartCount],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'performed_at' => now(),
        ]);

        return back()->with('success', 'Cart cleared successfully');
    }

    public function submitRequest(Request $request)
    {
        
        $request->validate([
            'purpose' => 'required|string|max:255',
            'priority' => 'nullable|in:low,medium,high,urgent',
            'required_date' => 'nullable|date|after_or_equal:today',
            'remarks' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:500',
        ]);
        
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return back()->with('error', 'Cart is empty');
        }

        DB::beginTransaction();
        try {
            $itemRequest = ItemRequest::create([
                'user_id' => Auth::id(),
                'purpose' => $request->purpose,
                'priority' => $request->priority ?? 'medium',
                'status' => 'pending',
                'request_date' => now(),
                'required_date' => $request->required_date,
                'remarks' => $request->remarks, 
                'notes' => $request->notes,
            ]);

            $requestItems = [];
            // Add items to request
            foreach ($cart as $itemId => $cartItem) {
                $item = Item::find($itemId);
                if (!$item) {
                    throw new \Exception("Item not found: {$itemId}");
                }
                // Check if item is still available
                if (!$item->isAvailable()) {
                    throw new \Exception("Item '{$item->name}' is no longer available");
                }
                if ($item->quantity < $cartItem['quantity']) {
                    throw new \Exception("Item '{$item->name}' has insufficient quantity. Only {$item->quantity} available");
                }
                
                $requestItem = RequestItem::create([
                    'item_request_id' => $itemRequest->id,
                    'item_id' => $itemId,
                    'quantity' => $cartItem['quantity'],
                    'remarks' => $cartItem['notes'] ?? null,
                    'status' => 'pending',
                ]);
                
                $requestItems[] = [
                    'item_id' => $itemId,
                    'item_name' => $item->name,
                    'quantity' => $cartItem['quantity'],
                    'notes' => $cartItem['notes'] ?? null
                ];
            }

            // Clear cart
            session()->forget('cart');

            // ✅ AUDIT LOG: Request submitted
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'submitted',
                'module' => 'requests',
                'description' => "Submitted request #{$itemRequest->id}",
                'model_type' => ItemRequest::class,
                'model_id' => $itemRequest->id,
                'new_data' => [
                    'request_id' => $itemRequest->id,
                    'purpose' => $request->purpose,
                    'priority' => $request->priority ?? 'medium',
                    'items_count' => count($cart),
                    'items' => $requestItems,
                    'required_date' => $request->required_date,
                    'remarks' => $request->remarks,
                    'notes' => $request->notes
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'performed_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('requests.my-requests')->with('success', 'Request submitted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            // ✅ AUDIT LOG: Request submission failed
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'submit_failed',
                'module' => 'requests',
                'description' => "Failed to submit request: " . $e->getMessage(),
                'old_data' => [
                    'purpose' => $request->purpose,
                    'priority' => $request->priority,
                    'cart_items' => count($cart)
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'performed_at' => now(),
            ]);

            return back()->with('error', 'Failed to submit request: ' . $e->getMessage());
        }
    }

    public function myRequests(Request $request)
    {
        $query = ItemRequest::with(['requestItems.item.category'])
            ->where('user_id', Auth::id());

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Search by purpose
        if ($request->filled('search')) {
            $query->where('purpose', 'like', '%' . $request->search . '%');
        }

        $requests = $query->orderBy('created_at', 'desc')
            ->paginate(10);

        // ✅ AUDIT LOG: Viewed my requests list
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'viewed_my_requests',
            'module' => 'requests',
            'description' => 'Viewed my requests list',
            'old_data' => [
                'filters' => $request->only(['status', 'priority', 'search'])
            ],
            'new_data' => ['result_count' => $requests->total()],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'performed_at' => now(),
        ]);

        return view('requests.my-requests', compact('requests'));
    }

    public function show($id)
    {
        $request = ItemRequest::with(['requestItems.item.category', 'user'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // ✅ AUDIT LOG: Viewed request details
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'viewed',
            'module' => 'requests',
            'description' => "Viewed request #{$id} details",
            'model_type' => ItemRequest::class,
            'model_id' => $id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'performed_at' => now(),
        ]);

        return view('requests.show', compact('request'));
    }

    public function cancelRequest($id)
    {
        $request = ItemRequest::where('id', $id)
            ->where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'approved'])
            ->firstOrFail();

        DB::beginTransaction();
        try {
            $oldStatus = $request->status;
            
            $request->update([
                'status' => 'cancelled',
                'cancelled_by' => Auth::id(),
                'cancelled_at' => now(),
            ]);

            // ✅ AUDIT LOG: Request cancelled
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'cancelled',
                'module' => 'requests',
                'description' => "Cancelled request #{$id}",
                'model_type' => ItemRequest::class,
                'model_id' => $id,
                'old_data' => ['status' => $oldStatus],
                'new_data' => ['status' => 'cancelled'],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'performed_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('requests.my-requests')->with('success', 'Request cancelled successfully');

        } catch (\Exception $e) {
            DB::rollBack();

            // ✅ AUDIT LOG: Cancellation failed
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'cancel_failed',
                'module' => 'requests',
                'description' => "Failed to cancel request #{$id}: " . $e->getMessage(),
                'model_type' => ItemRequest::class,
                'model_id' => $id,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'performed_at' => now(),
            ]);

            return back()->with('error', 'Failed to cancel request');
        }
    }
}