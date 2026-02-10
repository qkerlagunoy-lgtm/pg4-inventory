<?php

namespace App\Http\Controllers;

use App\Models\User;
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

            DB::commit();
           
            return redirect()->route('admin.users.index')
                ->with('success', 'User created successfully.');
               
        } catch (\Exception $e) {
            DB::rollBack();
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

            DB::commit();
           
            return redirect()->route('admin.users.index')
                ->with('success', 'User updated successfully.');
               
        } catch (\Exception $e) {
            DB::rollBack();
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
                return back()->with('error', 'You cannot delete your own account.');
            }
           
            $user->delete();
           
            DB::commit();
           
            return redirect()->route('admin.users.index')
                ->with('success', 'User deleted successfully.');
               
        } catch (\Exception $e) {
            DB::rollBack();
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