<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    /**
     * Display all inventory items
     */
    public function index(Request $request)
    {
        $query = Item::with('category');
        
        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('storage_location', 'like', "%{$search}%");
        }
        
        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        // Filter by stock level
        if ($request->filled('stock_level')) {
            switch ($request->stock_level) {
                case 'low':
                    $query->whereColumn('quantity', '<=', 'minimum_quantity');
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
            'name' => 'required|string|max:255|unique:items,name',
            'description' => 'nullable|string|max:1000',
            'category_id' => 'required|exists:categories,id',
            'quantity' => 'required|integer|min:0',
            'minimum_quantity' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'storage_location' => 'nullable|string|max:255',
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
        $item->load(['category', 'requestItems.itemRequest.user', 'issuanceItems.issuance.itemRequest.user']);
        
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
     */
    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:items,name,' . $item->id,
            'description' => 'nullable|string|max:1000',
            'category_id' => 'required|exists:categories,id',
            'minimum_quantity' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'storage_location' => 'nullable|string|max:255',
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
        // Check if item has transactions
        if ($item->requestItems()->count() > 0 || $item->issuanceItems()->count() > 0) {
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
        $items = Item::with('category')
            ->whereColumn('quantity', '<=', 'minimum_quantity')
            ->orWhere('quantity', 0)
            ->orderBy('quantity')
            ->paginate(20);
        
        return view('admin.inventory.low-stock', compact('items'));
    }

    /**
     * Restock item
     */
    public function restock(Request $request, Item $item)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500',
        ]);
        
        DB::beginTransaction();
        try {
            $oldQuantity = $item->quantity;
            $newQuantity = $oldQuantity + $validated['quantity'];
            
            $item->update(['quantity' => $newQuantity]);
            
            // Log the restock (you can use AuditLog model here)
            // AuditLog::create([...]);
            
            DB::commit();
            
            return back()->with('success', 
                "Item restocked successfully. {$validated['quantity']} {$item->unit} added. " .
                "New stock: {$newQuantity} {$item->unit}");
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to restock item: ' . $e->getMessage());
        }
    }
}
