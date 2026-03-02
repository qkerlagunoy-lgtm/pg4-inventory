<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\AuditLog; // ADD THIS
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        // ✅ AUDIT LOG: Viewed categories list
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'viewed_list',
            'module' => 'categories',
            'description' => 'Viewed categories list',
            'old_data' => ['search' => $request->search],
            'new_data' => ['result_count' => $categories->total()],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'performed_at' => now(),
        ]);

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * CREATE PAGE
     */
    public function create()
    {
        // ✅ AUDIT LOG: Viewed create form
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'viewed_create_form',
            'module' => 'categories',
            'description' => 'Viewed create category form',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'performed_at' => now(),
        ]);

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

        DB::beginTransaction();
        try {
            $category = Category::create([
                'name' => $validated['code'], // AUTO FILL NAME
                'code' => $validated['code'],
                'description' => $validated['description'],
                'is_active' => $request->has('is_active') ? 1 : 0,
                'created_by' => Auth::id()
            ]);

            // ✅ AUDIT LOG: Category creation
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'created',
                'module' => 'categories',
                'description' => "Created new category: {$category->code}",
                'model_type' => Category::class,
                'model_id' => $category->id,
                'new_data' => [
                    'code' => $category->code,
                    'description' => $category->description,
                    'is_active' => $category->is_active
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'performed_at' => now(),
            ]);

            DB::commit();

            return redirect()
                ->route('admin.categories.index')
                ->with('success', 'Category created successfully');

        } catch (\Exception $e) {
            DB::rollBack();

            // ✅ AUDIT LOG: Creation failed
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'create_failed',
                'module' => 'categories',
                'description' => "Failed to create category: " . $e->getMessage(),
                'old_data' => $validated,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'performed_at' => now(),
            ]);

            return back()->with('error', 'Failed to create category: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * EDIT PAGE
     */
    public function edit(Category $category)
    {
        // ✅ AUDIT LOG: Viewed edit form
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'viewed_edit_form',
            'module' => 'categories',
            'description' => "Viewed edit form for category: {$category->code}",
            'model_type' => Category::class,
            'model_id' => $category->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'performed_at' => now(),
        ]);

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

        DB::beginTransaction();
        try {
            $oldData = [
                'code' => $category->code,
                'description' => $category->description,
                'is_active' => $category->is_active
            ];

            $category->update([
                'code' => $validated['code'],
                'description' => $validated['description'],
                'is_active' => $request->has('is_active') ? 1 : 0
            ]);

            // ✅ AUDIT LOG: Category update
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'updated',
                'module' => 'categories',
                'description' => "Updated category: {$category->code}",
                'model_type' => Category::class,
                'model_id' => $category->id,
                'old_data' => $oldData,
                'new_data' => [
                    'code' => $category->code,
                    'description' => $category->description,
                    'is_active' => $category->is_active
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'performed_at' => now(),
            ]);

            DB::commit();

            return redirect()
                ->route('admin.categories.index')
                ->with('success', 'Category updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();

            // ✅ AUDIT LOG: Update failed
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'update_failed',
                'module' => 'categories',
                'description' => "Failed to update category {$category->code}: " . $e->getMessage(),
                'model_type' => Category::class,
                'model_id' => $category->id,
                'old_data' => $validated,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'performed_at' => now(),
            ]);

            return back()->with('error', 'Failed to update category: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * DELETE SINGLE
     */
    public function destroy(Category $category)
    {
        DB::beginTransaction();
        try {
            $categoryData = [
                'code' => $category->code,
                'description' => $category->description,
                'is_active' => $category->is_active
            ];
            $categoryCode = $category->code;
            
            $category->delete();

            // ✅ AUDIT LOG: Category deletion
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'deleted',
                'module' => 'categories',
                'description' => "Deleted category: {$categoryCode}",
                'model_type' => Category::class,
                'model_id' => $category->id,
                'old_data' => $categoryData,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'performed_at' => now(),
            ]);

            DB::commit();

            return back()->with('success', 'Category deleted');

        } catch (\Exception $e) {
            DB::rollBack();

            // ✅ AUDIT LOG: Deletion failed
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'delete_failed',
                'module' => 'categories',
                'description' => "Failed to delete category: " . $e->getMessage(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'performed_at' => now(),
            ]);

            return back()->with('error', 'Failed to delete category: ' . $e->getMessage());
        }
    }

    /**
     * BULK DELETE
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'category_ids' => 'required|array'
        ]);

        DB::beginTransaction();
        try {
            $categories = Category::whereIn('id', $request->category_ids)->get();
            $categoryCount = $categories->count();
            $categoryCodes = $categories->pluck('code')->toArray();

            Category::whereIn('id', $request->category_ids)->delete();

            // ✅ AUDIT LOG: Bulk deletion
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'bulk_deleted',
                'module' => 'categories',
                'description' => "Bulk deleted {$categoryCount} categories",
                'old_data' => [
                    'category_ids' => $request->category_ids,
                    'category_codes' => $categoryCodes
                ],
                'new_data' => ['deleted_count' => $categoryCount],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'performed_at' => now(),
            ]);

            DB::commit();

            return back()->with('success', 'Selected categories deleted');

        } catch (\Exception $e) {
            DB::rollBack();

            // ✅ AUDIT LOG: Bulk deletion failed
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'bulk_delete_failed',
                'module' => 'categories',
                'description' => "Failed to bulk delete categories: " . $e->getMessage(),
                'old_data' => ['category_ids' => $request->category_ids],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'performed_at' => now(),
            ]);

            return back()->with('error', 'Failed to delete categories: ' . $e->getMessage());
        }
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

        DB::beginTransaction();
        try {
            $categories = Category::whereIn('id', $request->category_ids)->get();
            $categoryCount = $categories->count();
            $oldStatuses = $categories->pluck('is_active', 'code')->toArray();

            Category::whereIn('id', $request->category_ids)
                ->update(['is_active' => $request->status]);

            // ✅ AUDIT LOG: Bulk status update
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'bulk_status_update',
                'module' => 'categories',
                'description' => "Bulk updated status for {$categoryCount} categories to " . ($request->status ? 'active' : 'inactive'),
                'old_data' => [
                    'category_ids' => $request->category_ids,
                    'old_statuses' => $oldStatuses
                ],
                'new_data' => [
                    'new_status' => $request->status,
                    'updated_count' => $categoryCount
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'performed_at' => now(),
            ]);

            DB::commit();

            return back()->with('success', 'Status updated');

        } catch (\Exception $e) {
            DB::rollBack();

            // ✅ AUDIT LOG: Bulk status update failed
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'bulk_status_update_failed',
                'module' => 'categories',
                'description' => "Failed to bulk update category status: " . $e->getMessage(),
                'old_data' => [
                    'category_ids' => $request->category_ids,
                    'status' => $request->status
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'performed_at' => now(),
            ]);

            return back()->with('error', 'Failed to update status: ' . $e->getMessage());
        }
    }
}