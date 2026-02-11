<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Display all categories
     */
    public function index(Request $request)
    {
        $query = Category::withCount('items');
        
        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }
        
        // Filter by parent category
        if ($request->filled('parent_id')) {
            $query->where('parent_id', $request->parent_id);
        }
        
        $categories = $query->orderBy('name')->paginate(20);
        $parentCategories = Category::whereNull('parent_id')->get();
        
        return view('admin.categories.index', compact('categories', 'parentCategories'));
    }

    /**
     * Show create category form
     */
    public function create()
    {
        $parentCategories = Category::whereNull('parent_id')->get();
        return view('admin.categories.create', compact('parentCategories'));
    }

    /**
     * Store new category
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string|max:500',
            'parent_id' => 'nullable|exists:categories,id',
        ]);
        
        Category::create($validated);
        
        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Show single category with items
     */
    public function show(Category $category)
    {
        $category->load(['items' => function($query) {
            $query->orderBy('name');
        }, 'children']);
        
        return view('admin.categories.show', compact('category'));
    }

    /**
     * Show edit category form
     */
    public function edit(Category $category)
    {
        $parentCategories = Category::whereNull('parent_id')
            ->where('id', '!=', $category->id)
            ->get();
        
        return view('admin.categories.edit', compact('category', 'parentCategories'));
    }

    /**
     * Update category
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string|max:500',
            'parent_id' => 'nullable|exists:categories,id',
        ]);
        
        $category->update($validated);
        
        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Delete category
     */
    public function destroy(Category $category)
    {
        DB::beginTransaction();
        try {
            // Check if category has items
            if ($category->items()->count() > 0) {
                return back()->with('error', 'Cannot delete category that has items. Please reassign items first.');
            }
            
            // Move subcategories to parent or make them root
            if ($category->children()->count() > 0) {
                $category->children()->update(['parent_id' => $category->parent_id]);
            }
            
            $category->delete();
            
            DB::commit();
            return redirect()->route('admin.categories.index')
                ->with('success', 'Category deleted successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete category: ' . $e->getMessage());
        }
    }
}
