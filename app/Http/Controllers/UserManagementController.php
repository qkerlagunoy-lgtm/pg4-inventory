<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
       
        // Get distinct units for filter
        $units = User::select('unit')->distinct()->whereNotNull('unit')->get();

        // ✅ OPTIONAL: Log view action (if you want to track who views the list)
        // AuditLog::log('viewed_list', 'users', null, [
        //     'filters' => $request->only(['unit', 'status', 'search']),
        //     'result_count' => $users->total()
        // ]);

        return view('admin.users.index', compact('users', 'units'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        // Get distinct units for the datalist
        $units = User::select('unit')
            ->distinct()
            ->whereNotNull('unit')
            ->pluck('unit');
       
        return view('admin.users.create', compact('units'));
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
                'password' => bcrypt($validated['password']),
                'unit' => $validated['unit'],
                'type' => $validated['role'],
                'email_verified_at' => $validated['status'] === 'active' ? now() : null,
            ]);

            // ✅ AUDIT LOG: User creation
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
            
            // ✅ AUDIT LOG: Failed creation
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
       
        $units = User::select('unit')
            ->distinct()
            ->whereNotNull('unit')
            ->pluck('unit');
       
        return view('admin.users.edit', compact('user', 'units'));
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
                'unit' => $validated['unit'],
                'type' => $validated['role'],
                'email_verified_at' => $validated['status'] === 'active'
                    ? ($user->email_verified_at ?? now())
                    : null,
            ];

            if (!empty($validated['password'])) {
                $updateData['password'] = bcrypt($validated['password']);
            }

            $user->update($updateData);
            
            // ✅ AUDIT LOG: User update
            $changes = $user->getChanges();
            unset($changes['updated_at']); // Remove timestamp from changes log
            
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'updated',
                'module' => 'users',
                'description' => "Updated user: {$user->username}",
                'old_values' => array_intersect_key($oldValues, $changes), // Only changed fields
                'new_values' => $changes,
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
                ->with('success', 'User updated successfully.');
               
        } catch (\Exception $e) {
            DB::rollBack();
            
            // ✅ AUDIT LOG: Failed update
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
                // ✅ AUDIT LOG: Self-deletion attempt
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
            
            // Store user data before deletion
            $userData = $user->toArray();
            
            $user->delete();
            
            // ✅ AUDIT LOG: User deletion
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
            
            // ✅ AUDIT LOG: Failed deletion
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
        
        // ✅ AUDIT LOG: Export action
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'exported',
            'module' => 'users',
            'description' => "Exported users to CSV",
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
           
            fputcsv($file, ['Username', 'Email', 'First Name', 'Last Name', 'Unit', 'Role', 'Status', 'Created At']);
           
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->username,
                    $user->email,
                    $user->first_name,
                    $user->last_name,
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
}