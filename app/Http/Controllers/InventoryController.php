<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::with('category');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('unit_of_measure', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('stock_level')) {
            switch ($request->stock_level) {
                case 'low':
                    $query->where(function ($q) {
                        $q->whereColumn('quantity', '<=', 'minimum_quantity')
                          ->where('quantity', '>', 0);
                    });
                    break;
                case 'out':
                    $query->where('quantity', 0);
                    break;
                case 'normal':
                    $query->whereColumn('quantity', '>', 'minimum_quantity')
                          ->where('quantity', '>', 0);
                    break;
            }
        }

        $items      = $query->orderBy('name')->paginate(25);
        $categories = Category::orderBy('name')->get();

        return view('admin.inventory.index', compact('items', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $units = ['pcs', 'boxes', 'sets', 'units', 'packs', 'reams', 'bottles', 'cans'];

        return view('admin.inventory.create', compact('categories', 'units'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255|unique:items,name',
            'description'      => 'nullable|string|max:1000',
            'category_id'      => 'required|exists:categories,id',
            'quantity'         => 'required|integer|min:0',
            'minimum_quantity' => 'required|integer|min:1',
            'unit_of_measure'  => 'required|string|in:pcs,boxes,sets,units,packs,reams,bottles,cans',
            'image'            => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        DB::beginTransaction();
        try {
            if ($request->hasFile('image')) {
                $validated['image'] = $request->file('image')->store('items', 'public');
            }

            $item = Item::create($validated);

            AuditLog::create([
                'user_id'      => auth()->id(),
                'action'       => 'created',
                'module'       => 'inventory',
                'description'  => "Created new item: {$item->name}",
                'model_type'   => Item::class,
                'model_id'     => $item->id,
                'new_data'     => $item->toArray(),
                'ip_address'   => $request->ip(),
                'user_agent'   => $request->userAgent(),
                'url'          => $request->fullUrl(),
                'method'       => $request->method(),
                'performed_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('admin.inventory.index')
                ->with('success', 'Item added to inventory successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            AuditLog::create([
                'user_id'      => auth()->id(),
                'action'       => 'create_failed',
                'module'       => 'inventory',
                'description'  => "Failed to create item: " . $e->getMessage(),
                'old_data'     => $validated,
                'ip_address'   => $request->ip(),
                'user_agent'   => $request->userAgent(),
                'url'          => $request->fullUrl(),
                'method'       => $request->method(),
                'performed_at' => now(),
            ]);

            return back()->with('error', 'Failed to create item: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Item $item)
    {
        $item->load(['category', 'requestItems.itemRequest.user', 'auditLogs']);

        AuditLog::create([
            'user_id'      => auth()->id(),
            'action'       => 'viewed',
            'module'       => 'inventory',
            'description'  => "Viewed item details: {$item->name}",
            'model_type'   => Item::class,
            'model_id'     => $item->id,
            'ip_address'   => request()->ip(),
            'user_agent'   => request()->userAgent(),
            'url'          => request()->fullUrl(),
            'method'       => request()->method(),
            'performed_at' => now(),
        ]);

        return view('admin.inventory.show', compact('item'));
    }

    public function edit(Item $item)
    {
        $categories = Category::orderBy('name')->get();
        $units = ['pcs', 'boxes', 'sets', 'units', 'packs', 'reams', 'bottles', 'cans'];

        AuditLog::create([
            'user_id'      => auth()->id(),
            'action'       => 'viewed_edit_form',
            'module'       => 'inventory',
            'description'  => "Viewed edit form for item: {$item->name}",
            'model_type'   => Item::class,
            'model_id'     => $item->id,
            'ip_address'   => request()->ip(),
            'user_agent'   => request()->userAgent(),
            'url'          => request()->fullUrl(),
            'method'       => request()->method(),
            'performed_at' => now(),
        ]);

        return view('admin.inventory.edit', compact('item', 'categories', 'units'));
    }

    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('items', 'name')->ignore($item->id),
            ],
            'description'      => 'nullable|string|max:1000',
            'category_id'      => 'required|exists:categories,id',
            'minimum_quantity' => 'required|integer|min:1',
            'unit_of_measure'  => 'required|string|in:pcs,boxes,sets,units,packs,reams,bottles,cans',
            'image'            => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $oldData = $item->toArray();

            if ($request->hasFile('image')) {
                if ($item->image) {
                    Storage::disk('public')->delete($item->image);
                }
                $validated['image'] = $request->file('image')->store('items', 'public');
            }

            if ($request->input('remove_image') === '1' && $item->image) {
                Storage::disk('public')->delete($item->image);
                $validated['image'] = null;
            }

            $item->update($validated);

            AuditLog::create([
                'user_id'      => auth()->id(),
                'action'       => 'updated',
                'module'       => 'inventory',
                'description'  => "Updated item: {$item->name}",
                'model_type'   => Item::class,
                'model_id'     => $item->id,
                'old_data'     => $oldData,
                'new_data'     => $item->getChanges(),
                'ip_address'   => $request->ip(),
                'user_agent'   => $request->userAgent(),
                'url'          => $request->fullUrl(),
                'method'       => $request->method(),
                'performed_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('admin.inventory.show', $item)
                ->with('success', 'Item updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            AuditLog::create([
                'user_id'      => auth()->id(),
                'action'       => 'update_failed',
                'module'       => 'inventory',
                'description'  => "Failed to update item {$item->name}: " . $e->getMessage(),
                'model_type'   => Item::class,
                'model_id'     => $item->id,
                'old_data'     => $validated,
                'ip_address'   => $request->ip(),
                'user_agent'   => $request->userAgent(),
                'url'          => $request->fullUrl(),
                'method'       => $request->method(),
                'performed_at' => now(),
            ]);

            return back()->with('error', 'Failed to update item: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Item $item)
    {
        $hasHistory = $item->requestItems()->exists();

        if ($hasHistory) {
            return back()->with('error', 'Cannot delete item that has transaction history.');
        }

        DB::beginTransaction();
        try {
            $itemData = $item->toArray();
            $itemName = $item->name;

            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }

            $item->delete();

            AuditLog::create([
                'user_id'      => auth()->id(),
                'action'       => 'deleted',
                'module'       => 'inventory',
                'description'  => "Deleted item: {$itemName}",
                'model_type'   => Item::class,
                'model_id'     => $item->id,
                'old_data'     => $itemData,
                'ip_address'   => request()->ip(),
                'user_agent'   => request()->userAgent(),
                'url'          => request()->fullUrl(),
                'method'       => request()->method(),
                'performed_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('admin.inventory.index')
                ->with('success', 'Item deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete item: ' . $e->getMessage());
        }
    }

    public function lowStock(Request $request)
    {
        $items = Item::with('category')
            ->where(function ($q) {
                $q->whereColumn('quantity', '<=', 'minimum_quantity')
                  ->orWhere('quantity', 0);
            })
            ->orderByRaw('quantity / COALESCE(NULLIF(minimum_quantity, 0), 1)')
            ->paginate(20);

        $criticalItems = Item::with('category')
            ->where(function ($q) {
                $q->where('quantity', 0)
                  ->orWhereRaw('(quantity / COALESCE(NULLIF(minimum_quantity, 0), 1)) <= 0.25');
            })
            ->orderBy('quantity')
            ->get();

        $outOfStockCount = Item::where('quantity', 0)->count();

        $affectedCategoriesCount = Item::where(function ($q) {
                $q->whereColumn('quantity', '<=', 'minimum_quantity')
                  ->orWhere('quantity', 0);
            })
            ->distinct('category_id')
            ->count('category_id');

        $lowStockByCategory = Category::withCount([
            'items as low_stock_count' => function ($q) {
                $q->where(function ($inner) {
                    $inner->whereColumn('quantity', '<=', 'minimum_quantity')
                          ->orWhere('quantity', 0);
                });
            }
        ])
        ->having('low_stock_count', '>', 0)
        ->orderByDesc('low_stock_count')
        ->get();

        AuditLog::create([
            'user_id'      => auth()->id(),
            'action'       => 'viewed_report',
            'module'       => 'inventory',
            'description'  => 'Viewed low stock report',
            'new_data'     => [
                'low_stock_count' => $items->total(),
                'critical_count'  => $criticalItems->count(),
                'out_of_stock'    => $outOfStockCount,
            ],
            'ip_address'   => $request->ip(),
            'user_agent'   => $request->userAgent(),
            'url'          => $request->fullUrl(),
            'method'       => $request->method(),
            'performed_at' => now(),
        ]);

        return view('admin.inventory.low-stock', compact(
            'items', 'criticalItems', 'outOfStockCount',
            'affectedCategoriesCount', 'lowStockByCategory'
        ));
    }

    public function restock(Request $request, Item $item)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'notes'    => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $oldQuantity = $item->quantity;
            $newQuantity = $oldQuantity + $validated['quantity'];

            $item->update(['quantity' => $newQuantity]);

            AuditLog::create([
                'user_id'      => auth()->id(),
                'action'       => 'restocked',
                'module'       => 'inventory',
                'description'  => "Restocked item: {$item->name}",
                'model_type'   => Item::class,
                'model_id'     => $item->id,
                'old_data'     => ['quantity' => $oldQuantity],
                'new_data'     => ['quantity' => $newQuantity, 'notes' => $validated['notes'] ?? null],
                'remarks'      => $validated['notes'] ?? null,
                'ip_address'   => $request->ip(),
                'user_agent'   => $request->userAgent(),
                'url'          => $request->fullUrl(),
                'method'       => $request->method(),
                'performed_at' => now(),
            ]);

            DB::commit();

            return back()->with('success',
                "Item restocked successfully. {$validated['quantity']} {$item->unit_of_measure} added. " .
                "New stock: {$newQuantity} {$item->unit_of_measure}."
            );

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to restock item: ' . $e->getMessage());
        }
    }

    public function exportCsv(Request $request)
    {
        $query = Item::with('category');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('unit_of_measure', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('stock_level')) {
            switch ($request->stock_level) {
                case 'low':
                    $query->where(function ($q) {
                        $q->whereColumn('quantity', '<=', 'minimum_quantity')
                          ->where('quantity', '>', 0);
                    });
                    break;
                case 'out':
                    $query->where('quantity', 0);
                    break;
                case 'normal':
                    $query->whereColumn('quantity', '>', 'minimum_quantity')
                          ->where('quantity', '>', 0);
                    break;
            }
        }

        $items = $query->orderBy('name')->get();

        $filename = 'inventory_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () use ($items) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'ID', 'Name', 'Description', 'Category',
                'Quantity', 'Minimum Quantity', 'Unit of Measure',
                'Stock Status', 'Created At', 'Updated At',
            ]);

            foreach ($items as $item) {
                if ($item->quantity == 0) {
                    $status = 'Out of Stock';
                } elseif ($item->quantity <= $item->minimum_quantity) {
                    $status = 'Low Stock';
                } else {
                    $status = 'In Stock';
                }

                fputcsv($handle, [
                    $item->id,
                    $item->name,
                    $item->description ?? '',
                    $item->category->name ?? 'N/A',
                    $item->quantity,
                    $item->minimum_quantity,
                    $item->unit_of_measure,
                    $status,
                    $item->created_at->format('Y-m-d H:i:s'),
                    $item->updated_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}