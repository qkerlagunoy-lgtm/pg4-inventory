{{-- resources/views/admin/orders/rejected.blade.php --}}
@extends('layouts.admin')

@section('title', 'Rejected Requests')

@section('page-title', 'Rejected Requests')

@section('content')
<!-- Flash Messages -->
@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        {{ session('error') }}
    </div>
@endif

<!-- Page Header -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Rejected Requests</h1>
            <p class="text-gray-600 mt-1">View all rejected item requests</p>
        </div>
        <div class="mt-4 md:mt-0">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                {{ $requests->total() }} Rejected Request{{ $requests->total() !== 1 ? 's' : '' }}
            </span>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <form method="GET" action="{{ route('admin.orders.rejected') }}" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Search -->
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" 
                       name="search" 
                       id="search"
                       value="{{ request('search') }}"
                       placeholder="Search by purpose..." 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <!-- Date Range -->
            <div>
                <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">Date From</label>
                <input type="date" 
                       name="date_from" 
                       id="date_from"
                       value="{{ request('date_from') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div>
                <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">Date To</label>
                <input type="date" 
                       name="date_to" 
                       id="date_to"
                       value="{{ request('date_to') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>
        
        <div class="flex justify-end space-x-3">
            <a href="{{ route('admin.orders.rejected') }}" 
               class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                Clear Filters
            </a>
            <button type="submit" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                Apply Filters
            </button>
        </div>
    </form>
</div>

<!-- Requests Table -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    @if($requests->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Request ID
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Requester
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Purpose
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Items
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Rejected By
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Rejection Reason
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Date Rejected
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($requests as $request)
                        <tr class="hover:bg-gray-50">
                            <!-- Request ID -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    #{{ str_pad($request->id, 6, '0', STR_PAD_LEFT) }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $request->created_at->format('M d, Y') }}
                                </div>
                            </td>
                            
                            <!-- Requester -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <span class="text-blue-600 font-semibold">
                                            {{ Str::upper(substr($request->user->first_name, 0, 1) . substr($request->user->last_name, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $request->user->first_name }} {{ $request->user->last_name }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $request->user->unit ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Purpose -->
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ Str::limit($request->purpose, 50) }}</div>
                                @if($request->priority == 'urgent')
                                    <span class="inline-flex items-center px-2 py-1 mt-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                        Urgent
                                    </span>
                                @endif
                            </td>
                            
                            <!-- Items -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $request->requestItems->count() }} item(s)
                                </div>
                                <div class="text-sm text-gray-500">
                                    Qty: {{ $request->requestItems->sum('quantity') }}
                                </div>
                            </td>
                            
                            <!-- Rejected By -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($request->rejectedBy)
                                    <div class="text-sm text-gray-900">
                                        {{ $request->rejectedBy->first_name }} {{ $request->rejectedBy->last_name }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        Admin
                                    </div>
                                @else
                                    <span class="text-sm text-gray-400">System</span>
                                @endif
                            </td>
                            
                            <!-- Rejection Reason -->
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 max-w-xs">
                                    {{ Str::limit($request->rejection_reason, 60) }}
                                </div>
                                @if(strlen($request->rejection_reason) > 60)
                                    <button type="button" 
                                            onclick="showReasonModal('{{ $request->rejection_reason }}')"
                                            class="mt-1 text-sm text-blue-600 hover:text-blue-800">
                                        View Full
                                    </button>
                                @endif
                            </td>
                            
                            <!-- Date Rejected -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $request->rejected_at->format('M d, Y') }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $request->rejected_at->format('h:i A') }}
                                </div>
                                <div class="text-xs text-gray-400">
                                    {{ $request->rejected_at->diffForHumans() }}
                                </div>
                            </td>
                            
                            <!-- Actions -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <!-- View Details -->
                                    <a href="{{ route('admin.orders.review', $request->id) }}" 
                                       class="text-blue-600 hover:text-blue-900"
                                       title="View Details">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    
                                    <!-- Export -->
                                    <button type="button" 
                                            onclick="exportRequest({{ $request->id }})"
                                            class="text-green-600 hover:text-green-900"
                                            title="Export">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $requests->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No rejected requests</h3>
            <p class="mt-1 text-sm text-gray-500">
                All requests are either pending or approved.
            </p>
            <div class="mt-6">
                <a href="{{ route('admin.orders.pending') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    View Pending Requests
                </a>
            </div>
        </div>
    @endif
</div>

<!-- Stats Summary -->
@if($requests->count() > 0)
<div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <svg class="h-8 w-8 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-red-800">Total Rejected</p>
                <p class="text-2xl font-bold text-red-900">{{ $requests->total() }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <svg class="h-8 w-8 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-yellow-800">This Month</p>
                <p class="text-2xl font-bold text-yellow-900">
                    {{ \App\Models\ItemRequest::where('status', 'rejected')
                        ->whereMonth('rejected_at', now()->month)
                        ->whereYear('rejected_at', now()->year)
                        ->count() }}
                </p>
            </div>
        </div>
    </div>
    
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <svg class="h-8 w-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-blue-800">Top Rejector</p>
                <p class="text-lg font-bold text-blue-900">
                    @php
                        $topRejector = \App\Models\User::withCount(['rejectedRequests'])
                            ->where('type', 'admin')
                            ->orderBy('rejected_requests_count', 'desc')
                            ->first();
                    @endphp
                    {{ $topRejector ? $topRejector->first_name . ' ' . $topRejector->last_name : 'N/A' }}
                </p>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Rejection Reason Modal -->
<div id="reasonModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Rejection Reason</h3>
            <div class="mt-2 px-4 py-3 bg-gray-50 rounded-md">
                <p id="reasonText" class="text-sm text-gray-700"></p>
            </div>
            <div class="items-center px-4 py-3">
                <button onclick="closeReasonModal()"
                        class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 w-full">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
    function showReasonModal(reason) {
        document.getElementById('reasonText').textContent = reason;
        document.getElementById('reasonModal').classList.remove('hidden');
    }

    function closeReasonModal() {
        document.getElementById('reasonModal').classList.add('hidden');
    }

    function exportRequest(requestId) {
        // You can implement export functionality here
        window.location.href = `/admin/orders/export?type=rejected&request_id=${requestId}`;
    }

    // Close modal when clicking outside
    document.getElementById('reasonModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeReasonModal();
        }
    });
</script>

<!-- Add date filtering logic to controller -->
@push('scripts')
<script>
    // Close modal with ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeReasonModal();
        }
    });
</script>
@endpush
@endsection