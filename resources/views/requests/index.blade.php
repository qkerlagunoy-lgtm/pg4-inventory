@extends('layouts.user')

@section('title', 'Request Items')

@section('page-title', 'Request Items')

@section('breadcrumb')
    <nav class="mb-4">
        <ol class="flex items-center space-x-2 text-sm">
            <li><a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700">Dashboard</a></li>
            <li><svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </li>
            <li class="text-blue-600 font-medium">Request Items</li>
        </ol>
    </nav>
@endsection

@section('header-actions')
    @php
        $cart = session()->get('cart', []);
        $cartCount = count($cart);
        $cartTotal = array_sum(array_column($cart, 'quantity'));
    @endphp
    <a href="{{ route('requests.cart') }}" 
       class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition flex items-center gap-2">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
        </svg>
        View Cart
        @if($cartCount > 0)
            <span class="px-2 py-1 text-xs font-bold bg-white text-yellow-700 rounded-full">{{ $cartTotal }}</span>
        @endif
    </a>
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

    <!-- Search and Filter Card -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <label for="searchInput" class="block text-sm font-medium text-gray-700 mb-2">Search Items</label>
                <div class="relative">
                    <input type="text" id="searchInput" placeholder="Search by name, category, or description..." 
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>
            <div class="flex items-end">
                <button onclick="resetSearch()" 
                        class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Reset
                </button>
            </div>
        </div>
        
        <!-- Quick Filters -->
        <div class="flex flex-wrap gap-2 mt-4 pt-4 border-t border-gray-200">
            <span class="text-sm text-gray-600 mr-2 py-1">Quick filters:</span>
            <button onclick="filterByStatus('available')" class="px-3 py-1 text-xs bg-green-100 text-green-700 rounded-full hover:bg-green-200 transition">In Stock</button>
            <button onclick="filterByStatus('low')" class="px-3 py-1 text-xs bg-yellow-100 text-yellow-700 rounded-full hover:bg-yellow-200 transition">Low Stock</button>
            <button onclick="filterByStatus('expiring')" class="px-3 py-1 text-xs bg-red-100 text-red-700 rounded-full hover:bg-red-200 transition">Expiring Soon</button>
            <button onclick="resetSearch()" class="px-3 py-1 text-xs bg-gray-100 text-gray-600 rounded-full hover:bg-gray-200 transition">Clear All</button>
        </div>
    </div>

    <!-- Items Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200" id="itemsTable">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Available</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($items as $item)
                        <tr class="hover:bg-gray-50 transition"
                            data-category="{{ $item->category->name ?? 'Uncategorized' }}"
                            data-name="{{ $item->name }}"
                            data-description="{{ $item->description ?? '' }}"
                            data-stock="{{ $item->quantity }}"
                            data-minimum="{{ $item->minimum_quantity }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                    {{ $item->category->name ?? 'Uncategorized' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $item->name }}</div>
                                @if($item->storage_location)
                                    <div class="text-xs text-gray-500">📍 {{ $item->storage_location }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-600 max-w-xs truncate">
                                    {{ $item->description ?? 'No description' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 font-medium">{{ $item->quantity }} {{ $item->unit }}</div>
                                <div class="text-xs text-gray-500">Min: {{ $item->minimum_quantity }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="space-y-1">
                                    @if($item->quantity == 0)
                                        <span class="px-2 py-1 text-xs bg-gray-100 text-gray-800 rounded-full">Out of Stock</span>
                                    @elseif($item->quantity <= $item->minimum_quantity)
                                        <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded-full">Low Stock</span>
                                    @else
                                        <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">In Stock</span>
                                    @endif
                                    
                                    @if($item->isExpiringSoon(30))
                                        <span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">Expiring Soon</span>
                                    @endif
                                    @if($item->isExpired())
                                        <span class="px-2 py-1 text-xs bg-gray-100 text-gray-800 rounded-full">Expired</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($item->quantity > 0)
                                    <button onclick="openAddToCartModal({{ $item->id }}, '{{ $item->name }}', {{ $item->quantity }}, '{{ $item->unit }}')" 
                                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        Add to Cart
                                    </button>
                                @else
                                    <button disabled 
                                            class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-500 text-sm font-medium rounded-lg cursor-not-allowed">
                                        Out of Stock
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No items available</h3>
                                <p class="mt-1 text-sm text-gray-500">Check back later for new inventory items.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination (if needed) -->
        @if(method_exists($items, 'links'))
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $items->links() }}
            </div>
        @endif
    </div>
@endsection

@push('modals')
<!-- Add to Cart Modal -->
<div id="addToCartModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 mb-4">
                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 text-center" id="modalItemName">
                Add to Cart
            </h3>
            <div class="mt-2 px-7 py-3">
                <form id="addToCartForm" method="POST" action="{{ route('requests.cart.add') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" id="modalItemId" name="item_id">
                    
                    <div>
                        <label for="modalQuantity" class="block text-sm font-medium text-gray-700 mb-1">
                            Quantity *
                        </label>
                        <div class="flex items-center space-x-3">
                            <input type="number" id="modalQuantity" name="quantity" min="1" 
                                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   required>
                            <div class="w-20 text-center">
                                <span class="text-sm text-gray-500" id="modalUnit">—</span>
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500" id="availableStock">Available: <span id="availableQty">0</span></p>
                    </div>
                    
                    <div>
                        <label for="modalNotes" class="block text-sm font-medium text-gray-700 mb-1">
                            Notes (Optional)
                        </label>
                        <textarea id="modalNotes" name="notes" rows="2"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Add any specific requirements..."></textarea>
                    </div>
                    
                    <div class="flex justify-center space-x-3 pt-2">
                        <button type="button" 
                                onclick="closeModal()"
                                class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-400">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-blue-700">
                            Add to Cart
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
    const searchInput = document.getElementById('searchInput');
    const table = document.getElementById('itemsTable');
    const tbody = table?.getElementsByTagName('tbody')[0];
    const rows = tbody ? tbody.getElementsByTagName('tr') : [];

    // Search functionality
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const filter = searchInput.value.toLowerCase();

            for (let i = 0; i < rows.length; i++) {
                const category = rows[i].getAttribute('data-category')?.toLowerCase() || '';
                const name = rows[i].getAttribute('data-name')?.toLowerCase() || '';
                const description = rows[i].getAttribute('data-description')?.toLowerCase() || '';
                
                const found = category.includes(filter) || name.includes(filter) || description.includes(filter);
                rows[i].style.display = found ? '' : 'none';
            }
        });
    }

    // Filter by status
    window.filterByStatus = function(status) {
        for (let i = 0; i < rows.length; i++) {
            const stock = parseInt(rows[i].getAttribute('data-stock') || '0');
            const minimum = parseInt(rows[i].getAttribute('data-minimum') || '0');
            
            let show = false;
            
            switch(status) {
                case 'available':
                    show = stock > minimum;
                    break;
                case 'low':
                    show = stock <= minimum && stock > 0;
                    break;
                case 'expiring':
                    // This would need actual expiring data
                    show = false;
                    break;
                default:
                    show = true;
            }
            
            rows[i].style.display = show ? '' : 'none';
        }
    };

    // Reset search
    window.resetSearch = function() {
        if (searchInput) {
            searchInput.value = '';
        }
        for (let i = 0; i < rows.length; i++) {
            rows[i].style.display = '';
        }
    };

    // Modal functions
    const modal = document.getElementById('addToCartModal');
    const modalItemName = document.getElementById('modalItemName');
    const modalItemId = document.getElementById('modalItemId');
    const modalQuantity = document.getElementById('modalQuantity');
    const modalUnit = document.getElementById('modalUnit');
    const availableQty = document.getElementById('availableQty');

    window.openAddToCartModal = function(itemId, itemName, available, unit) {
        modalItemName.textContent = `Add to Cart: ${itemName}`;
        modalItemId.value = itemId;
        modalQuantity.max = available;
        modalQuantity.value = 1;
        modalUnit.textContent = unit;
        availableQty.textContent = `${available} ${unit}`;
        
        modal.classList.remove('hidden');
        
        // Focus on quantity input
        setTimeout(() => modalQuantity.focus(), 100);
        
        // Close modal on background click
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal();
            }
        });
        
        // Validate quantity input
        modalQuantity.addEventListener('input', function() {
            let value = parseInt(this.value) || 0;
            if (value < 1) this.value = 1;
            if (value > available) this.value = available;
        });
    };

    window.closeModal = function() {
        modal.classList.add('hidden');
    };

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });
</script>
@endpush