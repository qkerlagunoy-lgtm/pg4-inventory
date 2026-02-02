<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Show dashboard based on user type
     */
    public function Dashboard()
    {
        if (Auth::check() && Auth::user()->type == "user") {
            return view('dashboard');
        } elseif (Auth::check() && Auth::user()->type == "admin") {
            return view('admin.dashboard');
        } else {
            return redirect()->route('login');
        }
    }

    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Filter by unit
        if ($request->filled('unit')) {
            $query->where('unit', $request->unit);
        }

        // Search by username, email, or name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('username', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%");
            });
        }

        $query->orderBy('created_at', 'desc');

        $users = $query->paginate(15)->withQueryString();

        // Get unique units for filter
        $units = User::whereNotNull('unit')
                     ->distinct()
                     ->pluck('unit')
                     ->sort();

        return view('admin.users', compact('users', 'units'));
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'username' => ['nullable', 'string', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'type' => ['required', 'in:user,admin,superadmin'],
            'unit' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'type' => $request->type,
            'unit' => $request->unit,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('admin.users')->with('success', 'User created successfully.');
    }

    /**
     * Show the specified user
     */
    public function show($id)
    {
        $user = User::with(['orders'])->findOrFail($id);
        return view('admin.users-show', compact('user'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $id],
            'username' => ['nullable', 'string', 'max:255', 'unique:users,username,' . $id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'type' => ['required', 'in:user,admin,superadmin'],
            'unit' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'username' => $request->username,
            'type' => $request->type,
            'unit' => $request->unit,
            'is_active' => $request->has('is_active') ? true : false,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users')->with('success', 'User updated successfully.');
    }

    /**
     * Toggle user active status
     */
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_active' => !$user->is_active]);

        return redirect()->route('admin.users')->with('success', 'User status updated successfully.');
    }

    /**
     * Remove the specified user
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        if ($user->type === 'superadmin' && auth()->user()->type !== 'superadmin') {
            return redirect()->back()->with('error', 'You cannot delete a superadmin account.');
        }

        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User deleted successfully.');
    }

    /**
     * Bulk update users
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'action' => 'required|in:activate,deactivate,delete',
        ]);

        $users = User::whereIn('id', $request->user_ids)->get();

        foreach ($users as $user) {
            if ($user->id === auth()->id()) continue;

            switch ($request->action) {
                case 'activate':
                    $user->update(['is_active' => true]);
                    break;
                case 'deactivate':
                    $user->update(['is_active' => false]);
                    break;
                case 'delete':
                    if ($user->type !== 'superadmin' || auth()->user()->type === 'superadmin') {
                        $user->delete();
                    }
                    break;
            }
        }

        return redirect()->route('admin.users')->with('success', 'Users updated successfully.');
    }
}
