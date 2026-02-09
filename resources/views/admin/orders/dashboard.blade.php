@extends('layouts.admin')

@section('title', 'Order Management Dashboard')

@section('page-title', 'Order Management')

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

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <!-- Pending Requests -->
        <a href="{{ route('admin.orders.pending') }}" 
            class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Pending Requests</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['pending_requests'] }}</p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </a>

        <!-- Approved Requests -->
        <a href="{{ route('admin.orders.approved') }}" 
            class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Approved Requests</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['approved_requests'] }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </a>

        <!-- Rejected Requests -->
        <a href="{{ route('admin.orders.rejected') }}" 
            class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Rejected Requests</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['rejected_requests'] }}</p>
                </div>
                <div class="bg-red-100 p-3 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </a>

        <!-- Total Issuances -->
        <a href="{{ route('admin.orders.issuances') }}" 
            class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Issuances</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['total_issuances'] }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3z"/>
                    </svg>
                </div>
            </div>
        </a>
    </div>

    <!-- Additional Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <!-- Pending Issuances -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-orange-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Pending Issuances</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['pending_issuances'] }}</p>
                </div>
                <div class="bg-orange-100 p-3 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Overdue Items -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Overdue Items</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['overdue_items'] }}</p>
                </div>
                <div class="bg-purple-100 p-3 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- This Month Issuances -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-cyan-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">This Month</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['monthly_issuances'] ?? '0' }}</p>
                    <p class="text-xs text-gray-500">Issuances this month</p>
                </div>
                <div class="bg-cyan-100 p-3 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-cyan-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Two Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Pending Requests -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Recent Pending Requests</h3>
                <a href="{{ route('admin.orders.pending') }}" class="text-sm text-blue-600 hover:text-blue-800 flex items-center">
                    View All
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                </a>
            </div>
            
            <div class="space-y-3">
                @forelse($recentRequests as $request)
                    <a href="{{ route('admin.orders.review', $request->id) }}" 
                       class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-1">
                                <p class="font-medium text-gray-800">{{ $request->user->name }}</p>
                                <span class="px-2 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                    {{ strtoupper($request->priority ?? 'normal') }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 truncate">{{ $request->purpose }}</p>
                            <div class="flex items-center mt-2 text-xs text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                </svg>
                                {{ $request->created_at->format('M d, Y') }}
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                Pending
                            </span>
                            <p class="text-xs text-gray-500 mt-1">{{ $request->created_at->diffForHumans() }}</p>
                        </div>
                    </a>
                @empty
                    <div class="text-center py-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="text-gray-400 italic">No pending requests</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Issuances -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Recent Issuances</h3>
                <a href="{{ route('admin.orders.issuances') }}" class="text-sm text-blue-600 hover:text-blue-800 flex items-center">
                    View All
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                </a>
            </div>
            
            <div class="space-y-3">
                @forelse($recentIssuances as $issuance)
                    <a href="{{ route('admin.orders.issuances.view', $issuance->id) }}" 
                       class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-1">
                                <p class="font-medium text-gray-800">{{ $issuance->itemRequest->user->name }}</p>
                                <span class="text-xs font-medium text-gray-500">#{{ $issuance->id }}</span>
                            </div>
                            <p class="text-sm text-gray-600">Issuance Code: <span class="font-mono">{{ $issuance->issuance_code ?? 'N/A' }}</span></p>
                            <div class="flex items-center mt-2 text-xs text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                </svg>
                                {{ $issuance->issued_at->format('M d, Y') }}
                            </div>
                        </div>
                        <div class="text-right">
                            @php
                                $statusColors = [
                                    'completed' => 'bg-green-100 text-green-800',
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'partially_issued' => 'bg-orange-100 text-orange-800',
                                ];
                                $statusColor = $statusColors[$issuance->status] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $statusColor }}">
                                {{ ucfirst(str_replace('_', ' ', $issuance->status)) }}
                            </span>
                            <p class="text-xs text-gray-500 mt-1">{{ $issuance->created_at->diffForHumans() }}</p>
                        </div>
                    </a>
                @empty
                    <div class="text-center py-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <p class="text-gray-400 italic">No issuances yet</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-md p-6 mt-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('admin.orders.pending') }}" 
                class="bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-lg p-5 text-center transition hover:shadow-md">
                <div class="bg-blue-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <p class="font-medium text-gray-800">Review Requests</p>
                <p class="text-sm text-gray-600 mt-1">Manage pending requests</p>
            </a>
            
            <a href="{{ route('admin.orders.create-issuance', 'new') }}" 
                class="bg-green-50 hover:bg-green-100 border border-green-200 rounded-lg p-5 text-center transition hover:shadow-md">
                <div class="bg-green-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"/>
                        <path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <p class="font-medium text-gray-800">Create Issuance</p>
                <p class="text-sm text-gray-600 mt-1">Issue items to users</p>
            </a>
            
            <a href="{{ route('admin.orders.reports') }}" 
                class="bg-purple-50 hover:bg-purple-100 border border-purple-200 rounded-lg p-5 text-center transition hover:shadow-md">
                <div class="bg-purple-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <p class="font-medium text-gray-800">View Reports</p>
                <p class="text-sm text-gray-600 mt-1">Analytics & insights</p>
            </a>
        </div>
    </div>

    <!-- Navigation Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
        <a href="{{ route('admin.orders.pending') }}" class="bg-gradient-to-r from-yellow-50 to-yellow-100 border border-yellow-200 rounded-lg p-4 hover:shadow-md transition">
            <div class="flex items-center">
                <div class="bg-yellow-100 p-2 rounded-lg mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <p class="font-medium text-gray-800">Pending</p>
                    <p class="text-xs text-gray-600">Review requests</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.orders.approved') }}" class="bg-gradient-to-r from-green-50 to-green-100 border border-green-200 rounded-lg p-4 hover:shadow-md transition">
            <div class="flex items-center">
                <div class="bg-green-100 p-2 rounded-lg mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <p class="font-medium text-gray-800">Approved</p>
                    <p class="text-xs text-gray-600">Ready for issuance</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.orders.rejected') }}" class="bg-gradient-to-r from-red-50 to-red-100 border border-red-200 rounded-lg p-4 hover:shadow-md transition">
            <div class="flex items-center">
                <div class="bg-red-100 p-2 rounded-lg mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <p class="font-medium text-gray-800">Rejected</p>
                    <p class="text-xs text-gray-600">View declined requests</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.orders.returns') }}" class="bg-gradient-to-r from-purple-50 to-purple-100 border border-purple-200 rounded-lg p-4 hover:shadow-md transition">
            <div class="flex items-center">
                <div class="bg-purple-100 p-2 rounded-lg mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <p class="font-medium text-gray-800">Returns</p>
                    <p class="text-xs text-gray-600">Track item returns</p>
                </div>
            </div>
        </a>
    </div>
@endsection

@section('scripts')
<script>
    // Auto-refresh stats every 60 seconds
    setInterval(function() {
        // You can add AJAX call here to refresh stats without page reload
        // Example: fetch('/admin/orders/stats').then(...)
    }, 60000);
</script>
@endsection