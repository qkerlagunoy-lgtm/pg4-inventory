@extends('layouts.admin')

@section('title', 'Approved Requests')

@section('page-title', 'Approved Requests')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header with Stats -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Approved Requests</h1>
                <p class="text-gray-600 mt-2">Manage all approved item requests</p>
            </div>
            <div class="mt-4 md:mt-0">
                <div class="flex items-center space-x-4">
                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                        {{ $requests->total() }} Approved
                    </span>
                    <a href="{{ route('admin.orders.index') }}" 
                       class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg text-sm font-medium transition">
                        Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Bar -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
        <form action="{{ route('admin.orders.approved') }}" method="GET" class="space-y-4 md:space-y-0 md:flex md:items-center md:space-x-4">
            <div class="flex-1">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Search by purpose, user name, or email..."
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg bg-white text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
            <div>
                <button type="submit" 
                        class="w-full md:w-auto px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                    Search
                </button>
            </div>
            @if(request()->has('search'))
            <div>
                <a href="{{ route('admin.orders.approved') }}" 
                   class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium rounded-lg transition">
                    Clear Filters
                </a>
            </div>
            @endif
        </form>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            <div class="flex items-center">
                <svg class="h-5 w-5 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
            <div class="flex items-center">
                <svg class="h-5 w-5 mr-2 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                {{ session('error') }}
            </div>
        </div>
    @endif

    <!-- Approved Requests Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        @if($requests->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Request Details
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                User & Department
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Items
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($requests as $request)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-green-100 rounded-lg flex items-center justify-center">
                                            <svg class="h-6 w-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                Request #{{ $request->id }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ Str::limit($request->purpose, 40) }}
                                            </div>
                                            <div class="text-xs text-gray-400 mt-1">
                                                Approved: {{ $request->approved_at->format('M d, Y H:i') }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $request->user->full_name ?? $request->user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $request->user->unit ?? 'N/A' }}</div>
                                    <div class="text-xs text-gray-400">{{ $request->user->email }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        {{ $request->requestItems->count() }} item(s)
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        @foreach($request->requestItems->take(2) as $item)
                                            <span class="inline-block bg-gray-100 rounded px-2 py-1 mr-1 mb-1">
                                                {{ $item->item->name }} (x{{ $item->quantity }})
                                            </span>
                                        @endforeach
                                        @if($request->requestItems->count() > 2)
                                            <span class="text-blue-600">+{{ $request->requestItems->count() - 2 }} more</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col space-y-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Approved
                                        </span>
                                        @if($request->issuance)
                                            @php
                                                $issuanceStatusColor = [
                                                    'pending' => 'yellow',
                                                    'partially_issued' => 'blue', 
                                                    'completed' => 'green'
                                                ][$request->issuance->status] ?? 'gray';
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $issuanceStatusColor }}-100 text-{{ $issuanceStatusColor }}-800">
                                                {{ ucfirst(str_replace('_', ' ', $request->issuance->status)) }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Pending Issuance
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        @if(!$request->issuance || $request->issuance->status !== 'completed')
                                            <a href="{{ route('admin.orders.create-issuance', $request->id) }}" 
                                               class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition">
                                                <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6z"/>
                                                    <path d="M13 5a1 1 0 011-1h3a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1V5z"/>
                                                </svg>
                                                Issue Items
                                            </a>
                                        @endif
                                        
                                        <a href="{{ route('admin.orders.review', $request->id) }}" 
                                           class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 text-sm font-medium rounded-lg transition">
                                            <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                            </svg>
                                            View
                                        </a>
                                        
                                        @if($request->issuance)
                                            <a href="{{ route('admin.orders.issuances.view', $request->issuance->id) }}" 
                                               class="inline-flex items-center px-3 py-2 bg-green-100 hover:bg-green-200 text-green-800 text-sm font-medium rounded-lg transition">
                                                <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                                                </svg>
                                                Issuance
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($requests->hasPages())
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    {{ $requests->withQueryString()->links() }}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="mx-auto h-24 w-24 text-gray-400">
                    <svg class="h-full w-full" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="mt-4 text-lg font-medium text-gray-900">No approved requests</h3>
                <p class="mt-1 text-sm text-gray-500">
                    @if(request()->has('search'))
                        No approved requests match your search criteria.
                    @else
                        All pending requests are awaiting approval.
                    @endif
                </p>
                <div class="mt-6">
                    <a href="{{ route('admin.orders.pending') }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition">
                        <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"/>
                        </svg>
                        View Pending Requests
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-green-100">Total Approved</p>
                    <p class="text-3xl font-bold mt-2">{{ $requests->total() }}</p>
                </div>
                <div class="bg-green-400 bg-opacity-20 p-3 rounded-full">
                    <svg class="h-8 w-8" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-100">Pending Issuance</p>
                    <p class="text-3xl font-bold mt-2">
                        {{ $requests->where('issuance', null)->count() }}
                    </p>
                </div>
                <div class="bg-blue-400 bg-opacity-20 p-3 rounded-full">
                    <svg class="h-8 w-8" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6z"/>
                        <path d="M13 5a1 1 0 011-1h3a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1V5z"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl shadow p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-purple-100">Items Issued</p>
                    <p class="text-3xl font-bold mt-2">
                        {{ $requests->whereNotNull('issuance')->count() }}
                    </p>
                </div>
                <div class="bg-purple-400 bg-opacity-20 p-3 rounded-full">
                    <svg class="h-8 w-8" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for additional functionality -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Search form submission on enter
        document.querySelector('input[name="search"]').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                this.form.submit();
            }
        });
        
        // Tooltip for status badges
        const statusBadges = document.querySelectorAll('[data-tooltip]');
        statusBadges.forEach(badge => {
            badge.addEventListener('mouseenter', function() {
                // Add tooltip implementation if needed
            });
        });
    });
</script>

<style>
    .hover\:bg-gray-50:hover {
        background-color: rgba(249, 250, 251, var(--tw-bg-opacity));
    }
    
    /* Ensure proper spacing for mobile */
    @media (max-width: 640px) {
        .container {
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        table {
            display: block;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
    }
</style>
@endsection