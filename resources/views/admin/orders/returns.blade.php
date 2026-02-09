@extends('layouts.admin')

@section('title', 'Item Returns Management')

@section('page-title', 'Item Returns & Tracking')

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

    <!-- Header with Stats -->
    <div class="mb-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Item Returns Management</h1>
                <p class="text-gray-600">Track and manage issued items that need to be returned</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                    Total Items: {{ $issuanceItems->total() }}
                </span>
                @php
                    $overdueCount = collect($issuanceItems->items())->filter(function($item) {
                        return $item->due_date && $item->due_date->lt(now());
                    })->count();
                @endphp
                <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-medium">
                    Overdue: {{ $overdueCount }}
                </span>
            </div>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('admin.orders.returns') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Search -->
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" 
                       name="search" 
                       id="search" 
                       value="{{ request('search') }}"
                       placeholder="Search by item name or user..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
            </div>

            <!-- Status Filter -->
            <div>
                <label for="overdue" class="block text-sm font-medium text-gray-700 mb-1">Filter</label>
                <select name="overdue" 
                        id="overdue" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    <option value="">All Items</option>
                    <option value="1" {{ request('overdue') == '1' ? 'selected' : '' }}>Overdue Items Only</option>
                    <option value="0" {{ request('overdue') == '0' ? 'selected' : '' }}>Not Overdue</option>
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-end gap-2">
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                    </svg>
                    Apply Filters
                </button>
                <a href="{{ route('admin.orders.returns') }}" 
                   class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Returns Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        @if($issuanceItems->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Item Details
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Issued To
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Quantity
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Due Date
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
                        @foreach($issuanceItems as $item)
                            @php
                                $isOverdue = $item->due_date && $item->due_date->lt(now());
                                $daysLeft = $item->due_date ? now()->diffInDays($item->due_date, false) : null;
                            @endphp
                            <tr class="hover:bg-gray-50 transition">
                                <!-- Item Details -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <svg class="h-6 w-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"/>
                                                <path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $item->item->name }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                Issuance #{{ $item->issuance->id }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Issued To -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $item->issuance->itemRequest->user->first_name }} 
                                        {{ $item->issuance->itemRequest->user->last_name }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $item->issuance->itemRequest->user->unit ?? 'N/A' }}
                                    </div>
                                </td>

                                <!-- Quantity -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        <span class="font-medium">{{ $item->quantity_issued }}</span> issued
                                        @if($item->quantity_returned)
                                            <span class="text-green-600 ml-2">
                                                ({{ $item->quantity_returned }} returned)
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Due Date -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if($item->due_date)
                                            <div class="{{ $isOverdue ? 'text-red-600' : 'text-gray-900' }}">
                                                <div class="text-sm font-medium">
                                                    {{ $item->due_date->format('M d, Y') }}
                                                </div>
                                                <div class="text-xs {{ $isOverdue ? 'text-red-500' : 'text-gray-500' }}">
                                                    @if($isOverdue)
                                                        <span class="flex items-center">
                                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                            </svg>
                                                            Overdue by {{ abs($daysLeft) }} days
                                                        </span>
                                                    @else
                                                        Due in {{ $daysLeft }} days
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-sm text-gray-400">No due date</span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Status -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($item->status === 'issued')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $isOverdue ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ $isOverdue ? 'Overdue' : 'Pending Return' }}
                                        </span>
                                    @elseif($item->status === 'returned')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Returned
                                        </span>
                                    @elseif($item->status === 'lost')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Lost
                                        </span>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    @if($item->status === 'issued')
                                        <!-- Return Item Modal Trigger -->
                                        <button type="button" 
                                                onclick="openReturnModal({{ json_encode([
                                                    'id' => $item->id,
                                                    'item_name' => $item->item->name,
                                                    'quantity_issued' => $item->quantity_issued,
                                                    'user_name' => $item->issuance->itemRequest->user->first_name . ' ' . $item->issuance->itemRequest->user->last_name,
                                                    'issuance_id' => $item->issuance->id
                                                ]) }})"
                                                class="text-blue-600 hover:text-blue-900 mr-3">
                                            Process Return
                                        </button>
                                    @endif
                                    
                                    <a href="{{ route('admin.orders.issuances.view', $item->issuance->id) }}" 
                                       class="text-gray-600 hover:text-gray-900">
                                        View Issuance
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $issuanceItems->withQueryString()->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No items pending return</h3>
                <p class="mt-1 text-sm text-gray-500">
                    All issued items have been returned or don't have return requirements.
                </p>
            </div>
        @endif
    </div>

    <!-- Return Item Modal -->
    <div id="returnModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden z-50 transition-opacity">
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                    <div class="absolute right-0 top-0 pr-4 pt-4">
                        <button type="button" onclick="closeReturnModal()" class="rounded-md bg-white text-gray-400 hover:text-gray-500">
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">
                                Process Item Return
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Item: <span id="modal-item-name" class="font-medium"></span><br>
                                    Issued to: <span id="modal-user-name" class="font-medium"></span><br>
                                    Issued quantity: <span id="modal-quantity" class="font-medium"></span>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <form id="returnForm" method="POST" class="mt-5">
                        @csrf
                        <input type="hidden" name="_method" value="POST">
                        
                        <!-- Returned Quantity -->
                        <div class="mb-4">
                            <label for="returned_quantity" class="block text-sm font-medium text-gray-700 mb-1">
                                Returned Quantity *
                            </label>
                            <input type="number" 
                                   name="returned_quantity" 
                                   id="returned_quantity"
                                   min="1"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            <p class="mt-1 text-xs text-gray-500">
                                Maximum: <span id="max-quantity" class="font-medium"></span>
                            </p>
                        </div>
                        
                        <!-- Condition -->
                        <div class="mb-4">
                            <label for="condition" class="block text-sm font-medium text-gray-700 mb-1">
                                Item Condition *
                            </label>
                            <select name="condition" 
                                    id="condition" 
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                <option value="">Select condition...</option>
                                <option value="good">Good - Restock to inventory</option>
                                <option value="damaged">Damaged - Do not restock</option>
                                <option value="lost">Lost</option>
                            </select>
                        </div>
                        
                        <!-- Notes -->
                        <div class="mb-6">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
                                Notes
                            </label>
                            <textarea name="notes" 
                                      id="notes" 
                                      rows="3"
                                      placeholder="Any additional notes about the return..."
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"></textarea>
                        </div>
                        
                        <!-- Form Actions -->
                        <div class="flex justify-end gap-3">
                            <button type="button" 
                                    onclick="closeReturnModal()"
                                    class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition">
                                Cancel
                            </button>
                            <button type="submit" 
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                Process Return
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    let currentItemId = null;
    let maxQuantity = 0;
    
    function openReturnModal(itemData) {
        currentItemId = itemData.id;
        maxQuantity = itemData.quantity_issued;
        
        // Update modal content
        document.getElementById('modal-item-name').textContent = itemData.item_name;
        document.getElementById('modal-user-name').textContent = itemData.user_name;
        document.getElementById('modal-quantity').textContent = itemData.quantity_issued;
        document.getElementById('max-quantity').textContent = itemData.quantity_issued;
        
        // Set form action
        const form = document.getElementById('returnForm');
        form.action = `{{ url('admin/orders/process-return') }}/${itemData.id}`;
        
        // Reset form
        form.reset();
        document.getElementById('returned_quantity').max = maxQuantity;
        document.getElementById('returned_quantity').value = maxQuantity;
        
        // Show modal
        document.getElementById('returnModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }
    
    function closeReturnModal() {
        document.getElementById('returnModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        currentItemId = null;
    }
    
    // Close modal when clicking outside
    document.getElementById('returnModal').addEventListener('click', function(e) {
        if (e.target.id === 'returnModal') {
            closeReturnModal();
        }
    });
    
    // Validate quantity before form submission
    document.getElementById('returnForm').addEventListener('submit', function(e) {
        const quantity = parseInt(document.getElementById('returned_quantity').value);
        const condition = document.getElementById('condition').value;
        
        if (!quantity || quantity < 1 || quantity > maxQuantity) {
            e.preventDefault();
            alert(`Please enter a valid quantity between 1 and ${maxQuantity}`);
            return false;
        }
        
        if (!condition) {
            e.preventDefault();
            alert('Please select the item condition');
            return false;
        }
        
        // Confirm for lost items
        if (condition === 'lost') {
            if (!confirm('Marking this item as LOST. This cannot be undone. Continue?')) {
                e.preventDefault();
                return false;
            }
        }
    });
</script>
@endsection

@push('styles')
<style>
    /* Custom styles for overdue items */
    .overdue-row {
        background-color: rgba(254, 226, 226, 0.3);
    }
    .overdue-row:hover {
        background-color: rgba(254, 226, 226, 0.5);
    }
</style>
@endpush