<?php

namespace App\Http\Controllers;

use App\Models\IpAddressRange;
use App\Models\DeviceRegistry;
use App\Models\DeviceOffense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IpAddressRangeController extends Controller
{
    public function index(Request $request)
    {
        $query = IpAddressRange::orderBy('name');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('range_start', 'like', "%{$search}%")
                  ->orWhere('range_end', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $ranges = $query->paginate(20);
        return view('admin.addresses.index', compact('ranges'));
    }

    public function create()
    {
        return view('admin.addresses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:ip_address_ranges,name',
            'range_start' => 'required|ip',
            'range_end'   => 'required|ip',
            'subnet_mask' => 'nullable|string|max:20',
            'gateway'     => 'nullable|ip',
            'description' => 'nullable|string|max:1000',
        ]);

        IpAddressRange::create($validated);

        return redirect()->route('admin.addresses.index')
            ->with('success', 'IP address range added successfully.');
    }

    public function show(IpAddressRange $address)
    {
        $ipRange = $address;

        view()->share('customBreadcrumbs', [
            ['title' => 'Dashboard',          'url' => route('admin.dashboard')],
            ['title' => 'Address Management', 'url' => route('admin.addresses.index')],
            ['title' => $ipRange->name,       'url' => '#'],
        ]);

        $devices = DeviceRegistry::where('ip_address_range_id', $ipRange->id)
                    ->orderByDesc('created_at')
                    ->paginate(20);
        return view('admin.addresses.show', compact('ipRange', 'devices'));
    }

    public function edit(IpAddressRange $address)
    {
        $ipRange = $address;
        return view('admin.addresses.edit', compact('ipRange'));
    }

    public function update(Request $request, IpAddressRange $address)
    {
        $ipRange   = $address;
        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:ip_address_ranges,name,' . $ipRange->id,
            'range_start' => 'required|ip',
            'range_end'   => 'required|ip',
            'subnet_mask' => 'nullable|string|max:20',
            'gateway'     => 'nullable|ip',
            'description' => 'nullable|string|max:1000',
            'is_active'   => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $ipRange->update($validated);

        return redirect()->route('admin.addresses.index')
            ->with('success', 'IP address range updated successfully.');
    }

    public function destroy(IpAddressRange $address)
    {
        $address->delete();
        return redirect()->route('admin.addresses.index')
            ->with('success', 'IP address range deleted.');
    }

    public function deviceProfile(DeviceRegistry $device)
    {
        $device->load(['offenses.filedBy', 'registeredBy']);

        // Load the IP range this device belongs to
        $ipRange = $device->ip_address_range_id
            ? IpAddressRange::find($device->ip_address_range_id)
            : null;

        view()->share('customBreadcrumbs', [
            ['title' => 'Dashboard',          'url' => route('admin.dashboard')],
            ['title' => 'Address Management', 'url' => route('admin.addresses.index')],
            ['title' => $ipRange?->name ?? 'Range', 'url' => $ipRange ? route('admin.addresses.show', $ipRange) : route('admin.addresses.index')],
            ['title' => ($device->assigned_lastname ?? '') . ', ' . ($device->assigned_firstname ?? 'Device'), 'url' => '#'],
        ]);

        return view('admin.addresses.device-profile', compact('device'));
    }

    public function registerDevice(Request $request)
    {
        $validated = $request->validate([
            'ip_address_range_id'  => 'nullable|exists:ip_address_ranges,id',
            'assigned_firstname'   => 'nullable|string|max:255',
            'assigned_middlename'  => 'nullable|string|max:255',
            'assigned_lastname'    => 'nullable|string|max:255',
            'assigned_rank'        => 'nullable|string|max:100',
            'assigned_unit'        => 'nullable|string|max:255',
            'assigned_category'    => 'nullable|string|max:255',
            'assigned_designation' => 'nullable|string|max:255',
            'profile_picture'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'ip_address'           => 'nullable|string|max:50',
            'mac_address'          => 'nullable|string|max:17',
            'device_name'          => 'nullable|string|max:255',
            'device_type'          => 'nullable|string|max:100',
            'serial_number'        => 'nullable|string|max:255',
            'image'                => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'remarks'              => 'nullable|string|max:1000',
            'date_registered'      => 'nullable|date',
        ]);

        if ($request->hasFile('profile_picture')) {
            $validated['profile_picture'] = $request->file('profile_picture')->store('profiles', 'public');
        }
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('devices', 'public');
        }

        $validated['registered_by']   = auth()->id();
        $validated['date_registered'] = $validated['date_registered'] ?? now()->toDateString();

        DeviceRegistry::create($validated);

        return redirect()->back()->with('success', 'Device registered successfully.');
    }

    public function updateDevice(Request $request, DeviceRegistry $device)
    {
        $validated = $request->validate([
            'assigned_firstname'   => 'nullable|string|max:255',
            'assigned_middlename'  => 'nullable|string|max:255',
            'assigned_lastname'    => 'nullable|string|max:255',
            'assigned_rank'        => 'nullable|string|max:100',
            'assigned_unit'        => 'nullable|string|max:255',
            'assigned_category'    => 'nullable|string|max:255',
            'assigned_designation' => 'nullable|string|max:255',
            'profile_picture'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'ip_address'           => 'nullable|string|max:50',
            'mac_address'          => 'nullable|string|max:17',
            'device_name'          => 'nullable|string|max:255',
            'device_type'          => 'nullable|string|max:100',
            'serial_number'        => 'nullable|string|max:255',
            'image'                => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'remarks'              => 'nullable|string|max:1000',
            'date_registered'      => 'nullable|date',
        ]);

        if ($request->hasFile('profile_picture')) {
            if ($device->profile_picture) Storage::disk('public')->delete($device->profile_picture);
            $validated['profile_picture'] = $request->file('profile_picture')->store('profiles', 'public');
        }
        if ($request->hasFile('image')) {
            if ($device->image) Storage::disk('public')->delete($device->image);
            $validated['image'] = $request->file('image')->store('devices', 'public');
        }

        $device->update($validated);
        return redirect()->back()->with('success', 'Device updated successfully.');
    }

    public function deleteDevice(DeviceRegistry $device)
    {
        if ($device->image)           Storage::disk('public')->delete($device->image);
        if ($device->profile_picture) Storage::disk('public')->delete($device->profile_picture);
        $device->delete();
        return redirect()->back()->with('success', 'Device deleted.');
    }

    public function addOffense(Request $request, DeviceRegistry $device)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string|max:2000',
            'offense_date' => 'nullable|date',
            'status'       => 'in:pending,resolved,dismissed',
        ]);

        $validated['device_registry_id'] = $device->id;
        $validated['filed_by']           = auth()->id();
        $validated['status']             = $validated['status'] ?? 'pending';

        DeviceOffense::create($validated);
        return redirect()->back()->with('success', 'Offense record added.');
    }

    public function deleteOffense(DeviceOffense $offense)
    {
        $offense->delete();
        return redirect()->back()->with('success', 'Offense deleted.');
    }

    public function updateOffense(Request $request, DeviceOffense $offense)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string|max:2000',
            'offense_date' => 'nullable|date',
            'status'       => 'in:pending,resolved,dismissed',
        ]);
        $offense->update($validated);
        return redirect()->back()->with('success', 'Offense updated.');
    }

    public function exportCsv(IpAddressRange $address)
{
    $ipRange = $address;
    $devices = DeviceRegistry::where('ip_address_range_id', $ipRange->id)
                ->orderByDesc('created_at')
                ->get();

    $filename = 'devices_' . str_replace(' ', '_', $ipRange->name) . '_' . date('Y-m-d') . '.csv';

    $headers = [
        'Content-Type'        => 'text/csv',
        'Content-Disposition' => 'attachment; filename="' . $filename . '"',
    ];

    $callback = function () use ($devices, $ipRange) {
        $file = fopen('php://output', 'w');

        // Header row
        fputcsv($file, [
            'IP Range',
            'First Name', 'Middle Name', 'Last Name',
            'Rank', 'Unit', 'Personnel Category', 'Designation',
            'Device Name', 'Device Type', 'Serial Number',
            'IP Address', 'MAC Address', 'Remarks',
        ]);

        foreach ($devices as $device) {
            fputcsv($file, [
                $ipRange->name,
                $device->assigned_firstname ?? '',
                $device->assigned_middlename ?? '',
                $device->assigned_lastname ?? '',
                $device->assigned_rank ?? '',
                $device->assigned_unit ?? '',
                $device->assigned_category ?? '',
                $device->assigned_designation ?? '',
                $device->device_name ?? '',
                $device->device_type ?? '',
                $device->serial_number ?? '',
                $device->ip_address ?? '',
                $device->mac_address ?? '',
                $device->remarks ?? '',
            ]);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}

    }