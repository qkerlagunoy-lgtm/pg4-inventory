@extends('layouts.user')

@section('title', 'Shopping Cart')

@section('header-actions')
    <a href="{{ route('requests.index') }}" 
       class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Continue Shopping
    </a>
@endsection

@section('content')
<style>
    /* focus rings → slate */
    .f-input:focus {
        outline: none;
        border-color: #6E7DA2 !important;
        box-shadow: 0 0 0 3px rgba(110,125,162,0.18);
    }

    /* update qty icon → slate */
    .btn-update { color: #6E7DA2; }
    .btn-update:hover { color: #4a5878; background: rgba(110,125,162,0.08); }

    /* update notes icon → teal */
    .btn-update-notes { color: #7bbfc3; }
    .btn-update-notes:hover { color: #5a9ea0; background: rgba(174,218,221,0.15); }

    /* remove icon → stays red (danger) */
    .btn-remove { color: #c0392b; }
    .btn-remove:hover { color: #922b21; background: rgba(192,57,43,0.07); }

    /* table thead */
    .thead-cream { background: #FCF8F3; }

    /* low stock text */
    .text-lowstock { color: #DB996C; font-weight: 600; }

    /* ready to submit → slate */
    .text-ready { color: #6E7DA2; font-weight: 600; }

    /* clear cart button */
    .btn-clear-cart {
        border: 1px solid #d0d4e0;
        color: #4a5878;
    }
    .btn-clear-cart:hover { background: #f0f1f5; }

    /* browse / empty state CTA → terracotta */
    .btn-browse {
        background: #DB996C;
        color: #fff;
    }
    .btn-browse:hover { background: #c8844f; }

    /* submit request button → slate */
    .btn-submit {
        background: #6E7DA2;
        color: #fff;
    }
    .btn-submit:hover { background: #5a6a8a; }

    /* empty cart icon */
    .empty-cart-icon { color: rgba(174,218,221,0.5); }
</style>

    @if(empty($cartItems))
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <div class="flex justify-center mb-4">
                <svg class="w-24 h-24 empty-cart-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Your cart is empty</h3>
            <p class="text-gray-500 mb-6">Add items to your cart to submit a request</p>
            <a href="{{ route('requests.index') }}" 
               class="btn-browse inline-block px-6 py-3 rounded-lg transition">
                Browse Items
            </a>
        </div>
    @else
        <!-- Cart Items Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="thead-cream">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Available</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($cartItems as $itemId => $cartItem)
                            @php 
                                $item = $cartItem['item'] ?? null; 
                                $quantity = $cartItem['quantity'] ?? 0;
                                $notes = $cartItem['notes'] ?? '';
                            @endphp
                            @if($item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $item->name }}</div>
                                    @if($item->storage_location)
                                        <div class="text-xs text-gray-500 mt-1">Location: {{ $item->storage_location }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $item->category->name ?? 'Uncategorized' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    <span class="{{ $item->quantity <= $item->minimum_quantity ? 'text-lowstock' : '' }}">
                                        {{ $item->quantity }} {{ $item->unit }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <form method="POST" action="{{ route('requests.cart.update', $itemId) }}" class="flex items-center gap-2">
                                        @csrf
                                        @method('POST')
                                        <input type="number" name="quantity" 
                                            value="{{ $quantity }}" 
                                            min="1" max="{{ $item->quantity }}"
                                            class="f-input w-20 px-2 py-1 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                        <button type="submit" 
                                                class="btn-update p-1 rounded"
                                                title="Update">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                                <td class="px-6 py-4">
                                    <form method="POST" action="{{ route('requests.cart.update', $itemId) }}" class="flex items-center gap-2">
                                        @csrf
                                        @method('POST')
                                        <input type="hidden" name="quantity" value="{{ $quantity }}">
                                        <input type="text" name="notes" 
                                            value="{{ $notes }}"
                                            placeholder="Add notes..."
                                            class="f-input w-32 px-2 py-1 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                        <button type="submit" 
                                                class="btn-update-notes p-1 rounded"
                                                title="Update notes">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                                <td class="px-6 py-4">
                                    <form method="POST" action="{{ route('requests.cart.remove', $itemId) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                onclick="return confirm('Remove this item from your cart?')"
                                                class="btn-remove p-1 rounded"
                                                title="Remove">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Cart Summary & Submit Form -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Cart Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Cart Summary</h3>
                    
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Total Items:</span>
                            <span class="font-medium text-gray-900">{{ count($cartItems) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Total Quantity:</span>
                            <span class="font-medium text-gray-900">
                                {{ array_sum(array_column($cartItems, 'quantity')) }}
                            </span>
                        </div>
                        <div class="border-t border-gray-200 pt-3">
                            <div class="flex justify-between text-sm font-medium">
                                <span class="text-gray-700">Ready to submit:</span>
                                <span class="text-ready">Yes</span>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('requests.cart.clear') }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                onclick="return confirm('Clear all items from your cart?')"
                                class="btn-clear-cart w-full px-4 py-2 rounded-lg transition">
                            Clear Cart
                        </button>
                    </form>
                </div>
            </div>

            <!-- Submit Request Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Submit Request</h3>
                    
                    <form method="POST" action="{{ route('requests.submit') }}" class="space-y-4">
                        @csrf
                        
                        <!-- Purpose -->
                        <div>
                            <label for="purpose" class="block text-sm font-medium text-gray-700 mb-2">
                                Purpose of Request *
                            </label>
                            <textarea id="purpose" name="purpose" rows="3" required
                                class="f-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Explain why you need these items...">{{ old('purpose') }}</textarea>
                            @error('purpose')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Priority & Required Date -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
                                    Priority *
                                </label>
                                <select id="priority" name="priority" 
                                        class="f-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="low">Low</option>
                                    <option value="medium" selected>Medium</option>
                                    <option value="high">High</option>
                                    <option value="urgent">Urgent</option>
                                </select>
                            </div>
                            
                            <div>
                                <label for="required_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    Required By (Optional)
                                </label>
                                <input type="date" id="required_date" name="required_date" 
                                    min="{{ date('Y-m-d') }}"
                                    class="f-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    value="{{ old('required_date') }}">
                            </div>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                Additional Notes
                            </label>
                            <textarea id="notes" name="notes" rows="2"
                                class="f-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Any additional notes about your request...">{{ old('notes') }}</textarea>
                        </div>

                        <!-- Remarks -->
                        <div>
                            <label for="remarks" class="block text-sm font-medium text-gray-700 mb-2">
                                Remarks (For Admin)
                            </label>
                            <textarea id="remarks" name="remarks" rows="2"
                                class="f-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Any special instructions for the admin...">{{ old('remarks') }}</textarea>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" 
                                class="btn-submit w-full font-semibold py-3 px-4 rounded-lg transition flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Submit Request
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection