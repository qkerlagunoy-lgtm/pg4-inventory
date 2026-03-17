<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    /**
     * Display a listing of users with filtering and search
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Filter by unit
        if ($request->filled('unit')) {
            $query->where('unit', $request->unit);
        }

        // Filter by status (using email_verified_at for active/inactive)
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->whereNotNull('email_verified_at');
            } elseif ($request->status === 'inactive') {
                $query->whereNull('email_verified_at');
            }
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
       
        // Get distinct units for filter dropdown
        $units = User::select('unit')
            ->distinct()
            ->whereNotNull('unit')
            ->orderBy('unit')
            ->get();

        return view('admin.users.index', compact('users', 'units'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        // No need to pass units anymore since we're using hardcoded dropdown
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|email|max:255|unique:users,email',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'suffix' => 'nullable|string|max:50',
            'sex' => 'nullable|in:male,female',
            'password' => 'required|string|min:8|confirmed',
            'unit' => 'nullable|string|max:255',
            'role' => 'required|in:admin,user',
            'status' => 'required|in:active,inactive',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'username' => $validated['username'],
                'email' => $validated['email'],
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'middle_name' => $validated['middle_name'] ?? null,
                'suffix' => $validated['suffix'] ?? null,
                'sex' => $validated['sex'] ?? null,
                'password' => Hash::make($validated['password']),
                'unit' => $validated['unit'],
                'type' => $validated['role'],
                'email_verified_at' => $validated['status'] === 'active' ? now() : null,
                'is_active' => $validated['status'] === 'active',
            ]);

            // Audit log
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'created',
                'module' => 'users',
                'description' => "Created new user: {$user->username} ({$user->email})",
                'new_values' => [
                    'username' => $user->username,
                    'email' => $user->email,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'middle_name' => $user->middle_name,
                    'suffix' => $user->suffix,
                    'sex' => $user->sex,
                    'unit' => $user->unit,
                    'role' => $user->type,
                    'status' => $validated['status'],
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'model_type' => User::class,
                'model_id' => $user->id,
                'performed_at' => now(),
            ]);

            DB::commit();
           
            return redirect()->route('admin.users.index')
                ->with('success', 'User created successfully.');
               
        } catch (\Exception $e) {
            DB::rollBack();
            
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'create_failed',
                'module' => 'users',
                'description' => "Failed to create user: " . $e->getMessage(),
                'old_values' => $validated,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'performed_at' => now(),
            ]);
            
            return back()->with('error', 'Failed to create user: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
       
        // No need to pass units anymore since we're using hardcoded dropdown
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        // Store old values before update
        $oldValues = $user->toArray();
       
        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'suffix' => 'nullable|string|max:50',
            'sex' => 'nullable|in:male,female',
            'password' => 'nullable|string|min:8|confirmed',
            'unit' => 'nullable|string|max:255',
            'role' => 'required|in:admin,user',
            'status' => 'required|in:active,inactive',
        ]);

        DB::beginTransaction();
        try {
            $updateData = [
                'username' => $validated['username'],
                'email' => $validated['email'],
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'middle_name' => $validated['middle_name'] ?? null,
                'suffix' => $validated['suffix'] ?? null,
                'sex' => $validated['sex'] ?? null,
                'unit' => $validated['unit'],
                'type' => $validated['role'],
                'email_verified_at' => $validated['status'] === 'active'
                    ? ($user->email_verified_at ?? now())
                    : null,
                'is_active' => $validated['status'] === 'active',
            ];

            if (!empty($validated['password'])) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            $user->update($updateData);
            
            // Get the changes after update
            $changes = $user->getChanges();
            unset($changes['updated_at']);
            
            // Only log if there are actual changes
            if (!empty($changes)) {
                AuditLog::create([
                    'user_id' => auth()->id(),
                    'action' => 'updated',
                    'module' => 'users',
                    'description' => "Updated user: {$user->username}",
                    'old_values' => array_intersect_key($oldValues, $changes),
                    'new_values' => $changes,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'url' => $request->fullUrl(),
                    'method' => $request->method(),
                    'model_type' => User::class,
                    'model_id' => $user->id,
                    'performed_at' => now(),
                ]);
            }

            DB::commit();
           
            return redirect()->route('admin.users.index')
                ->with('success', 'User updated successfully.');
               
        } catch (\Exception $e) {
            DB::rollBack();
            
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'update_failed',
                'module' => 'users',
                'description' => "Failed to update user {$user->username}: " . $e->getMessage(),
                'old_values' => $validated,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'model_type' => User::class,
                'model_id' => $user->id,
                'performed_at' => now(),
            ]);
            
            return back()->with('error', 'Failed to update user: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified user from storage
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($id);
           
            if ($user->id === auth()->id()) {
                AuditLog::create([
                    'user_id' => auth()->id(),
                    'action' => 'delete_self_attempt',
                    'module' => 'users',
                    'description' => "Attempted to delete own account",
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'url' => request()->fullUrl(),
                    'method' => request()->method(),
                    'performed_at' => now(),
                ]);
                
                return back()->with('error', 'You cannot delete your own account.');
            }
            
            // Check if user has related records before deletion
            if ($user->itemRequests()->exists()) {
                return back()->with('error', 'Cannot delete user with existing requests. Please archive the user instead.');
            }
            
            // Store user data before deletion
            $userData = $user->toArray();
            
            $user->delete();
            
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'deleted',
                'module' => 'users',
                'description' => "Deleted user: {$userData['username']} ({$userData['email']})",
                'old_values' => $userData,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'model_type' => User::class,
                'model_id' => $id,
                'performed_at' => now(),
            ]);
           
            DB::commit();
           
            return redirect()->route('admin.users.index')
                ->with('success', 'User deleted successfully.');
               
        } catch (\Exception $e) {
            DB::rollBack();
            
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'delete_failed',
                'module' => 'users',
                'description' => "Failed to delete user: " . $e->getMessage(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'performed_at' => now(),
            ]);
            
            return back()->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }

    /**
     * Export users to CSV
     */
    public function exportCsv(Request $request)
    {
        $query = User::query();
       
        if ($request->filled('unit')) {
            $query->where('unit', $request->unit);
        }
       
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->whereNotNull('email_verified_at');
            } elseif ($request->status === 'inactive') {
                $query->whereNull('email_verified_at');
            }
        }
       
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
       
        $users = $query->orderBy('created_at', 'desc')->get();
        
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'exported',
            'module' => 'users',
            'description' => "Exported " . $users->count() . " users to CSV",
            'old_values' => [
                'filters' => $request->only(['unit', 'status', 'search']),
                'count' => $users->count()
            ],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'performed_at' => now(),
        ]);
       
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="users_' . date('Y-m-d') . '.csv"',
        ];
       
        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
           
            fputcsv($file, ['Username', 'Email', 'First Name', 'Last Name', 'Middle Name', 'Suffix', 'Gender', 'Unit', 'Role', 'Status', 'Created At']);
           
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->username,
                    $user->email,
                    $user->first_name,
                    $user->last_name,
                    $user->middle_name ?? '',
                    $user->suffix ?? '',
                    ucfirst($user->sex ?? 'Not specified'),
                    $user->unit ?? 'N/A',
                    ucfirst($user->type),
                    $user->email_verified_at ? 'Active' : 'Inactive',
                    $user->created_at->format('Y-m-d H:i:s'),
                ]);
            }
           
            fclose($file);
        };
       
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Activate a user account
     */
    public function activate($id)
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($id);

            // Don't activate if already active
            if ($user->email_verified_at !== null) {
                return back()->with('info', 'User account is already active.');
            }

            $user->update([
                'email_verified_at' => now(),
                'is_active' => true,
            ]);

            AuditLog::create([
                'user_id'      => auth()->id(),
                'action'       => 'activated',
                'module'       => 'users',
                'description'  => "Activated user: {$user->username}",
                'new_values'   => ['email_verified_at' => now()],
                'ip_address'   => request()->ip(),
                'user_agent'   => request()->userAgent(),
                'url'          => request()->fullUrl(),
                'method'       => request()->method(),
                'model_type'   => User::class,
                'model_id'     => $user->id,
                'performed_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('admin.users.index')
                ->with('success', "{$user->first_name} {$user->last_name}'s account has been activated.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to activate user: ' . $e->getMessage());
        }
    }

    /**
     * Deactivate a user account (optional method)
     */
    public function deactivate($id)
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($id);

            // Don't allow deactivating own account
            if ($user->id === auth()->id()) {
                return back()->with('error', 'You cannot deactivate your own account.');
            }

            // Don't deactivate if already inactive
            if ($user->email_verified_at === null) {
                return back()->with('info', 'User account is already inactive.');
            }

            $user->update([
                'email_verified_at' => null,
                'is_active' => false,
            ]);

            AuditLog::create([
                'user_id'      => auth()->id(),
                'action'       => 'deactivated',
                'module'       => 'users',
                'description'  => "Deactivated user: {$user->username}",
                'new_values'   => ['email_verified_at' => null],
                'ip_address'   => request()->ip(),
                'user_agent'   => request()->userAgent(),
                'url'          => request()->fullUrl(),
                'method'       => request()->method(),
                'model_type'   => User::class,
                'model_id'     => $user->id,
                'performed_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('admin.users.index')
                ->with('success', "{$user->first_name} {$user->last_name}'s account has been deactivated.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to deactivate user: ' . $e->getMessage());
        }
    }
}