@extends('layouts.admin')

@section('title', $item->name . ' - Item Details')

@section('page-title', 'Item: ' . $item->name)

@section('breadcrumb')
    <nav class="mb-4">
        <ol class="flex items-center space-x-2 text-sm">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-gray-700">Dashboard</a>
            </li>
            <li>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </li>
            <li>
                <a href="{{ route('admin.inventory.index') }}" class="text-gray-500 hover:text-gray-700">Inventory</a>
            </li>
            <li>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </li>
            <li class="text-blue-600 font-medium">{{ Str::limit($item->name, 30) }}</li>
        </ol>
    </nav>
@endsection

@section('header-actions')
    <div class="flex items-center space-x-2">
        <a href="{{ route('admin.inventory.edit', $item) }}" 
           class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit
        </a>
        
        @if($item->quantity <= $item->minimum_quantity)
            <button type="button" 
                    onclick="showRestockModal()"
                    class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Restock
            </button>
        @endif
    </div>
@endsection

@section('content')
    <!-- Flash Messages -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Main Item Information -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Left Column: Item Details -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6">
                <!-- Item Header -->
                <div class="flex items-start justify-between mb-6">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <svg class="w-10 h-10 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"/>
                                <path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">{{ $item->name }}</h2>
                            <div class="flex items-center gap-3 mt-2">
                                @if($item->category)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        {{ $item->category->name }}
                                    </span>
                                @endif
                                <span class="text-sm text-gray-600">
                                    ID: #{{ $item->id }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Item Stats -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <p class="text-sm text-gray-600 mb-1">Current Stock</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $item->quantity }}</p>
                        <p class="text-xs text-gray-500">{{ $item->unit }}</p>
                    </div>
                    
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <p class="text-sm text-gray-600 mb-1">Minimum Quantity</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $item->minimum_quantity }}</p>
                        <p class="text-xs text-gray-500">{{ $item->unit }}</p>
                    </div>
                    
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <p class="text-sm text-gray-600 mb-1">Stock Status</p>
                        <div class="mt-1">
                            @if($item->quantity == 0)
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Out of Stock
                                </span>
                            @elseif($item->quantity <= $item->minimum_quantity)
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Low Stock
                                </span>
                            @else
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    In Stock
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <p class="text-sm text-gray-600 mb-1">Usage Rate</p>
                        <p class="text-2xl font-bold text-gray-800">
                            {{ $item->requestItems()->count() }}
                        </p>
                        <p class="text-xs text-gray-500">Total requests</p>
                    </div>
                </div>

                <!-- Stock Progress Bar -->
                <div class="mb-6">
                    <div class="flex justify-between text-sm text-gray-600 mb-2">
                        <span>Stock Level</span>
                        <span>{{ $item->quantity }} / {{ max($item->minimum_quantity * 2, $item->quantity + 10) }} {{ $item->unit }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        @php
                            $max = max($item->minimum_quantity * 2, $item->quantity + 10);
                            $percentage = $max > 0 ? min(100, ($item->quantity / $max) * 100) : 0;
                            $color = $item->quantity == 0 ? 'bg-red-500' : 
                                    ($item->quantity <= $item->minimum_quantity ? 'bg-yellow-500' : 'bg-green-500');
                        @endphp
                        <div class="h-2.5 rounded-full {{ $color }}" style="width: {{ $percentage }}%"></div>
                    </div>
                    <div class="flex justify-between text-xs text-gray-500 mt-1">
                        <span>Empty</span>
                        <span class="{{ $item->quantity <= $item->minimum_quantity ? 'text-yellow-600 font-medium' : '' }}">
                            Re-order Point: {{ $item->minimum_quantity }}
                        </span>
                        <span>Full</span>
                    </div>
                </div>

                <!-- Item Details -->
                <div class="space-y-4">
                    @if($item->description)
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Description</h4>
                            <p class="text-gray-600 whitespace-pre-line">{{ $item->description }}</p>
                        </div>
                    @endif
                    
                    @if($item->storage_location)
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Storage Location</h4>
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span class="text-gray-600">{{ $item->storage_location }}</span>
                            </div>
                        </div>
                    @endif
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Created</h4>
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-gray-600">{{ $item->created_at->format('F j, Y g:i A') }}</span>
                            </div>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Last Updated</h4>
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-gray-600">{{ $item->updated_at->format('F j, Y g:i A') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Quick Actions & Info -->
        <div class="space-y-6">
            <!-- Quick Actions Card -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <button type="button" 
                            onclick="showRestockModal()"
                            class="w-full flex items-center justify-between p-3 bg-yellow-50 border border-yellow-200 rounded-lg hover:bg-yellow-100 transition">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-yellow-100 rounded-lg">
                                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                            </div>
                            <div class="text-left">
                                <p class="font-medium text-gray-900">Restock Item</p>
                                <p class="text-sm text-gray-600">Add more quantity</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                    
                    <a href="{{ route('admin.inventory.edit', $item) }}" 
                       class="w-full flex items-center justify-between p-3 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-blue-100 rounded-lg">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </div>
                            <div class="text-left">
                                <p class="font-medium text-gray-900">Edit Details</p>
                                <p class="text-sm text-gray-600">Update information</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                    
                    <button type="button" 
                            onclick="showDeleteModal()"
                            class="w-full flex items-center justify-between p-3 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-red-100 rounded-lg">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </div>
                            <div class="text-left">
                                <p class="font-medium text-gray-900">Delete Item</p>
                                <p class="text-sm text-gray-600">Remove from inventory</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Related Items Card -->
            @if($item->category && $item->category->items->where('id', '!=', $item->id)->count() > 0)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Related Items</h3>
                    <div class="space-y-3">
                        @foreach($item->category->items->where('id', '!=', $item->id)->take(3) as $relatedItem)
                            <a href="{{ route('admin.inventory.show', $relatedItem) }}" 
                               class="flex items-center justify-between p-2 hover:bg-gray-50 rounded-lg transition">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"/>
                                            <path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ Str::limit($relatedItem->name, 25) }}</p>
                                        <p class="text-xs text-gray-500">{{ $relatedItem->quantity }} {{ $relatedItem->unit }}</p>
                                    </div>
                                </div>
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        @endforeach
                        @if($item->category->items->where('id', '!=', $item->id)->count() > 3)
                            <a href="{{ route('admin.inventory.index') }}?category_id={{ $item->category_id }}" 
                               class="text-sm text-blue-600 hover:text-blue-800 text-center block pt-2">
                                View all {{ $item->category->items->count() - 1 }} items in this category →
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Transaction History Tabs -->
    <div id="transactions" class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <!-- Tabs Header -->
        <div class="border-b border-gray-200">
            <nav class="flex">
                <button type="button" 
                        id="requestsTab"
                        class="px-6 py-3 text-sm font-medium border-b-2 border-blue-500 text-blue-600">
                    Request History
                    <span class="ml-2 px-2 py-1 text-xs font-bold bg-blue-100 text-blue-800 rounded-full">
                        {{ $item->requestItems->count() }}
                    </span>
                </button>
                <button type="button" 
                        id="issuancesTab"
                        class="px-6 py-3 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent">
                    Issuance History
                    <span class="ml-2 px-2 py-1 text-xs font-bold bg-gray-100 text-gray-800 rounded-full">
                        {{ $item->issuanceItems->count() }}
                    </span>
                </button>
            </nav>
        </div>
        
        <!-- Requests Tab Content -->
        <div id="requestsContent" class="p-6">
            @if($item->requestItems->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Request #
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Requester
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Quantity Requested
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($item->requestItems->sortByDesc('created_at')->take(10) as $requestItem)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        #{{ $requestItem->itemRequest->id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $requestItem->itemRequest->user->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $requestItem->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $requestItem->quantity }} {{ $item->unit }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusColors = [
                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                'approved' => 'bg-green-100 text-green-800',
                                                'rejected' => 'bg-red-100 text-red-800',
                                                'issued' => 'bg-blue-100 text-blue-800',
                                                'partially_issued' => 'bg-purple-100 text-purple-800',
                                            ];
                                            $colorClass = $statusColors[$requestItem->status] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $colorClass }}">
                                            {{ ucfirst(str_replace('_', ' ', $requestItem->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('admin.orders.review', $requestItem->itemRequest->id) }}" 
                                           class="text-blue-600 hover:text-blue-900">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if($item->requestItems->count() > 10)
                    <div class="mt-4 text-center">
                        <a href="{{ route('admin.orders.pending') }}?item_id={{ $item->id }}" 
                           class="text-sm text-blue-600 hover:text-blue-800">
                            View all {{ $item->requestItems->count() }} requests →
                        </a>
                    </div>
                @endif
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No request history</h3>
                    <p class="mt-1 text-sm text-gray-500">This item has not been requested yet.</p>
                </div>
            @endif
        </div>
        
        <!-- Issuances Tab Content -->
        <div id="issuancesContent" class="p-6 hidden">
            @if($item->issuanceItems->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Issuance #
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Recipient
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date Issued
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Quantity Issued
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($item->issuanceItems->sortByDesc('created_at')->take(10) as $issuanceItem)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        #{{ $issuanceItem->issuance->id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $issuanceItem->issuance->itemRequest->user->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $issuanceItem->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $issuanceItem->quantity_issued }} {{ $item->unit }}
                                        @if($issuanceItem->quantity_returned)
                                            <span class="text-xs text-gray-500">
                                                ({{ $issuanceItem->quantity_returned }} returned)
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusColors = [
                                                'issued' => 'bg-blue-100 text-blue-800',
                                                'returned' => 'bg-green-100 text-green-800',
                                                'lost' => 'bg-red-100 text-red-800',
                                            ];
                                            $colorClass = $statusColors[$issuanceItem->status] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $colorClass }}">
                                            {{ ucfirst($issuanceItem->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('admin.orders.issuances.view', $issuanceItem->issuance->id) }}" 
                                           class="text-blue-600 hover:text-blue-900">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if($item->issuanceItems->count() > 10)
                    <div class="mt-4 text-center">
                        <a href="{{ route('admin.orders.issuances') }}?item_id={{ $item->id }}" 
                           class="text-sm text-blue-600 hover:text-blue-800">
                            View all {{ $item->issuanceItems->count() }} issuances →
                        </a>
                    </div>
                @endif
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No issuance history</h3>
                    <p class="mt-1 text-sm text-gray-500">This item has not been issued yet.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Audit Log (Optional) -->
    @if($item->auditLogs && $item->auditLogs->count() > 0)
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Activity</h3>
            <div class="space-y-4">
                @foreach($item->auditLogs->sortByDesc('created_at')->take(5) as $log)
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-900">
                                {{ $log->description }}
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $log->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
@endsection

@push('modals')
<!-- Restock Modal -->
<div id="restockModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 mb-4">
                <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 text-center">Restock: {{ $item->name }}</h3>
            <div class="mt-2 px-7 py-3">
                <form action="{{ route('admin.inventory.restock', $item) }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label for="restockQuantity" class="block text-sm font-medium text-gray-700 mb-1">
                            Quantity to Add *
                        </label>
                        <input type="number" id="restockQuantity" name="quantity" min="1" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                               placeholder="Enter quantity" required>
                    </div>
                    
                    <div>
                        <label for="restockNotes" class="block text-sm font-medium text-gray-700 mb-1">
                            Notes (Optional)
                        </label>
                        <textarea id="restockNotes" name="notes" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                                  placeholder="Add any notes about this restock..."></textarea>
                    </div>
                    
                    <!-- Current Stock Info -->
                    <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg">
                        <div class="text-sm text-gray-600">
                            <div class="flex justify-between mb-1">
                                <span>Current Stock:</span>
                                <span class="font-medium">{{ $item->quantity }} {{ $item->unit }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>After Restock:</span>
                                <span class="font-medium text-green-600" id="afterRestock">—</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-center space-x-3 pt-2">
                        <button type="button" 
                                onclick="hideRestockModal()"
                                class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-400">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-yellow-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-yellow-700">
                            Restock
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 text-center mb-2">Delete Item</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500 text-center">
                    Are you sure you want to delete "<span class="font-medium text-gray-900">{{ $item->name }}</span>"?
                </p>
                <p class="text-sm text-red-600 text-center mt-2">
                    Warning: This action cannot be undone!
                </p>
                
                @if($item->requestItems()->count() > 0 || $item->issuanceItems()->count() > 0)
                    <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                        <p class="text-xs text-red-700 font-medium mb-1">Cannot delete this item because:</p>
                        <ul class="text-xs text-red-600 space-y-1">
                            @if($item->requestItems()->count() > 0)
                                <li>• Has {{ $item->requestItems()->count() }} request history records</li>
                            @endif
                            @if($item->issuanceItems()->count() > 0)
                                <li>• Has {{ $item->issuanceItems()->count() }} issuance records</li>
                            @endif
                            <li>• Items with transaction history cannot be deleted</li>
                        </ul>
                    </div>
                @endif
            </div>
            <div class="items-center px-4 py-3">
                <div class="flex justify-center space-x-3">
                    <button id="deleteModalCancelBtn" 
                            class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-400">
                        Cancel
                    </button>
                    
                    @if($item->requestItems()->count() == 0 && $item->issuanceItems()->count() == 0)
                        <form action="{{ route('admin.inventory.destroy', $item) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700">
                                Delete Item
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tab Switching
        const requestsTab = document.getElementById('requestsTab');
        const issuancesTab = document.getElementById('issuancesTab');
        const requestsContent = document.getElementById('requestsContent');
        const issuancesContent = document.getElementById('issuancesContent');
        
        if (requestsTab && issuancesTab) {
            requestsTab.addEventListener('click', function() {
                this.classList.add('border-blue-500', 'text-blue-600');
                this.classList.remove('border-transparent', 'text-gray-500');
                issuancesTab.classList.add('border-transparent', 'text-gray-500');
                issuancesTab.classList.remove('border-blue-500', 'text-blue-600');
                
                requestsContent.classList.remove('hidden');
                issuancesContent.classList.add('hidden');
            });
            
            issuancesTab.addEventListener('click', function() {
                this.classList.add('border-blue-500', 'text-blue-600');
                this.classList.remove('border-transparent', 'text-gray-500');
                requestsTab.classList.add('border-transparent', 'text-gray-500');
                requestsTab.classList.remove('border-blue-500', 'text-blue-600');
                
                issuancesContent.classList.remove('hidden');
                requestsContent.classList.add('hidden');
            });
        }
        
        // Restock Modal Logic
        const restockQuantity = document.getElementById('restockQuantity');
        const afterRestock = document.getElementById('afterRestock');
        
        if (restockQuantity && afterRestock) {
            restockQuantity.addEventListener('input', function() {
                const currentQty = {{ $item->quantity }};
                const addQty = parseInt(this.value) || 0;
                const unit = '{{ $item->unit }}';
                
                afterRestock.textContent = `${currentQty + addQty} ${unit}`;
            });
        }
    });
    
    // Modal Functions
    function showRestockModal() {
        const modal = document.getElementById('restockModal');
        modal.classList.remove('hidden');
        
        // Reset form
        document.getElementById('restockQuantity').value = '';
        document.getElementById('restockNotes').value = '';
        document.getElementById('afterRestock').textContent = '—';
        
        // Close modal on background click
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                hideRestockModal();
            }
        });
    }
    
    function hideRestockModal() {
        document.getElementById('restockModal').classList.add('hidden');
    }
    
    function showDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.remove('hidden');
        
        // Setup cancel button
        document.getElementById('deleteModalCancelBtn').onclick = hideDeleteModal;
        
        // Close modal on background click
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                hideDeleteModal();
            }
        });
    }
    
    function hideDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
    
    // Close modals with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            hideRestockModal();
            hideDeleteModal();
        }
    });
</script>
@endpush