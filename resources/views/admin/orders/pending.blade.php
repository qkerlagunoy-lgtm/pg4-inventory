@extends('layouts.admin')

@section('title', 'Pending Requests')

@section('page-title', 'Pending Requests')

@section('breadcrumb')
    <nav class="mb-4" aria-label="breadcrumb">
        <ol class="flex items-center space-x-2 text-sm">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-gray-700">Dashboard</a>
            </li>
            <li>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </li>
            <li>
                <a href="{{ route('admin.orders.index') }}" class="text-gray-500 hover:text-gray-700">Order Management</a>
            </li>
            <li>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </li>
            <li class="text-blue-600 font-medium" aria-current="page">Pending Requests</li>
        </ol>
    </nav>
@endsection

@section('content')
    <!-- Flash Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <!-- Header with Stats and Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Pending Requests</h2>
                <p class="text-gray-600 mt-1">Review and process new item requests</p>
            </div>
            
            <div class="flex items-center space-x-4">
                <!-- Search Form -->
                <form method="GET" action="{{ route('admin.orders.pending') }}" class="flex items-center">
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Search requests..." 
                               class="w-full md:w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <button type="submit" class="ml-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Search
                    </button>
                    @if(request()->has('search') || request()->has('priority'))
                        <a href="{{ route('admin.orders.pending') }}" class="ml-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                            Clear
                        </a>
                    @endif
                </form>

                <!-- Filter by Priority -->
                <form method="GET" action="{{ route('admin.orders.pending') }}" class="hidden md:block">
                    <select name="priority" onchange="this.form.submit()" 
                            class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Priorities</option>
                        <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low Priority</option>
                        <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium Priority</option>
                        <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High Priority</option>
                        <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                    </select>
                </form>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-sm text-blue-600 font-medium">Total Pending</p>
                <p class="text-2xl font-bold text-gray-800">{{ $requests->total() }}</p>
            </div>
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <p class="text-sm text-yellow-600 font-medium">High Priority</p>
                <p class="text-2xl font-bold text-gray-800">
                    {{ \App\Models\ItemRequest::where('status', 'pending')->where('priority', 'high')->count() }}
                </p>
            </div>
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <p class="text-sm text-red-600 font-medium">Urgent</p>
                <p class="text-2xl font-bold text-gray-800">
                    {{ \App\Models\ItemRequest::where('status', 'pending')->where('priority', 'urgent')->count() }}
                </p>
            </div>
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <p class="text-sm text-green-600 font-medium">Today's Requests</p>
                <p class="text-2xl font-bold text-gray-800">
                    {{ \App\Models\ItemRequest::where('status', 'pending')->whereDate('created_at', today())->count() }}
                </p>
            </div>
        </div>
    </div>

    <!-- Requests Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Request #
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
                            Priority
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Requested
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($requests as $request)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">#{{ $request->id }}</div>
                                <div class="text-xs text-gray-500">{{ $request->created_at->format('M d, Y h:i A') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-semibold">
                                            {{ strtoupper(substr($request->user->first_name, 0, 1) . substr($request->user->last_name, 0, 1)) }}
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $request->user->first_name }} {{ $request->user->last_name }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $request->user->unit ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ Str::limit($request->purpose, 50) }}</div>
                                @if($request->remarks)
                                    <div class="text-xs text-gray-500 mt-1">{{ Str::limit($request->remarks, 30) }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $request->requestItems->count() }} items</div>
                                <div class="text-xs text-gray-500">
                                    {{ $request->requestItems->sum('quantity') }} total quantity
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $priorityColors = [
                                        'low' => 'bg-gray-100 text-gray-800',
                                        'medium' => 'bg-yellow-100 text-yellow-800',
                                        'high' => 'bg-orange-100 text-orange-800',
                                        'urgent' => 'bg-red-100 text-red-800',
                                    ];
                                    $colorClass = $priorityColors[$request->priority] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $colorClass }}">
                                    {{ ucfirst($request->priority) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $request->created_at->diffForHumans() }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.orders.review', $request->id) }}" 
                                       class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Review
                                    </a>
                                    
                                    <!-- Quick Approve Button -->
                                    <form action="{{ route('admin.orders.approve', $request->id) }}" method="POST" 
                                          class="inline" onsubmit="return confirm('Approve this request?')">
                                        @csrf
                                        <button type="submit" 
                                                class="text-green-600 hover:text-green-900 bg-green-50 hover:bg-green-100 px-3 py-1.5 rounded-lg transition flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Approve
                                        </button>
                                    </form>

                                    <!-- Quick Reject Dropdown -->
                                    <div class="relative inline-block">
                                        <button type="button" 
                                                class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition flex items-center reject-btn"
                                                data-request-id="{{ $request->id }}">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            Reject
                                        </button>
                                        
                                        <!-- Reject Form Modal -->
                                        <div id="reject-modal-{{ $request->id }}" 
                                             class="hidden absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-xl border border-gray-200 z-10">
                                            <form action="{{ route('admin.orders.reject', $request->id) }}" method="POST" 
                                                  class="p-4">
                                                @csrf
                                                <h4 class="text-sm font-medium text-gray-800 mb-2">Rejection Reason</h4>
                                                <textarea name="rejection_reason" 
                                                          rows="3" 
                                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                                          placeholder="Enter reason for rejection..."
                                                          required></textarea>
                                                <div class="mt-3 flex justify-end space-x-2">
                                                    <button type="button" 
                                                            class="cancel-reject-btn px-3 py-1.5 text-sm text-gray-600 hover:text-gray-800">
                                                        Cancel
                                                    </button>
                                                    <button type="submit" 
                                                            class="px-3 py-1.5 text-sm bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                                                        Confirm Reject
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="text-gray-400">
                                    <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No pending requests</h3>
                                    <p class="mt-1 text-sm text-gray-500">All requests have been processed.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($requests->hasPages())
            <div class="bg-white px-6 py-4 border-t border-gray-200">
                {{ $requests->links() }}
            </div>
        @endif
    </div>

    <!-- Bulk Actions -->
    @if($requests->count() > 0)
        <div class="mt-6 bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-600">
                    {{ $requests->total() }} request(s) found
                </div>
                <div class="flex items-center space-x-3">
                    <button type="button" onclick="selectAllRequests()" 
                            class="text-sm text-blue-600 hover:text-blue-800">
                        Select All
                    </button>
                    <button type="button" onclick="deselectAllRequests()" 
                            class="text-sm text-gray-600 hover:text-gray-800">
                        Deselect All
                    </button>
                    <button type="button" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">
                        Bulk Approve
                    </button>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
<script>
    // Handle reject buttons
    document.addEventListener('DOMContentLoaded', function() {
        // Show reject modal
        document.querySelectorAll('.reject-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.stopPropagation();
                const requestId = this.getAttribute('data-request-id');
                const modal = document.getElementById(`reject-modal-${requestId}`);
                
                // Hide all other modals
                document.querySelectorAll('[id^="reject-modal-"]').forEach(m => {
                    if (m.id !== `reject-modal-${requestId}`) {
                        m.classList.add('hidden');
                    }
                });
                
                // Toggle current modal
                modal.classList.toggle('hidden');
            });
        });

        // Hide modal when clicking cancel
        document.querySelectorAll('.cancel-reject-btn').forEach(button => {
            button.addEventListener('click', function() {
                const modal = this.closest('[id^="reject-modal-"]');
                modal.classList.add('hidden');
            });
        });

        // Hide modal when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('[id^="reject-modal-"]') && !e.target.closest('.reject-btn')) {
                document.querySelectorAll('[id^="reject-modal-"]').forEach(modal => {
                    modal.classList.add('hidden');
                });
            }
        });
    });

    function selectAllRequests() {
        document.querySelectorAll('input[name="selected_requests[]"]').forEach(checkbox => {
            checkbox.checked = true;
        });
    }

    function deselectAllRequests() {
        document.querySelectorAll('input[name="selected_requests[]"]').forEach(checkbox => {
            checkbox.checked = false;
        });
    }

    // Auto-submit priority filter
    document.querySelector('select[name="priority"]')?.addEventListener('change', function() {
        if (this.value) {
            this.form.submit();
        }
    });
</script>
@endpush

@push('styles')
<style>
    [id^="reject-modal-"] {
        min-width: 280px;
    }
    
    .reject-btn:hover + [id^="reject-modal-"],
    [id^="reject-modal-"]:hover {
        display: block !important;
    }
</style>
@endpush