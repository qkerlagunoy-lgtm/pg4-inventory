@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('page-title', 'Admin Dashboard')

@section('content')
    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Pending Requests -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Pending Requests</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['pending_requests'] }}</p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <span class="text-2xl">⏳</span>
                </div>
            </div>
        </div>

        <!-- Urgent Requests -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Urgent Requests</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['urgent_requests'] }}</p>
                </div>
                <div class="bg-red-100 p-3 rounded-full">
                    <span class="text-2xl">🚨</span>
                </div>
            </div>
        </div>

        <!-- Approved Requests -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Approved Requests</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['approved_requests'] }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <span class="text-2xl">👍</span>
                </div>
            </div>
        </div>

        <!-- Rejected Requests -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Rejected Requests</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['rejected_requests'] }}</p>
                </div>
                <div class="bg-red-100 p-3 rounded-full">
                    <span class="text-2xl">👎</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Two Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
       
        <!-- Inventory Status -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Inventory Status</h3>
           
            <div class="space-y-4">
                <!-- Low Stock Items -->
                <a href="{{ route('admin.inventory') }}?filter=low_stock" class="block">
                    <div class="flex items-center gap-3 p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition">
                        <div class="bg-yellow-500 text-white rounded-full w-12 h-12 flex items-center justify-center text-xl font-bold">
                            ⚠️
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-800">Low Stock Items</p>
                            <p class="text-sm text-gray-600">Items below threshold</p>
                        </div>
                        <span class="text-2xl font-bold text-gray-800">{{ $stats['low_stock_items'] }}</span>
                    </div>
                </a>

                <!-- Expiring Soon -->
                <a href="{{ route('admin.inventory') }}?filter=expiring" class="block">
                    <div class="flex items-center gap-3 p-4 bg-red-50 rounded-lg hover:bg-red-100 transition">
                        <div class="bg-red-500 text-white rounded-full w-12 h-12 flex items-center justify-center text-xl font-bold">
                            🔴
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-800">Expiring Soon</p>
                            <p class="text-sm text-gray-600">Items expiring in 30 days</p>
                        </div>
                        <span class="text-2xl font-bold text-gray-800">{{ $stats['expiring_soon'] }}</span>
                    </div>
                </a>
            </div>
        </div>

        <!-- Most Requested Items -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Most Requested Items</h3>
           
            <div class="space-y-3">
                @if(isset($mostRequestedItems) && $mostRequestedItems->count() > 0)
                    @foreach($mostRequestedItems as $item)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                            <div>
                                <span class="font-medium text-gray-800">{{ $item['name'] }}</span>
                                <p class="text-xs text-gray-500">{{ $item['count'] }} requests ({{ $item['quantity'] }} items)</p>
                            </div>
                            <div class="w-32 bg-gray-200 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: {{ min($item['percentage'], 100) }}%"></div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-center text-gray-400 italic py-4">No requested items yet</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Critical Requests Table -->
    <div class="bg-white rounded-lg shadow-md p-6 mt-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Recent Critical Requests</h3>
            <a href="{{ route('admin.orders.pending') }}" class="text-sm text-blue-600 hover:text-blue-800">
                View All →
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b">
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Requester</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Unit</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Purpose</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Created</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentRequests as $request)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-4 text-sm text-gray-800">{{ $request->user->first_name }} {{ $request->user->last_name }}</td>
                            <td class="py-3 px-4 text-sm text-gray-600">{{ $request->user->unit ?? '-' }}</td>
                            <td class="py-3 px-4 text-sm text-gray-800">{{ Str::limit($request->purpose, 40) }}</td>
                            <td class="py-3 px-4 text-sm text-gray-600">{{ $request->created_at->format('M d, Y') }}</td>
                            <td class="py-3 px-4">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    @if($request->status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($request->priority == 'urgent') bg-red-100 text-red-800
                                    @elseif($request->status == 'approved') bg-green-100 text-green-800
                                    @elseif($request->status == 'rejected') bg-red-100 text-red-800
                                    @elseif($request->status == 'cancelled') bg-gray-100 text-gray-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    @if($request->priority == 'urgent')
                                        Urgent
                                    @else
                                        {{ ucfirst($request->status) }}
                                    @endif
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-8 text-gray-400 italic">
                                No critical requests at this time
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection