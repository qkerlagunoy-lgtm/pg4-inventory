{{-- resources/views/admin/orders/reports.blade.php --}}
@extends('layouts.admin')

@section('title', 'Order Reports')

@section('page-title', 'Order Reports & Analytics')

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

<!-- Report Filters -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Filter Reports</h3>
    
    <form method="GET" action="{{ route('admin.orders.reports') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Year</label>
            <select name="year" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @for($i = date('Y'); $i >= 2020; $i--)
                    <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
            </select>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Month</label>
            <select name="month" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">All Months</option>
                @for($i = 1; $i <= 12; $i++)
                    <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}" 
                            {{ $month == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                        {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                    </option>
                @endfor
            </select>
        </div>
        
        <div class="flex items-end">
            <button type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition">
                Generate Report
            </button>
        </div>
        
        <div class="flex items-end justify-end">
            <a href="{{ route('admin.orders.export', ['type' => 'requests', 'year' => $year, 'month' => $month]) }}" 
               class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md transition">
                Export to CSV
            </a>
        </div>
    </form>
</div>

<!-- Summary Statistics -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <!-- Total Requests -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Total Requests</p>
                <p class="text-3xl font-bold text-gray-800">
                    {{ $monthlyStats->sum('total_requests') }}
                </p>
            </div>
            <div class="bg-blue-100 p-3 rounded-full">
                <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Approved Requests -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Approved</p>
                <p class="text-3xl font-bold text-gray-800">
                    {{ $monthlyStats->sum('approved') }}
                </p>
                <p class="text-sm text-gray-500">
                    {{ $monthlyStats->sum('total_requests') > 0 ? 
                       round(($monthlyStats->sum('approved') / $monthlyStats->sum('total_requests')) * 100, 1) : 0 }}%
                </p>
            </div>
            <div class="bg-green-100 p-3 rounded-full">
                <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Rejected Requests -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Rejected</p>
                <p class="text-3xl font-bold text-gray-800">
                    {{ $monthlyStats->sum('rejected') }}
                </p>
                <p class="text-sm text-gray-500">
                    {{ $monthlyStats->sum('total_requests') > 0 ? 
                       round(($monthlyStats->sum('rejected') / $monthlyStats->sum('total_requests')) * 100, 1) : 0 }}%
                </p>
            </div>
            <div class="bg-red-100 p-3 rounded-full">
                <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Overdue Items -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Overdue Items</p>
                <p class="text-3xl font-bold text-gray-800">{{ $overdueItems }}</p>
            </div>
            <div class="bg-yellow-100 p-3 rounded-full">
                <svg class="w-8 h-8 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Monthly Statistics Chart -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Monthly Request Statistics - {{ $year }}</h3>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Month</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Requests</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Approved</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rejected</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pending</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Approval Rate</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($monthlyStats as $stat)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm font-medium text-gray-900">
                            {{ date('F Y', strtotime($stat->month . '-01')) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm text-gray-900">{{ $stat->total_requests }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm text-green-600 font-medium">{{ $stat->approved }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm text-red-600 font-medium">{{ $stat->rejected }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm text-yellow-600 font-medium">{{ $stat->pending }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm text-gray-900">
                            @if($stat->total_requests > 0)
                                {{ round(($stat->approved / $stat->total_requests) * 100, 1) }}%
                            @else
                                0%
                            @endif
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                        No data available for the selected period.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Two Column Layout -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Top Requested Items -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Top Requested Items - {{ $year }}</h3>
        
        <div class="space-y-4">
            @forelse($topItems as $item)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div>
                    <p class="font-medium text-gray-800">{{ $item->name }}</p>
                    <p class="text-sm text-gray-600">{{ $item->request_count }} requests</p>
                </div>
                <div class="text-right">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                        {{ $item->total_requested }} units
                    </span>
                </div>
            </div>
            @empty
            <p class="text-center text-gray-400 italic py-4">No item data available</p>
            @endforelse
        </div>
    </div>

    <!-- Issuance Statistics -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Issuance Statistics - {{ $year }}</h3>
        
        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm text-gray-600 mb-1">Total Items Issued</p>
                    <p class="text-2xl font-bold text-gray-800">
                        {{ $issuanceStats->total_issued ?? 0 }}
                    </p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"/>
                        <path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
            
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm text-gray-600 mb-1">Total Items Returned</p>
                    <p class="text-2xl font-bold text-gray-800">
                        {{ $issuanceStats->total_returned ?? 0 }}
                    </p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
            
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm text-gray-600 mb-1">Total Issuances</p>
                    <p class="text-2xl font-bold text-gray-800">
                        {{ $issuanceStats->total_issuances ?? 0 }}
                    </p>
                </div>
                <div class="bg-purple-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
            
            @if($issuanceStats->total_issued > 0)
            <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                <p class="text-sm text-gray-600 mb-1">Return Rate</p>
                <p class="text-2xl font-bold text-gray-800">
                    {{ round(($issuanceStats->total_returned / $issuanceStats->total_issued) * 100, 1) }}%
                </p>
                <p class="text-xs text-gray-500 mt-1">
                    {{ $issuanceStats->total_returned }} of {{ $issuanceStats->total_issued }} items returned
                </p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Export Options -->
<div class="bg-white rounded-lg shadow-md p-6 mt-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Export Data</h3>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('admin.orders.export', ['type' => 'requests', 'year' => $year, 'month' => $month]) }}" 
           class="bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-lg p-4 text-center transition">
            <svg class="w-10 h-10 text-blue-600 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
            </svg>
            <p class="font-medium text-gray-800">Export Requests</p>
            <p class="text-sm text-gray-600">All request data as CSV</p>
        </a>
        
        <a href="{{ route('admin.orders.export', ['type' => 'issuances', 'year' => $year, 'month' => $month]) }}" 
           class="bg-green-50 hover:bg-green-100 border border-green-200 rounded-lg p-4 text-center transition">
            <svg class="w-10 h-10 text-green-600 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"/>
                <path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd"/>
            </svg>
            <p class="font-medium text-gray-800">Export Issuances</p>
            <p class="text-sm text-gray-600">All issuance records</p>
        </a>
        
        <a href="{{ route('admin.orders.export', ['type' => 'returns', 'year' => $year, 'month' => $month]) }}" 
           class="bg-purple-50 hover:bg-purple-100 border border-purple-200 rounded-lg p-4 text-center transition">
            <svg class="w-10 h-10 text-purple-600 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd"/>
            </svg>
            <p class="font-medium text-gray-800">Export Returns</p>
            <p class="text-sm text-gray-600">Item return records</p>
        </a>
    </div>
</div>

<!-- JavaScript for Charts (optional) -->
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // You can add Chart.js visualizations here if needed
    document.addEventListener('DOMContentLoaded', function() {
        // Example: Create a monthly requests chart
        const monthlyData = @json($monthlyStats);
        
        if (monthlyData.length > 0) {
            // Initialize chart here
            console.log('Monthly data available for charting');
        }
    });
</script>
@endpush
@endsection