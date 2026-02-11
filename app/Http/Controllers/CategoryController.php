<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * INDEX
     */
    public function index(Request $request)
    {
        $query = Category::query();

        // SEARCH
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('code', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $categories = $query
            ->orderBy('code')
            ->paginate(10);

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * CREATE PAGE
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * STORE
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:categories,code',
            'description' => 'required|string',
            'is_active' => 'nullable|boolean'
        ]);

Category::create([
    'name' => $validated['code'], // AUTO FILL NAME
    'code' => $validated['code'],
    'description' => $validated['description'],
    'is_active' => $request->has('is_active') ? 1 : 0,
    'created_by' => Auth::id()
]);

  

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category created successfully');
    }

    /**
     * EDIT PAGE
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * UPDATE
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:categories,code,' . $category->id,
            'description' => 'required|string',
            'is_active' => 'nullable|boolean'
        ]);

        $category->update([
            'code' => $validated['code'],
            'description' => $validated['description'],
            'is_active' => $request->has('is_active') ? 1 : 0
        ]);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category updated successfully');
    }

    /**
     * DELETE SINGLE
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return back()->with('success', 'Category deleted');
    }

    /**
     * BULK DELETE
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'category_ids' => 'required|array'
        ]);

        Category::whereIn('id', $request->category_ids)->delete();

        return back()->with('success', 'Selected categories deleted');
    }

    /**
     * BULK STATUS UPDATE
     */
    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'category_ids' => 'required|array',
            'status' => 'required|in:0,1'
        ]);

        Category::whereIn('id', $request->category_ids)
            ->update(['is_active' => $request->status]);

        return back()->with('success', 'Status updated');
    }
}
