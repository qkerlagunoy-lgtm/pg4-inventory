{{-- resources/views/admin/orders/review.blade.php --}}
@extends('layouts.admin')

@section('title', 'Review Request #' . $request->id)

@section('page-title', 'Review Request')

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('admin.orders.index') }}">Order Management</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('admin.orders.pending') }}">Pending Requests</a>
    </li>
    <li class="breadcrumb-item active">Review Request #{{ $request->id }}</li>
@endsection

@section('content')
    <!-- Request Details Card -->
    <div class="bg-white rounded-lg shadow-md mb-6">
        <div class="p-6 border-b">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Request #{{ $request->id }}</h2>
                    <p class="text-gray-600 mt-1">Submitted {{ $request->created_at->format('M d, Y h:i A') }}</p>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="px-4 py-2 rounded-full text-sm font-semibold 
                        @if($request->priority == 'high') bg-red-100 text-red-800
                        @elseif($request->priority == 'medium') bg-yellow-100 text-yellow-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ ucfirst($request->priority) }} Priority
                    </span>
                    <span class="px-4 py-2 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800">
                        Pending Review
                    </span>
                </div>
            </div>
        </div>

        <!-- Requester Information -->
        <div class="p-6 border-b">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Requester Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Name</p>
                    <p class="font-medium text-gray-800">{{ $request->user->full_name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Email</p>
                    <p class="font-medium text-gray-800">{{ $request->user->email }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Unit/Department</p>
                    <p class="font-medium text-gray-800">{{ $request->user->unit ?? 'Not specified' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Phone</p>
                    <p class="font-medium text-gray-800">{{ $request->user->phone ?? 'Not provided' }}</p>
                </div>
            </div>
        </div>

        <!-- Request Details -->
        <div class="p-6 border-b">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Request Details</h3>
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-500">Purpose</p>
                    <p class="font-medium text-gray-800">{{ $request->purpose }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Required Date</p>
                    <p class="font-medium text-gray-800">
                        {{ $request->required_date ? $request->required_date->format('M d, Y') : 'Not specified' }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Special Instructions</p>
                    <p class="font-medium text-gray-800">{{ $request->notes ?? 'None' }}</p>
                </div>
            </div>
        </div>

        <!-- Requested Items Table -->
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Requested Items</h3>
            
            <!-- Stock Availability Warning -->
            @if(count($availabilityIssues) > 0)
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-medium text-red-800">Stock Availability Issues Detected</span>
                    </div>
                    <p class="text-sm text-red-600 mt-1">
                        Some items have insufficient stock. You may need to issue partial quantities or reject the request.
                    </p>
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Item
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Category
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Requested Qty
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Available Stock
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Remarks
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($request->requestItems as $item)
                            @php
                                $available = $item->item->quantity - $item->item->minimum_quantity;
                                $shortage = max(0, $item->quantity - $available);
                                $hasIssue = $available < $item->quantity;
                            @endphp
                            <tr class="{{ $hasIssue ? 'bg-red-50' : 'hover:bg-gray-50' }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $item->item->name }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $item->item->code }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">
                                        {{ $item->item->category->name ?? 'Uncategorized' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $item->quantity }} {{ $item->item->unit }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="text-sm font-medium 
                                            {{ $available >= $item->quantity ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $available }} {{ $item->item->unit }}
                                        </span>
                                        @if($hasIssue)
                                            <span class="ml-2 px-2 py-1 text-xs bg-red-100 text-red-800 rounded">
                                                Shortage: {{ $shortage }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        @if($hasIssue) bg-red-100 text-red-800
                                        @else bg-green-100 text-green-800 @endif">
                                        @if($hasIssue)
                                            Insufficient Stock
                                        @else
                                            Available
                                        @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $item->notes ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="3" class="px-6 py-3 text-sm font-medium text-gray-900">
                                Total Items: {{ $request->requestItems->count() }}
                            </td>
                            <td colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-900">
                                Total Quantity: {{ $request->requestItems->sum('quantity') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <!-- Back Button -->
            <a href="{{ route('admin.orders.pending') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                </svg>
                Back to Pending Requests
            </a>

            <div class="flex flex-col md:flex-row gap-4">
                <!-- Reject Form -->
                <div class="md:w-64">
                    <form id="rejectForm" action="{{ route('admin.orders.reject', $request->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-1">
                                Rejection Reason
                            </label>
                            <textarea 
                                id="rejection_reason" 
                                name="rejection_reason" 
                                rows="2"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500"
                                placeholder="Please provide a reason for rejection..."
                                required></textarea>
                        </div>
                        <button type="submit" 
                                onclick="return confirm('Are you sure you want to reject this request?')"
                                class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                            Reject Request
                        </button>
                    </form>
                </div>

                <!-- Approve Form -->
                <div class="md:w-64">
                    <form id="approveForm" action="{{ route('admin.orders.approve', $request->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="scheduled_date" class="block text-sm font-medium text-gray-700 mb-1">
                                Scheduled Issue Date (Optional)
                            </label>
                            <input type="date" 
                                   id="scheduled_date" 
                                   name="scheduled_date"
                                   min="{{ date('Y-m-d') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                        </div>
                        <div class="mb-3">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
                                Notes (Optional)
                            </label>
                            <textarea 
                                id="notes" 
                                name="notes" 
                                rows="2"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                placeholder="Add any notes or instructions..."></textarea>
                        </div>
                        
                        @if(count($availabilityIssues) > 0)
                            <div class="mb-3 p-3 bg-yellow-50 border border-yellow-200 rounded-md">
                                <p class="text-sm text-yellow-700">
                                    <strong>Note:</strong> Some items have insufficient stock. 
                                    You can still approve and issue partial quantities later.
                                </p>
                            </div>
                        @endif
                        
                        <button type="submit" 
                                onclick="return confirm('Are you sure you want to approve this request?')"
                                class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Approve Request
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Issues Details -->
    @if(count($availabilityIssues) > 0)
        <div class="bg-white rounded-lg shadow-md mt-6 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Stock Availability Details</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-red-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-red-800 uppercase tracking-wider">
                                Item
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-red-800 uppercase tracking-wider">
                                Requested
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-red-800 uppercase tracking-wider">
                                Available
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-red-800 uppercase tracking-wider">
                                Shortage
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-red-800 uppercase tracking-wider">
                                Status
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($availabilityIssues as $issue)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $issue['item']->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $issue['item']->code }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $issue['requested'] }} {{ $issue['item']->unit }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $issue['available'] }} {{ $issue['item']->unit }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        {{ $issue['shortage'] }} {{ $issue['item']->unit }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        Insufficient Stock
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4 text-sm text-gray-600">
                <p><strong>Recommendation:</strong> 
                    @if(count($availabilityIssues) == $request->requestItems->count())
                        All items have stock issues. Consider rejecting the request or requesting restocking.
                    @else
                        Some items can be issued. You can approve the request and issue partial quantities later.
                    @endif
                </p>
            </div>
        </div>
    @endif

    <!-- JavaScript for form validation -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Set minimum date for scheduled date
            const today = new Date().toISOString().split('T')[0];
            const scheduledDateInput = document.getElementById('scheduled_date');
            if (scheduledDateInput) {
                scheduledDateInput.min = today;
            }

            // Form validation
            const rejectForm = document.getElementById('rejectForm');
            const approveForm = document.getElementById('approveForm');

            if (rejectForm) {
                rejectForm.addEventListener('submit', function(e) {
                    const reason = document.getElementById('rejection_reason').value.trim();
                    if (!reason) {
                        e.preventDefault();
                        alert('Please provide a reason for rejection.');
                        return false;
                    }
                });
            }
        });
    </script>
@endsection

@section('styles')
<style>
    .breadcrumb-item + .breadcrumb-item::before {
        content: ">";
        color: #6b7280;
    }
    .breadcrumb-item a:hover {
        color: #3b82f6;
    }
</style>
@endsection