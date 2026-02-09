{{-- resources/views/admin/orders/view-issuance.blade.php --}}
@extends('layouts.admin')

@section('title', 'View Issuance')

@section('page-title', 'Issuance Details')

@section('content')
<div class="space-y-6">
    <!-- Issuance Header Card -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Issuance #{{ $issuance->issuance_code ?? $issuance->id }}</h2>
                    <p class="text-gray-600">Issued on {{ $issuance->issued_at->format('F d, Y h:i A') }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="px-4 py-2 rounded-full text-sm font-semibold 
                        @if($issuance->status == 'completed') bg-green-100 text-green-800
                        @elseif($issuance->status == 'partially_issued') bg-yellow-100 text-yellow-800
                        @elseif($issuance->status == 'pending') bg-blue-100 text-blue-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ ucfirst(str_replace('_', ' ', $issuance->status)) }}
                    </span>
                    
                    @if($issuance->canReturnItems())
                    <button onclick="showReturnModal()" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Process Return
                    </button>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Request Information -->
                <div class="space-y-4">
                    <h3 class="font-semibold text-gray-700">Request Information</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-500">Request ID</p>
                            <p class="font-medium">#{{ $issuance->itemRequest->id }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Requester</p>
                            <p class="font-medium">{{ $issuance->itemRequest->user->name }}</p>
                            <p class="text-sm text-gray-600">{{ $issuance->itemRequest->user->unit }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Purpose</p>
                            <p class="font-medium">{{ $issuance->itemRequest->purpose }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Issuance Information -->
                <div class="space-y-4">
                    <h3 class="font-semibold text-gray-700">Issuance Details</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-500">Issued By</p>
                            <p class="font-medium">{{ $issuance->issuer->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Issuance Date</p>
                            <p class="font-medium">{{ $issuance->issued_at->format('F d, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Total Items</p>
                            <p class="font-medium">{{ $issuance->issuanceItems->count() }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Status & Actions -->
                <div class="space-y-4">
                    <h3 class="font-semibold text-gray-700">Status Overview</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Issued Items</span>
                            <span class="font-medium">{{ $issuance->totalItemsIssued() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Returned Items</span>
                            <span class="font-medium">{{ $issuance->totalItemsReturned() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Pending Returns</span>
                            <span class="font-medium">{{ $issuance->pendingReturnsCount() }}</span>
                        </div>
                        @if($issuance->remarks)
                        <div>
                            <p class="text-sm text-gray-500">Remarks</p>
                            <p class="font-medium">{{ $issuance->remarks }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Issued Items Table -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Issued Items</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requested Qty</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Issued Qty</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Returned Qty</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($issuance->issuanceItems as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $item->item->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $item->item->item_code }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                {{ $item->item->category->name ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $item->requested_quantity ?? $item->quantity_issued }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $item->quantity_issued }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $item->quantity_returned ?? '0' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($item->due_date)
                                <span class="{{ $item->isOverdue() ? 'text-red-600 font-semibold' : '' }}">
                                    {{ $item->due_date->format('M d, Y') }}
                                    @if($item->isOverdue())
                                        <span class="text-xs text-red-500">(Overdue)</span>
                                    @endif
                                </span>
                            @else
                                <span class="text-gray-400">No due date</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                @if($item->status == 'returned') bg-green-100 text-green-800
                                @elseif($item->status == 'issued') bg-blue-100 text-blue-800
                                @elseif($item->status == 'lost') bg-red-100 text-red-800
                                @elseif($item->status == 'damaged') bg-orange-100 text-orange-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            @if($item->status == 'issued')
                            <button onclick="showItemReturnModal({{ $item->id }})" 
                                    class="text-blue-600 hover:text-blue-900 mr-3">
                                Return Item
                            </button>
                            @endif
                            
                            @if($item->quantity_returned > 0)
                            <button onclick="showReturnDetails({{ $item->id }})" 
                                    class="text-gray-600 hover:text-gray-900">
                                View Details
                            </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                @if($issuance->issuanceItems->count() > 0)
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="3" class="px-6 py-3 text-sm font-semibold text-gray-900 text-right">Totals:</td>
                        <td class="px-6 py-3 text-sm font-semibold text-gray-900">
                            {{ $issuance->issuanceItems->sum('quantity_issued') }}
                        </td>
                        <td class="px-6 py-3 text-sm font-semibold text-gray-900">
                            {{ $issuance->issuanceItems->sum('quantity_returned') }}
                        </td>
                        <td colspan="3"></td>
                    </tr>
                </tfoot>
                @endif
            </table>
            
            @if($issuance->issuanceItems->isEmpty())
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No items issued</h3>
                <p class="mt-1 text-sm text-gray-500">No items have been issued for this request.</p>
            </div>
            @endif
        </div>
    </div>
    
    <!-- Activity Log -->
    @if($issuance->activityLogs && $issuance->activityLogs->count() > 0)
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Activity Log</h3>
        </div>
        <div class="p-6">
            <div class="flow-root">
                <ul role="list" class="-mb-8">
                    @foreach($issuance->activityLogs as $log)
                    <li>
                        <div class="relative pb-8">
                            @if(!$loop->last)
                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                            @endif
                            <div class="relative flex space-x-3">
                                <div>
                                    <span class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center ring-8 ring-white">
                                        <svg class="h-5 w-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                            @switch($log->type)
                                                @case('created')
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"/>
                                                    @break
                                                @case('updated')
                                                    <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                                                    @break
                                                @default
                                                    <path fill-rule="evenodd" d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                            @endswitch
                                        </svg>
                                    </span>
                                </div>
                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                    <div>
                                        <p class="text-sm text-gray-700">{{ $log->description }}</p>
                                    </div>
                                    <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                        <time datetime="{{ $log->created_at }}">{{ $log->created_at->diffForHumans() }}</time>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Action Buttons -->
    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="flex justify-between">
            <div>
                <a href="{{ route('admin.orders.issuances') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-200 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Issuances
                </a>
            </div>
            <div class="flex gap-3">
                @if($issuance->status != 'completed')
                <form action="{{ route('admin.orders.complete-issuance', $issuance->id) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" 
                            onclick="return confirm('Mark this issuance as completed?')"
                            class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-lg text-white hover:bg-green-700 transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Mark as Completed
                    </button>
                </form>
                @endif
                
                <button onclick="window.print()" 
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg text-white hover:bg-blue-700 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Print Issuance
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Return Modal -->
<div id="returnModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
        <div class="bg-white rounded-lg shadow-xl max-w-lg w-full p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Process Item Return</h3>
            
            <form id="returnForm" method="POST" action="">
                @csrf
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Item</label>
                        <select id="itemSelect" name="item_id" class="mt-1 block w-full border border-gray-300 rounded-lg p-2">
                            @foreach($issuance->issuanceItems->where('status', 'issued') as $item)
                            <option value="{{ $item->id }}">{{ $item->item->name }} (Issued: {{ $item->quantity_issued }})</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Returned Quantity</label>
                        <input type="number" name="returned_quantity" min="1" class="mt-1 block w-full border border-gray-300 rounded-lg p-2">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Condition</label>
                        <select name="condition" class="mt-1 block w-full border border-gray-300 rounded-lg p-2">
                            <option value="good">Good</option>
                            <option value="damaged">Damaged</option>
                            <option value="lost">Lost</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Notes</label>
                        <textarea name="notes" rows="3" class="mt-1 block w-full border border-gray-300 rounded-lg p-2"></textarea>
                    </div>
                </div>
                
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="closeReturnModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Process Return
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Return Details Modal -->
<div id="returnDetailsModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden overflow-y-auto">
    <!-- Similar modal structure for viewing return details -->
</div>
@endsection

@push('scripts')
<script>
function showReturnModal() {
    document.getElementById('returnModal').classList.remove('hidden');
    document.getElementById('returnForm').action = "{{ route('admin.orders.process-return', ['id' => '__ITEM_ID__']) }}";
}

function showItemReturnModal(itemId) {
    document.getElementById('returnModal').classList.remove('hidden');
    document.getElementById('itemSelect').value = itemId;
    document.getElementById('returnForm').action = "{{ route('admin.orders.process-return', ['id' => '__ITEM_ID__']) }}".replace('__ITEM_ID__', itemId);
}

function closeReturnModal() {
    document.getElementById('returnModal').classList.add('hidden');
}

function showReturnDetails(itemId) {
    // Fetch and show return details via AJAX
    fetch(`/api/issuance-items/${itemId}/return-details`)
        .then(response => response.json())
        .then(data => {
            // Populate and show return details modal
            document.getElementById('returnDetailsModal').classList.remove('hidden');
        });
}

// Close modals when clicking outside
document.addEventListener('click', function(event) {
    const returnModal = document.getElementById('returnModal');
    const detailsModal = document.getElementById('returnDetailsModal');
    
    if (returnModal && !returnModal.contains(event.target) && event.target.closest('button')?.onclick?.name !== 'showReturnModal') {
        returnModal.classList.add('hidden');
    }
    
    if (detailsModal && !detailsModal.contains(event.target) && event.target.closest('button')?.onclick?.name !== 'showReturnDetails') {
        detailsModal.classList.add('hidden');
    }
});
</script>
@endpush

@push('styles')
<style>
    @media print {
        header, footer, .no-print {
            display: none !important;
        }
        
        body {
            font-size: 12pt;
        }
        
        .print-content {
            margin: 0;
            padding: 0;
        }
    }
</style>
@endpush