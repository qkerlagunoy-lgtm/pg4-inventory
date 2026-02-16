@extends('layouts.admin')

@section('title', 'Low Stock Alerts')

@section('page-title', 'Low Stock Alerts')

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
            <li class="text-blue-600 font-medium">Low Stock Alerts</li>
        </ol>
    </nav>
@endsection

@section('header-actions')
    <div class="flex items-center space-x-2">
        <button type="button"
                onclick="printLowStockReport()"
                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Print Report
        </button>

        <button type="button"
                onclick="exportLowStockCSV()"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Export CSV
        </button>
    </div>
@endsection

@section('content')
    <!-- Critical Alerts Banner -->
    @if($criticalItems->count() > 0)
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-medium text-red-800">Critical Stock Alert!</h3>
                    <p class="mt-2 text-red-700">
                        <span class="font-bold">{{ $criticalItems->count() }}</span> item(s) are
                        <span class="font-bold">out of stock</span> or critically low. Immediate action is required.
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Summary Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Low Stock Items</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $items->total() }}</p>
                </div>
                <div class="p-3 bg-yellow-100 rounded-full">
                    <svg class="w-8 h-8 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Out of Stock</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $outOfStockCount }}</p>
                </div>
                <div class="p-3 bg-red-100 rounded-full">
                    <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Critical Low Stock</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $criticalItems->count() }}</p>
                </div>
                <div class="p-3 bg-orange-100 rounded-full">
                    <svg class="w-8 h-8 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Categories Affected</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $affectedCategoriesCount }}</p>
                </div>
                <div class="p-3 bg-purple-100 rounded-full">
                    <svg class="w-8 h-8 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
            <div>
                <h3 class="text-lg font-medium text-gray-900">Filter Low Stock Items</h3>
                <p class="text-sm text-gray-600">Items flagged when stock reaches or falls below minimum quantity</p>
            </div>
            <div class="flex items-center space-x-3">
                <div class="relative">
                    <input type="text" id="searchItems" placeholder="Search items..."
                           class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-64">
                    <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <select id="severityFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Severity Levels</option>
                    <option value="critical">Critical (Out of Stock)</option>
                    <option value="low">Low Stock</option>
                </select>
            </div>
        </div>

        <div class="flex items-center justify-between pt-4 border-t border-gray-200">
            <div class="flex items-center space-x-4">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                    <span class="text-xs text-gray-600">Out of Stock</span>
                </div>
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-orange-500 rounded-full mr-2"></div>
                    <span class="text-xs text-gray-600">Critical (≤ 25%)</span>
                </div>
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></div>
                    <span class="text-xs text-gray-600">Low (≤ 50%)</span>
                </div>
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div>
                    <span class="text-xs text-gray-600">Warning (≤ 75%)</span>
                </div>
            </div>
            <div class="text-sm text-gray-500">
                Showing {{ $items->count() }} of {{ $items->total() }} items
            </div>
        </div>
    </div>

    <!-- Critical Items Section -->
    @if($criticalItems->count() > 0)
        <div class="mb-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-red-700 flex items-center gap-2">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    Critical Items (Out of Stock or ≤ 25% of Minimum)
                </h3>
                <span class="px-3 py-1 bg-red-100 text-red-800 text-sm font-medium rounded-full">
                    {{ $criticalItems->count() }} items
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($criticalItems as $criticalItem)
                    <div class="bg-white border border-red-200 rounded-lg shadow-md overflow-hidden">
                        <div class="p-4 border-b border-red-100 bg-red-50">
                            <div class="flex items-center justify-between">
                                <h4 class="font-bold text-red-800 truncate">{{ $criticalItem->name }}</h4>
                                <span class="px-2 py-1 bg-red-100 text-red-800 text-xs font-bold rounded">
                                    {{ $criticalItem->minimum_quantity > 0 ? round(($criticalItem->quantity / $criticalItem->minimum_quantity) * 100, 1) : 0 }}%
                                </span>
                            </div>
                            @if($criticalItem->category)
                                <div class="text-xs text-red-600 mt-1">{{ $criticalItem->category->name }}</div>
                            @endif
                        </div>

                        <div class="p-4">
                            <div class="flex justify-between items-center mb-3">
                                <div>
                                    <div class="text-2xl font-bold text-gray-800">{{ $criticalItem->quantity }}</div>
                                    {{-- FIX: was $item->unit --}}
                                    <div class="text-xs text-gray-500">{{ $criticalItem->unit_of_measure }}</div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm text-gray-600">Minimum</div>
                                    <div class="text-lg font-bold text-red-600">{{ $criticalItem->minimum_quantity }}</div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="flex justify-between text-xs text-gray-600 mb-1">
                                    <span>Stock Level</span>
                                    <span>{{ $criticalItem->quantity }} / {{ $criticalItem->minimum_quantity }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    @php
                                        $critPct = $criticalItem->minimum_quantity > 0
                                            ? min(100, ($criticalItem->quantity / $criticalItem->minimum_quantity) * 100)
                                            : 0;
                                    @endphp
                                    <div class="h-2 rounded-full bg-red-500" style="width: {{ $critPct }}%"></div>
                                </div>
                            </div>

                            <div class="flex space-x-2">
                                <a href="{{ route('admin.inventory.show', $criticalItem) }}"
                                   class="flex-1 px-3 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded hover:bg-gray-200 transition text-center">
                                    View
                                </a>
                                <button type="button"
                                        onclick="quickRestock({{ $criticalItem->id }}, '{{ addslashes($criticalItem->name) }}', {{ $criticalItem->quantity }}, '{{ $criticalItem->unit_of_measure }}')"
                                        class="flex-1 px-3 py-2 bg-red-600 text-white text-sm font-medium rounded hover:bg-red-700 transition">
                                    Restock
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- All Low Stock Items Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">All Low Stock Items</h3>
                <div class="text-sm text-gray-500">
                    Sorted by: <span class="font-medium">Stock Level (Lowest First)</span>
                </div>
            </div>
        </div>

        @if($items->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Stock</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Minimum Required</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock Level</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Severity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($items as $item)
                            @php
                                $percentage = $item->minimum_quantity > 0
                                    ? ($item->quantity / $item->minimum_quantity) * 100
                                    : 0;

                                if ($item->quantity == 0) {
                                    $severity      = 'out';
                                    $severityColor = 'bg-red-100 text-red-800';
                                    $severityText  = 'Out of Stock';
                                } elseif ($percentage <= 25) {
                                    $severity      = 'critical';
                                    $severityColor = 'bg-orange-100 text-orange-800';
                                    $severityText  = 'Critical';
                                } elseif ($percentage <= 50) {
                                    $severity      = 'low';
                                    $severityColor = 'bg-yellow-100 text-yellow-800';
                                    $severityText  = 'Low';
                                } else {
                                    $severity      = 'warning';
                                    $severityColor = 'bg-blue-100 text-blue-800';
                                    $severityText  = 'Warning';
                                }

                                $progressColor = $severity == 'out'      ? 'bg-red-500'    :
                                               ($severity == 'critical'  ? 'bg-orange-500' :
                                               ($severity == 'low'       ? 'bg-yellow-500' : 'bg-blue-500'));
                            @endphp
                            <tr class="hover:bg-gray-50 transition" data-severity="{{ $severity }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-gray-100 rounded-lg flex items-center justify-center">
                                            <svg class="h-6 w-6 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"/>
                                                <path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $item->name }}</div>
                                            <div class="text-xs text-gray-400">ID #{{ $item->id }}</div>
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
                                    <div class="text-sm font-bold text-gray-900">{{ $item->quantity }}</div>
                                    {{-- FIX: was $item->unit --}}
                                    <div class="text-xs text-gray-500">{{ $item->unit_of_measure }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $item->minimum_quantity }}</div>
                                    {{-- FIX: was $item->unit --}}
                                    <div class="text-xs text-gray-500">{{ $item->unit_of_measure }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-32 bg-gray-200 rounded-full h-2 mr-3">
                                            <div class="h-2 rounded-full {{ $progressColor }}"
                                                 style="width: {{ min(100, $percentage) }}%"></div>
                                        </div>
                                        <div class="text-sm text-gray-900 font-medium">
                                            {{ round($percentage, 1) }}%
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $severityColor }}">
                                        {{ $severityText }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.inventory.show', $item) }}"
                                           class="text-blue-600 hover:text-blue-900">View</a>
                                        <span class="text-gray-300">|</span>
                                        {{--
                                            FIX: Pass quantity and unit_of_measure directly as arguments
                                            instead of relying on itemsData JS lookup which used item.unit
                                        --}}
                                        <button type="button"
                                                onclick="quickRestock({{ $item->id }}, '{{ addslashes($item->name) }}', {{ $item->quantity }}, '{{ $item->unit_of_measure }}')"
                                                class="text-green-600 hover:text-green-900">
                                            Restock
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($items->hasPages())
                <div class="bg-white px-6 py-4 border-t border-gray-200">
                    {{ $items->links() }}
                </div>
            @endif

        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No low stock items!</h3>
                <p class="mt-1 text-sm text-gray-500">All inventory items are above their minimum quantities.</p>
                <div class="mt-6">
                    <a href="{{ route('admin.inventory.index') }}"
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        View All Inventory
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Restock Suggestions -->
    @if($items->count() > 0)
        <div class="mt-6 bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">📋 Restock Suggestions</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="text-sm font-medium text-gray-700 mb-3">Prioritize by Category</h4>
                    <div class="space-y-3">
                        @foreach($lowStockByCategory as $category)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $category->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $category->low_stock_count }} items low</p>
                                </div>
                                <a href="{{ route('admin.inventory.index') }}?category_id={{ $category->id }}&stock_level=low"
                                   class="text-sm text-blue-600 hover:text-blue-800">View →</a>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div>
                    <h4 class="text-sm font-medium text-gray-700 mb-3">Quick Actions</h4>
                    <div class="space-y-3">
                        <button type="button"
                                onclick="restockAllCritical()"
                                class="w-full flex items-center justify-between p-3 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-red-100 rounded-lg">
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                    </svg>
                                </div>
                                <div class="text-left">
                                    <p class="font-medium text-gray-900">Restock Critical Items</p>
                                    <p class="text-sm text-gray-600">Add stock to {{ $criticalItems->count() }} critical items</p>
                                </div>
                            </div>
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>

                        <a href="{{ route('admin.inventory.create') }}"
                           class="w-full flex items-center justify-between p-3 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-blue-100 rounded-lg">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                </div>
                                <div class="text-left">
                                    <p class="font-medium text-gray-900">Add New Items</p>
                                    <p class="text-sm text-gray-600">Prevent future stockouts</p>
                                </div>
                            </div>
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('modals')
<!-- Quick Restock Modal -->
<div id="quickRestockModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 text-center" id="restockItemName">
                Restock Item
            </h3>
            <div class="mt-2 px-7 py-3">
                <form id="quickRestockForm" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label for="quickRestockQuantity" class="block text-sm font-medium text-gray-700 mb-1">
                            Quantity to Add *
                        </label>
                        <div class="flex items-center space-x-3">
                            <input type="number" id="quickRestockQuantity" name="quantity" min="1"
                                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                   placeholder="Enter quantity" required>
                            <div class="w-20 text-center">
                                <span class="text-sm text-gray-500" id="restockUnit">—</span>
                            </div>
                        </div>
                        <div class="mt-2 flex space-x-2">
                            <button type="button" onclick="setRestockQuantity(25)"
                                    class="px-3 py-1 text-xs bg-gray-100 text-gray-700 rounded hover:bg-gray-200">+25</button>
                            <button type="button" onclick="setRestockQuantity(50)"
                                    class="px-3 py-1 text-xs bg-gray-100 text-gray-700 rounded hover:bg-gray-200">+50</button>
                            <button type="button" onclick="setRestockQuantity(100)"
                                    class="px-3 py-1 text-xs bg-gray-100 text-gray-700 rounded hover:bg-gray-200">+100</button>
                        </div>
                    </div>

                    <div>
                        <label for="quickRestockNotes" class="block text-sm font-medium text-gray-700 mb-1">
                            Notes (Optional)
                        </label>
                        <textarea id="quickRestockNotes" name="notes" rows="2"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                  placeholder="Add any notes about this restock..."></textarea>
                    </div>

                    <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg">
                        <div class="text-sm text-gray-600">
                            <div class="flex justify-between mb-1">
                                <span>Current Stock:</span>
                                <span class="font-medium" id="currentStock">—</span>
                            </div>
                            <div class="flex justify-between">
                                <span>After Restock:</span>
                                <span class="font-medium text-green-600" id="afterQuickRestock">—</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-center space-x-3 pt-2">
                        <button type="button"
                                onclick="hideQuickRestockModal()"
                                class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-400">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-green-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-green-700">
                            Restock Now
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
    {{--
        FIX: Removed itemsData JS lookup entirely (it used item.unit which doesn't exist).
        Instead, quickRestock() now receives currentQty and unit directly from Blade
        as inline onclick arguments — reliable and always correct.
    --}}
    function quickRestock(itemId, itemName, currentQty, unit) {
        const modal          = document.getElementById('quickRestockModal');
        const form           = document.getElementById('quickRestockForm');
        const currentStock   = document.getElementById('currentStock');
        const afterRestock   = document.getElementById('afterQuickRestock');
        const itemNameEl     = document.getElementById('restockItemName');
        const unitDisplay    = document.getElementById('restockUnit');
        const quantityInput  = document.getElementById('quickRestockQuantity');

        itemNameEl.textContent    = `Restock: ${itemName}`;
        currentStock.textContent  = `${currentQty} ${unit}`;
        unitDisplay.textContent   = unit;
        form.action               = `/admin/inventory/${itemId}/restock`;

        // Reset fields
        quantityInput.value                    = '';
        document.getElementById('quickRestockNotes').value = '';
        afterRestock.textContent               = '—';

        // Live "after restock" preview
        quantityInput.oninput = function() {
            const addQty = parseInt(this.value) || 0;
            afterRestock.textContent = `${currentQty + addQty} ${unit}`;
        };

        modal.classList.remove('hidden');
        quantityInput.focus();

        modal.onclick = function(e) {
            if (e.target === modal) hideQuickRestockModal();
        };
    }

    function hideQuickRestockModal() {
        document.getElementById('quickRestockModal').classList.add('hidden');
    }

    function setRestockQuantity(quantity) {
        const input = document.getElementById('quickRestockQuantity');
        input.value = quantity;
        input.dispatchEvent(new Event('input'));
    }

    // Table search + severity filter
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput    = document.getElementById('searchItems');
        const severityFilter = document.getElementById('severityFilter');
        const tableRows      = document.querySelectorAll('tbody tr');

        function filterTable() {
            const searchTerm    = searchInput.value.toLowerCase();
            const severityValue = severityFilter.value;

            tableRows.forEach(row => {
                const nameEl = row.querySelector('td:first-child .text-sm.font-medium');
                if (!nameEl) return;
                const itemName = nameEl.textContent.toLowerCase();
                const severity = row.getAttribute('data-severity');

                const matchesSearch   = itemName.includes(searchTerm);
                const matchesSeverity = !severityValue || severity === severityValue;

                row.style.display = (matchesSearch && matchesSeverity) ? '' : 'none';
            });
        }

        searchInput?.addEventListener('input', filterTable);
        severityFilter?.addEventListener('change', filterTable);
    });

    function exportLowStockCSV() {
        const rows    = [];
        const headers = ['Item Name', 'Category', 'Current Stock', 'Unit', 'Minimum Quantity', 'Stock Level %', 'Severity'];
        rows.push(headers.join(','));

        document.querySelectorAll('tbody tr').forEach(row => {
            const cells = row.querySelectorAll('td');
            if (!cells.length) return;
            const name      = cells[0].querySelector('.text-sm.font-medium')?.textContent.trim() ?? '';
            const category  = cells[1].textContent.trim();
            const stock     = cells[2].querySelector('.text-sm.font-bold')?.textContent.trim() ?? '';
            const unit      = cells[2].querySelector('.text-xs')?.textContent.trim() ?? '';
            const minQty    = cells[3].querySelector('.text-sm')?.textContent.trim() ?? '';
            const pct       = cells[4].querySelector('.font-medium')?.textContent.trim() ?? '';
            const severity  = cells[5].textContent.trim();
            rows.push([`"${name}"`, `"${category}"`, stock, unit, minQty, pct, `"${severity}"`].join(','));
        });

        const blob = new Blob([rows.join('\n')], { type: 'text/csv' });
        const url  = window.URL.createObjectURL(blob);
        const a    = document.createElement('a');
        a.href     = url;
        a.download = `low-stock-alerts-${new Date().toISOString().split('T')[0]}.csv`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);
    }

    function printLowStockReport() { window.print(); }

    function restockAllCritical() {
        alert('Bulk restocking: please restock items individually using the Restock button on each row.');
    }

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') hideQuickRestockModal();
    });

    const style = document.createElement('style');
    style.innerHTML = `
        @media print {
            .no-print { display: none !important; }
            body { font-size: 12pt; }
            table { page-break-inside: auto; }
            tr { page-break-inside: avoid; }
            thead { display: table-header-group; }
        }`;
    document.head.appendChild(style);
</script>
@endpush