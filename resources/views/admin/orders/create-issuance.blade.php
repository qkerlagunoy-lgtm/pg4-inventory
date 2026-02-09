{{-- resources/views/admin/orders/create-issuance.blade.php --}}
@extends('layouts.admin')

@section('title', 'Create Issuance')

@section('page-title', 'Create Issuance')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Create Issuance</h1>
                <p class="text-gray-600">Issue items for Request #{{ $request->id }}</p>
            </div>
            <a href="{{ route('admin.orders.approved') }}" class="text-sm text-gray-600 hover:text-gray-900">
                ← Back to Approved Requests
            </a>
        </div>
        
        <!-- Request Info Card -->
        <div class="mt-4 bg-white rounded-lg shadow p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Requested By</p>
                    <p class="font-medium">{{ $request->user->first_name }} {{ $request->user->last_name }}</p>
                    <p class="text-sm text-gray-600">{{ $request->user->unit }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Purpose</p>
                    <p class="font-medium">{{ $request->purpose }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Request Date</p>
                    <p class="font-medium">{{ $request->created_at->format('M d, Y h:i A') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Approved Date</p>
                    <p class="font-medium">{{ $request->approved_at->format('M d, Y h:i A') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Issuance Form -->
    <form action="{{ route('admin.orders.process-issuance', $request->id) }}" method="POST" id="issuanceForm">
        @csrf
        
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <!-- Issuance Details -->
            <div class="px-6 py-4 border-b">
                <h3 class="text-lg font-medium text-gray-900">Issuance Details</h3>
                <p class="text-sm text-gray-600">Enter issuance information and issue items</p>
            </div>

            <!-- Remarks Field -->
            <div class="px-6 py-4 border-b">
                <label for="remarks" class="block text-sm font-medium text-gray-700 mb-2">
                    Remarks (Optional)
                </label>
                <textarea 
                    name="remarks" 
                    id="remarks" 
                    rows="3" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Add any additional notes or instructions..."
                ></textarea>
                @error('remarks')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Items to Issue -->
            <div class="px-6 py-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Items to Issue</h3>
                    <div class="flex items-center space-x-2">
                        <button type="button" id="selectAll" class="text-sm text-blue-600 hover:text-blue-800">
                            Select All
                        </button>
                        <button type="button" id="deselectAll" class="text-sm text-gray-600 hover:text-gray-800">
                            Deselect All
                        </button>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <input type="checkbox" id="checkAll" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                </th>
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
                                    Issue Qty
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Due Date
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($request->requestItems as $index => $requestItem)
                                @php
                                    $item = $requestItem->item;
                                    $available = $item->quantity - $item->minimum_quantity;
                                    $canIssue = min($requestItem->quantity, $available);
                                @endphp
                                <tr class="{{ $available < $requestItem->quantity ? 'bg-yellow-50' : 'hover:bg-gray-50' }}">
                                    <!-- Checkbox -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input 
                                            type="checkbox" 
                                            name="issued_items[{{ $index }}][issue]" 
                                            value="1" 
                                            class="item-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                            data-max="{{ $canIssue }}"
                                            {{ $canIssue > 0 ? 'checked' : 'disabled' }}
                                        >
                                        <input type="hidden" name="issued_items[{{ $index }}][item_id]" value="{{ $item->id }}">
                                    </td>
                                    
                                    <!-- Item Name -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-gray-100 rounded-lg flex items-center justify-center">
                                                <svg class="h-6 w-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"/>
                                                    <path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $item->name }}
                                                    @if($item->serial_number)
                                                        <span class="text-xs text-gray-500">({{ $item->serial_number }})</span>
                                                    @endif
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $item->description ? Str::limit($item->description, 30) : 'No description' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <!-- Category -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">
                                            {{ $item->category->name ?? 'Uncategorized' }}
                                        </span>
                                    </td>
                                    
                                    <!-- Requested Quantity -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $requestItem->quantity }}
                                        {{ $item->unit_of_measure }}
                                    </td>
                                    
                                    <!-- Available Stock -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span class="text-sm font-medium {{ $available >= $requestItem->quantity ? 'text-green-600' : 'text-red-600' }}">
                                                {{ max(0, $available) }}
                                            </span>
                                            <span class="text-sm text-gray-500 ml-1">{{ $item->unit_of_measure }}</span>
                                            
                                            @if($available < $requestItem->quantity)
                                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                                    Shortage: {{ $requestItem->quantity - $available }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    
                                    <!-- Issue Quantity -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="relative">
                                            <input 
                                                type="number" 
                                                name="issued_items[{{ $index }}][quantity]" 
                                                value="{{ $canIssue > 0 ? $canIssue : 0 }}" 
                                                min="0" 
                                                max="{{ $canIssue }}"
                                                class="issue-quantity w-24 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                                {{ $canIssue > 0 ? '' : 'disabled' }}
                                            >
                                            <span class="ml-2 text-sm text-gray-500">{{ $item->unit_of_measure }}</span>
                                        </div>
                                        @error("issued_items.{$index}.quantity")
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </td>
                                    
                                    <!-- Due Date -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input 
                                            type="date" 
                                            name="issued_items[{{ $index }}][due_date]" 
                                            value="{{ old("issued_items.{$index}.due_date") }}"
                                            min="{{ date('Y-m-d') }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                            {{ $canIssue > 0 ? '' : 'disabled' }}
                                        >
                                        @error("issued_items.{$index}.due_date")
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Summary -->
                <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Total Items in Request</p>
                            <p class="text-lg font-bold text-gray-900">{{ $request->requestItems->count() }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Items Available to Issue</p>
                            <p class="text-lg font-bold text-green-600" id="availableItemsCount">
                                {{ $request->requestItems->where(function($item) {
                                    return ($item->item->quantity - $item->item->minimum_quantity) > 0;
                                })->count() }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Total Quantity to Issue</p>
                            <p class="text-lg font-bold text-blue-600" id="totalQuantity">0</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="px-6 py-4 bg-gray-50 border-t flex justify-end space-x-3">
                <a href="{{ route('admin.orders.approved') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancel
                </a>
                <button type="submit" 
                        id="submitBtn"
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed">
                    Process Issuance
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const checkAll = document.getElementById('checkAll');
    const itemCheckboxes = document.querySelectorAll('.item-checkbox');
    const quantityInputs = document.querySelectorAll('.issue-quantity');
    const totalQuantityEl = document.getElementById('totalQuantity');
    const availableItemsCountEl = document.getElementById('availableItemsCount');
    const submitBtn = document.getElementById('submitBtn');
    
    // Initialize
    updateSummary();
    
    // Select/Deselect All functionality
    document.getElementById('selectAll').addEventListener('click', function() {
        itemCheckboxes.forEach(cb => {
            if (!cb.disabled) {
                cb.checked = true;
                const index = getCheckboxIndex(cb);
                const quantityInput = document.querySelector(`input[name="issued_items[${index}][quantity]"]`);
                if (quantityInput && quantityInput.disabled) {
                    quantityInput.disabled = false;
                    quantityInput.value = quantityInput.max || 1;
                }
            }
        });
        checkAll.checked = true;
        updateSummary();
    });
    
    document.getElementById('deselectAll').addEventListener('click', function() {
        itemCheckboxes.forEach(cb => {
            cb.checked = false;
            const index = getCheckboxIndex(cb);
            const quantityInput = document.querySelector(`input[name="issued_items[${index}][quantity]"]`);
            if (quantityInput) quantityInput.disabled = true;
        });
        checkAll.checked = false;
        updateSummary();
    });
    
    // Check All checkbox
    checkAll.addEventListener('change', function() {
        itemCheckboxes.forEach(cb => {
            if (!cb.disabled) {
                cb.checked = this.checked;
                const index = getCheckboxIndex(cb);
                const quantityInput = document.querySelector(`input[name="issued_items[${index}][quantity]"]`);
                if (quantityInput) {
                    quantityInput.disabled = !this.checked;
                    if (this.checked && quantityInput.value == 0) {
                        quantityInput.value = quantityInput.max || 1;
                    }
                }
            }
        });
        updateSummary();
    });
    
    // Individual checkbox change
    itemCheckboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            const index = getCheckboxIndex(this);
            const quantityInput = document.querySelector(`input[name="issued_items[${index}][quantity]"]`);
            if (quantityInput) {
                quantityInput.disabled = !this.checked;
                if (this.checked && quantityInput.value == 0) {
                    quantityInput.value = quantityInput.max || 1;
                }
            }
            updateCheckAll();
            updateSummary();
        });
    });
    
    // Quantity input change
    quantityInputs.forEach(input => {
        input.addEventListener('input', function() {
            const max = parseInt(this.max);
            const value = parseInt(this.value) || 0;
            
            if (value > max) {
                this.value = max;
                showAlert(`Cannot issue more than ${max} units`, 'warning');
            } else if (value < 0) {
                this.value = 0;
            }
            
            updateSummary();
        });
    });
    
    // Helper functions
    function getCheckboxIndex(checkbox) {
        const name = checkbox.getAttribute('name');
        const match = name.match(/\[(\d+)\]/);
        return match ? match[1] : null;
    }
    
    function updateCheckAll() {
        const enabledCheckboxes = Array.from(itemCheckboxes).filter(cb => !cb.disabled);
        const checkedCheckboxes = Array.from(itemCheckboxes).filter(cb => cb.checked && !cb.disabled);
        checkAll.checked = enabledCheckboxes.length > 0 && enabledCheckboxes.length === checkedCheckboxes.length;
        checkAll.indeterminate = checkedCheckboxes.length > 0 && checkedCheckboxes.length < enabledCheckboxes.length;
    }
    
    function updateSummary() {
        let totalQuantity = 0;
        let selectedItems = 0;
        
        itemCheckboxes.forEach(cb => {
            if (cb.checked && !cb.disabled) {
                const index = getCheckboxIndex(cb);
                const quantityInput = document.querySelector(`input[name="issued_items[${index}][quantity]"]`);
                if (quantityInput) {
                    const quantity = parseInt(quantityInput.value) || 0;
                    totalQuantity += quantity;
                    selectedItems++;
                }
            }
        });
        
        // Update display
        totalQuantityEl.textContent = totalQuantity;
        
        // Update submit button state
        const hasSelectedItems = selectedItems > 0;
        submitBtn.disabled = !hasSelectedItems;
        
        // Update available items count
        const availableItems = Array.from(itemCheckboxes).filter(cb => !cb.disabled).length;
        availableItemsCountEl.textContent = availableItems;
    }
    
    function showAlert(message, type = 'info') {
        // Remove existing alerts
        const existingAlert = document.querySelector('.custom-alert');
        if (existingAlert) existingAlert.remove();
        
        // Create alert
        const alert = document.createElement('div');
        alert.className = `custom-alert fixed top-4 right-4 px-4 py-3 rounded-md shadow-lg z-50 ${
            type === 'warning' ? 'bg-yellow-100 border-yellow-400 text-yellow-700' : 
            type === 'error' ? 'bg-red-100 border-red-400 text-red-700' : 
            'bg-blue-100 border-blue-400 text-blue-700'
        } border`;
        alert.innerHTML = `
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(alert);
        
        // Auto remove after 3 seconds
        setTimeout(() => alert.remove(), 3000);
    }
    
    // Form validation
    document.getElementById('issuanceForm').addEventListener('submit', function(e) {
        let isValid = true;
        let hasIssuance = false;
        
        itemCheckboxes.forEach(cb => {
            if (cb.checked && !cb.disabled) {
                hasIssuance = true;
                const index = getCheckboxIndex(cb);
                const quantityInput = document.querySelector(`input[name="issued_items[${index}][quantity]"]`);
                if (quantityInput && (parseInt(quantityInput.value) || 0) <= 0) {
                    isValid = false;
                    showAlert('Issued quantity must be greater than 0 for selected items', 'error');
                }
            }
        });
        
        if (!hasIssuance) {
            e.preventDefault();
            showAlert('Please select at least one item to issue', 'error');
            return false;
        }
        
        if (!isValid) {
            e.preventDefault();
            return false;
        }
        
        // Show loading state
        submitBtn.innerHTML = `
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Processing...
        `;
        submitBtn.disabled = true;
    });
});
</script>
@endpush

@push('styles')
<style>
.custom-alert {
    animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
    opacity: 1;
}
</style>
@endpush