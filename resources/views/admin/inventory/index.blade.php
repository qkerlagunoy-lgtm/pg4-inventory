@extends('layouts.admin')

@section('title', 'Inventory Management')

@section('page-title', 'Inventory Management')

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
            <li class="text-blue-600 font-medium">Inventory</li>
        </ol>
    </nav>
@endsection

@section('header-actions')
    <div class="flex items-center space-x-2">
        <a href="{{ route('admin.inventory.low-stock') }}"
           class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.73 0L4.342 16.5c-.77.833.192 2.5 1.732 2.5z"/>
            </svg>
            Low Stock
        </a>
        <a href="{{ route('admin.inventory.create') }}"
           class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Item
        </a>
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

    @if(session('error'))
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Items</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $items->total() }}</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Stock</p>
                    <p class="text-2xl font-bold text-gray-800">{{ \App\Models\Item::sum('quantity') }}</p>
                </div>
                <div class="p-3 bg-green-100 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM14 11a1 1 0 011 1v1h1a1 1 0 110 2h-1v1a1 1 0 11-2 0v-1h-1a1 1 0 110-2h1v-1a1 1 0 011-1z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{--
            FIX: Clicking the Low Stock stat card now filters the table directly
            instead of navigating to a separate page — using ?stock_level=low
        --}}
        <a href="{{ route('admin.inventory.index', ['stock_level' => 'low']) }}"
           class="hover:shadow-lg transition">
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 {{ request('stock_level') == 'low' ? 'border-yellow-600' : 'border-yellow-500' }}">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Low Stock Items</p>
                        <p class="text-2xl font-bold text-gray-800">
                            {{ \App\Models\Item::where(function($q){ $q->whereColumn('quantity', '<=', 'minimum_quantity')->orWhere('quantity', 0); })->count() }}
                        </p>
                    </div>
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
            </div>
        </a>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Out of Stock</p>
                    <p class="text-2xl font-bold text-gray-800">{{ \App\Models\Item::where('quantity', 0)->count() }}</p>
                </div>
                <div class="p-3 bg-red-100 rounded-full">
                    <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('admin.inventory.index') }}" id="filterForm" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div class="md:col-span-2">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                           placeholder="Search by name or description..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Category Filter -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select name="category_id" id="category_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Stock Level Filter -->
                <div>
                    <label for="stock_level" class="block text-sm font-medium text-gray-700 mb-1">Stock Level</label>
                    <select name="stock_level" id="stock_level"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Stock Levels</option>
                        <option value="low"    {{ request('stock_level') == 'low'    ? 'selected' : '' }}>Low Stock</option>
                        <option value="out"    {{ request('stock_level') == 'out'    ? 'selected' : '' }}>Out of Stock</option>
                        <option value="normal" {{ request('stock_level') == 'normal' ? 'selected' : '' }}>Normal Stock</option>
                    </select>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                <div class="text-sm text-gray-500">
                    @if(request('stock_level') == 'low')
                        <span class="font-medium text-yellow-700">⚠ Showing low stock items only</span>
                        — {{ $items->total() }} item(s) found
                    @else
                        {{ $items->total() }} item(s) found
                    @endif
                </div>
                <div class="flex items-center space-x-3">
                    @if(request()->hasAny(['search', 'category_id', 'stock_level']))
                        <a href="{{ route('admin.inventory.index') }}"
                           class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                            Clear Filters
                        </a>
                    @endif
                    <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Apply Filters
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Inventory Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        @if($items->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                            {{-- FIX: column header renamed from "Unit" to "Unit" but now reads unit_of_measure --}}
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($items as $item)
                            <tr class="hover:bg-gray-50 transition {{ ($item->quantity == 0 || $item->quantity <= $item->minimum_quantity) ? 'bg-yellow-50' : '' }}">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <svg class="h-6 w-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"/>
                                                <path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $item->name }}</div>
                                            @if($item->description)
                                                <div class="text-xs text-gray-500 truncate max-w-xs">
                                                    {{ Str::limit($item->description, 50) }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($item->category)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $item->category->name }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-24 bg-gray-200 rounded-full h-2.5 mr-3">
                                            @php
                                                $percentage = $item->minimum_quantity > 0
                                                    ? min(100, ($item->quantity / $item->minimum_quantity) * 100)
                                                    : 0;
                                                $color = $item->quantity == 0 ? 'bg-red-500'
                                                    : ($item->quantity <= $item->minimum_quantity ? 'bg-yellow-500' : 'bg-green-500');
                                            @endphp
                                            <div class="h-2.5 rounded-full {{ $color }}" style="width: {{ $percentage }}%"></div>
                                        </div>
                                        <div class="text-sm text-gray-900 font-medium">{{ $item->quantity }}</div>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">Min: {{ $item->minimum_quantity }}</div>
                                </td>
                                {{-- FIX: was $item->unit — correct column is unit_of_measure --}}
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $item->unit_of_measure }}
                                </td>
                                {{-- FIX: removed storage_location column (not in DB) and its stray 'x' character --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($item->quantity == 0)
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Out of Stock
                                        </span>
                                    @elseif($item->quantity <= $item->minimum_quantity)
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Low Stock
                                        </span>
                                    @else
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            In Stock
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <!-- View -->
                                        <a href="{{ route('admin.inventory.show', $item) }}"
                                           class="text-blue-600 hover:text-blue-900 p-1 hover:bg-blue-50 rounded"
                                           title="View Details">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        <!-- Edit -->
                                        <a href="{{ route('admin.inventory.edit', $item) }}"
                                           class="text-green-600 hover:text-green-900 p-1 hover:bg-green-50 rounded"
                                           title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        <!-- Restock -->
                                        <button type="button"
                                                onclick="showRestockModal({{ $item->id }}, '{{ addslashes($item->name) }}')"
                                                class="text-yellow-600 hover:text-yellow-900 p-1 hover:bg-yellow-50 rounded"
                                                title="Restock">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                            </svg>
                                        </button>
                                        <!-- Delete -->
                                        <form action="{{ route('admin.inventory.destroy', $item) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    onclick="confirmDelete('{{ addslashes($item->name) }}', this)"
                                                    class="text-red-600 hover:text-red-900 p-1 hover:bg-red-50 rounded"
                                                    title="Delete">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                    {{-- NEW: Always-visible New Item row at the bottom of the table --}}
                    <tfoot>
                        <tr class="bg-gray-50 border-t-2 border-dashed border-gray-300">
                            <td colspan="6" class="px-6 py-4">
                                <a href="{{ route('admin.inventory.create') }}"
                                   class="flex items-center gap-2 text-blue-600 hover:text-blue-800 font-medium text-sm w-fit">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                    </div>
                                    Add New Item
                                </a>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Pagination -->
            @if($items->hasPages())
                <div class="bg-white px-6 py-4 border-t border-gray-200">
                    {{ $items->links() }}
                </div>
            @endif

        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No inventory items found</h3>
                <p class="mt-1 text-sm text-gray-500">
                    @if(request()->hasAny(['search', 'category_id', 'stock_level']))
                        Try adjusting your filters or
                        <a href="{{ route('admin.inventory.index') }}" class="text-blue-600 hover:underline">clear all filters</a>
                    @else
                        Get started by adding your first inventory item
                    @endif
                </p>
                <div class="mt-6">
                    <a href="{{ route('admin.inventory.create') }}"
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add New Item
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Export Options -->
    <div class="mt-6 bg-gray-50 border border-gray-200 rounded-lg p-4">
        <div class="flex items-center justify-between">
            <div>
                <h4 class="text-sm font-medium text-gray-900">Export Inventory Data</h4>
                <p class="text-sm text-gray-600 mt-1">Download inventory data for reporting</p>
            </div>
            <button type="button"
                    onclick="alert('Export feature coming soon!')"
                    class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Export to CSV
            </button>
        </div>
    </div>
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
            <h3 class="text-lg leading-6 font-medium text-gray-900 text-center" id="modalTitle">Restock Item</h3>
            <div class="mt-2 px-7 py-3">
                <form id="restockForm" method="POST" class="space-y-4">
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
                        <textarea id="restockNotes" name="notes" rows="2"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                                  placeholder="Add any notes about this restock..."></textarea>
                    </div>
                </form>
            </div>
            <div class="items-center px-4 py-3">
                <div class="flex justify-center space-x-3">
                    <button id="modalCancelBtn"
                            class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-400">
                        Cancel
                    </button>
                    <button id="modalConfirmBtn"
                            class="px-4 py-2 bg-yellow-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-yellow-700">
                        Restock
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
    function confirmDelete(itemName, button) {
        if (confirm(`Are you sure you want to delete "${itemName}"? This action cannot be undone.`)) {
            button.closest('form').submit();
        }
    }

    function showRestockModal(itemId, itemName) {
        const modal       = document.getElementById('restockModal');
        const modalTitle  = document.getElementById('modalTitle');
        const restockForm = document.getElementById('restockForm');

        modalTitle.textContent  = `Restock: ${itemName}`;
        restockForm.action      = `/admin/inventory/${itemId}/restock`;

        document.getElementById('restockQuantity').value = '';
        document.getElementById('restockNotes').value    = '';

        modal.classList.remove('hidden');

        document.getElementById('modalCancelBtn').onclick = () => modal.classList.add('hidden');

        document.getElementById('modalConfirmBtn').onclick = () => {
            if (document.getElementById('restockQuantity').value) {
                restockForm.submit();
            } else {
                alert('Please enter a quantity.');
            }
        };

        modal.addEventListener('click', e => {
            if (e.target === modal) modal.classList.add('hidden');
        });
    }

    // FIX: Auto-submit on dropdown change so Low Stock filter takes effect immediately
    document.getElementById('category_id')?.addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });

    document.getElementById('stock_level')?.addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });
</script>
@endpush