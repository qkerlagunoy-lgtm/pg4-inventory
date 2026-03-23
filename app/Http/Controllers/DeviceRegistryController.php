<?php

namespace App\Http\Controllers;

use App\Models\DeviceRegistry;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DeviceRegistryController extends Controller
{
    public function index(Request $request)
    
    {
         dd($request->all());
    $query = DeviceRegistry::with(['user.unit', 'user.personnelCategory', 'user.designation', 'registeredBy']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ip_address', 'like', "%{$search}%")
                  ->orWhere('mac_address', 'like', "%{$search}%")
                  ->orWhere('device_name', 'like', "%{$search}%")
                  ->orWhere('device_type', 'like', "%{$search}%")
                  ->orWhere('serial_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($u) use ($search) {
                      $u->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('device_type')) {
            $query->where('device_type', $request->device_type);
        }

        $devices     = $query->orderByDesc('created_at')->paginate(20);
        $deviceTypes = DeviceRegistry::select('device_type')->distinct()->pluck('device_type')->filter();

        return view('admin.device-registry.index', compact('devices', 'deviceTypes'));
    }

    public function create()
    {
        $users       = User::where('is_active', true)->orderBy('last_name')->get();
        $deviceTypes = ['Desktop', 'Laptop', 'Printer', 'Router', 'Switch', 'Server', 'Mobile', 'Other'];
        return view('admin.device-registry.create', compact('users', 'deviceTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'         => 'nullable|exists:users,id',
            'ip_address'      => 'nullable|string|max:50',
            'mac_address'     => 'nullable|string|max:17',
            'device_name'     => 'nullable|string|max:255',
            'device_type'     => 'nullable|string|max:100',
            'serial_number'   => 'nullable|string|max:255',
            'image'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'remarks'         => 'nullable|string|max:1000',
            'date_registered' => 'nullable|date',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('devices', 'public');
        }

        $validated['registered_by']   = auth()->id();
        $validated['date_registered'] = $validated['date_registered'] ?? now()->toDateString();

        DeviceRegistry::create($validated);

        // Always redirect back to where the form was submitted from
        return redirect()->back()->with('success', 'Device registered successfully.');
    }

    public function show(DeviceRegistry $deviceRegistry)
    {
        $deviceRegistry->load([
            'user.unit',
            'user.personnelCategory',
            'user.designation',
            'user.offenses',
            'registeredBy',
        ]);
        return view('admin.device-registry.show', compact('deviceRegistry'));
    }

    public function edit(DeviceRegistry $deviceRegistry)
    {
        $users       = User::where('is_active', true)->orderBy('last_name')->get();
        $deviceTypes = ['Desktop', 'Laptop', 'Printer', 'Router', 'Switch', 'Server', 'Mobile', 'Other'];
        return view('admin.device-registry.edit', compact('deviceRegistry', 'users', 'deviceTypes'));
    }

    public function update(Request $request, DeviceRegistry $deviceRegistry)
    {
        $validated = $request->validate([
            'user_id'         => 'nullable|exists:users,id',
            'ip_address'      => 'nullable|string|max:50',
            'mac_address'     => 'nullable|string|max:17',
            'device_name'     => 'nullable|string|max:255',
            'device_type'     => 'nullable|string|max:100',
            'serial_number'   => 'nullable|string|max:255',
            'image'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'remarks'         => 'nullable|string|max:1000',
            'date_registered' => 'nullable|date',
        ]);

        if ($request->hasFile('image')) {
            if ($deviceRegistry->image) {
                Storage::disk('public')->delete($deviceRegistry->image);
            }
            $validated['image'] = $request->file('image')->store('devices', 'public');
        }

        if ($request->input('remove_image') === '1' && $deviceRegistry->image) {
            Storage::disk('public')->delete($deviceRegistry->image);
            $validated['image'] = null;
        }

        $deviceRegistry->update($validated);

        return redirect()->back()->with('success', 'Device updated successfully.');
    }

    public function destroy(DeviceRegistry $deviceRegistry)
    {
        if ($deviceRegistry->image) {
            Storage::disk('public')->delete($deviceRegistry->image);
        }
        $deviceRegistry->delete();
        return redirect()->back()->with('success', 'Device removed from registry.');
    }

    public function userProfile(User $user)
    {
        $user->load(['unit', 'personnelCategory', 'designation', 'devices', 'offenses.filedBy']);
        return view('admin.device-registry.user-profile', compact('user'));
    }
}