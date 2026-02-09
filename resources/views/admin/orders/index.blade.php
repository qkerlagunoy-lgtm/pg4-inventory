@extends('layouts.admin')

@section('title', 'Order Management')

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
                    <svg class="w-8 h-8 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
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
                    <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
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
                    <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 20 20">
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
                    <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3z"/>
                    </svg>
                </div>
            </div>
        </a>
    </div>

    <!-- Two Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Pending Requests -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Recent Pending Requests</h3>
                <a href="{{ route('admin.orders.pending') }}" class="text-sm text-blue-600 hover:text-blue-800">
                    View All →
                </a>
            </div>
            
            <div class="space-y-4">
                @forelse($recentRequests as $request)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-800">{{ $request->user->name }}</p>
                            <p class="text-sm text-gray-600">{{ Str::limit($request->purpose, 40) }}</p>
                        </div>
                        <div class="text-right">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                {{ ucfirst($request->status) }}
                            </span>
                            <p class="text-xs text-gray-500 mt-1">{{ $request->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-400 italic py-4">No pending requests</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Issuances -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Recent Issuances</h3>
                <a href="{{ route('admin.orders.issuances') }}" class="text-sm text-blue-600 hover:text-blue-800">
                    View All →
                </a>
            </div>
            
            <div class="space-y-4">
                @forelse($recentIssuances as $issuance)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-800">{{ $issuance->itemRequest->user->name }}</p>
                            <p class="text-sm text-gray-600">{{ $issuance->issuance_code }}</p>
                        </div>
                        <div class="text-right">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold 
                                @if($issuance->status == 'completed') bg-green-100 text-green-800
                                @elseif($issuance->status == 'partially_issued') bg-yellow-100 text-yellow-800
                                @else bg-blue-100 text-blue-800 @endif">
                                {{ ucfirst($issuance->status) }}
                            </span>
                            <p class="text-xs text-gray-500 mt-1">{{ $issuance->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-400 italic py-4">No issuances yet</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-md p-6 mt-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('admin.orders.pending') }}" 
                class="bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-lg p-4 text-center transition">
                <svg class="w-10 h-10 text-blue-600 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                </svg>
                <p class="font-medium text-gray-800">Review Requests</p>
                <p class="text-sm text-gray-600">Manage pending requests</p>
            </a>
            
            <a href="{{ route('admin.orders.issuances') }}" 
                class="bg-green-50 hover:bg-green-100 border border-green-200 rounded-lg p-4 text-center transition">
                <svg class="w-10 h-10 text-green-600 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"/>
                    <path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd"/>
                </svg>
                <p class="font-medium text-gray-800">Manage Issuances</p>
                <p class="text-sm text-gray-600">Track item issuances</p>
            </a>
            
            <a href="{{ route('admin.orders.reports') }}" 
                class="bg-purple-50 hover:bg-purple-100 border border-purple-200 rounded-lg p-4 text-center transition">
                <svg class="w-10 h-10 text-purple-600 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <p class="font-medium text-gray-800">View Reports</p>
                <p class="text-sm text-gray-600">Analytics & insights</p>
            </a>
        </div>
    </div>
@endsection