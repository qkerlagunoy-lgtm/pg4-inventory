<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class InventoryController extends Controller
{
    /**
     * Display all inventory items
     */
    public function index(Request $request)
    {
        $query = Item::with('category');

        // BUG FIX: Wrapped search conditions in a closure so they don't
        // bleed into category/stock filters via ungrouped orWhere.
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('unit_of_measure', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by stock level
        if ($request->filled('stock_level')) {
            switch ($request->stock_level) {
                case 'low':
                    // BUG FIX: Wrapped in closure to prevent orWhere leaking
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

        $items = $query->orderBy('name')->paginate(25);
        $categories = Category::orderBy('name')->get();

        return view('admin.inventory.index', compact('items', 'categories'));
    }

    /**
     * Show create item form
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $units = ['pcs', 'boxes', 'sets', 'units', 'packs', 'reams', 'bottles', 'cans'];

        return view('admin.inventory.create', compact('categories', 'units'));
    }

    /**
     * Store new item
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255|unique:items,name',
            'description'      => 'nullable|string|max:1000',
            'category_id'      => 'required|exists:categories,id',
            'quantity'         => 'required|integer|min:0',
            'minimum_quantity' => 'required|integer|min:1',
            'unit_of_measure'  => 'required|string|in:pcs,boxes,sets,units,packs,reams,bottles,cans',
        ]);

        Item::create($validated);

        return redirect()->route('admin.inventory.index')
            ->with('success', 'Item added to inventory successfully.');
    }

    /**
     * Show single item
     */
    public function show(Item $item)
    {
        $item->load([
            'category',
            'requestItems.itemRequest.user',
            'auditLogs',
        ]);

        return view('admin.inventory.show', compact('item'));
    }

    /**
     * Show edit item form
     */
    public function edit(Item $item)
    {
        $categories = Category::orderBy('name')->get();
        $units = ['pcs', 'boxes', 'sets', 'units', 'packs', 'reams', 'bottles', 'cans'];

        return view('admin.inventory.edit', compact('item', 'categories', 'units'));
    }

    /**
     * Update item
     * NOTE: quantity is intentionally excluded — stock changes go through restock().
     */
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
        ]);

        $item->update($validated);

        return redirect()->route('admin.inventory.show', $item)
            ->with('success', 'Item updated successfully.');
    }

    /**
     * Delete item
     */
    public function destroy(Item $item)
    {
        // BUG FIX: Consolidated into a single check to avoid race condition
        // between two separate count queries.
        $hasHistory = $item->requestItems()->exists();

        if ($hasHistory) {
            return back()->with('error', 'Cannot delete item that has transaction history.');
        }

        $item->delete();

        return redirect()->route('admin.inventory.index')
            ->with('success', 'Item deleted successfully.');
    }

    /**
     * Show low stock items
     */
    public function lowStock()
    {
        // BUG FIX: Wrapped orWhere conditions in closures throughout this method
        // so that "quantity = 0" does not match items outside the intended scope.

        // All low stock items (at or below minimum, or completely out)
        $items = Item::with('category')
            ->where(function ($q) {
                $q->whereColumn('quantity', '<=', 'minimum_quantity')
                  ->orWhere('quantity', 0);
            })
            // BUG FIX: Use COALESCE to safely handle minimum_quantity = 0
            // and avoid division-by-zero in the ORDER BY clause.
            ->orderByRaw('quantity / COALESCE(NULLIF(minimum_quantity, 0), 1)')
            ->paginate(20);

        // Critical items: out of stock OR at/below 25% of minimum
        $criticalItems = Item::with('category')
            ->where(function ($q) {
                $q->where('quantity', 0)
                  ->orWhereRaw('(quantity / COALESCE(NULLIF(minimum_quantity, 0), 1)) <= 0.25');
            })
            ->orderBy('quantity')
            ->get();

        // Statistics
        $outOfStockCount = Item::where('quantity', 0)->count();

        // BUG FIX: Wrapped orWhere in closure so out-of-stock items don't
        // inflate the affected category count incorrectly.
        $affectedCategoriesCount = Item::where(function ($q) {
                $q->whereColumn('quantity', '<=', 'minimum_quantity')
                  ->orWhere('quantity', 0);
            })
            ->distinct('category_id')
            ->count('category_id');

        // Low stock items grouped by category
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

        return view('admin.inventory.low-stock', compact(
            'items',
            'criticalItems',
            'outOfStockCount',
            'affectedCategoriesCount',
            'lowStockByCategory'
        ));
    }

    /**
     * Restock item
     */
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

            // BUG FIX: Audit log was left as a dead comment. Wire this to your
            // AuditLog model. Example (uncomment and adjust fields as needed):
            //
            // AuditLog::create([
            //     'item_id'      => $item->id,
            //     'action'       => 'restock',
            //     'old_quantity' => $oldQuantity,
            //     'new_quantity' => $newQuantity,
            //     'notes'        => $validated['notes'] ?? null,
            //     'performed_by' => auth()->id(),
            // ]);

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
} 