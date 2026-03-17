@extends('layouts.user')

@section('title', 'Cart')

@section('header-actions')
    <a href="{{ route('requests.index') }}"
       class="px-5 py-2.5 bg-[#1a1a1a] text-white rounded-full text-sm font-semibold hover:bg-[#333] transition-all flex items-center gap-2 shadow-md">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Continue Shopping
    </a>
@endsection

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Serif+Display:ital@0;1&display=swap');

    .cart-wrap { font-family: 'DM Sans', sans-serif; }

    .section-title {
        font-family: 'DM Serif Display', serif;
        font-size: 1.75rem;
        color: #1a1a1a;
        font-weight: 400;
    }

    /* Inputs */
    .c-input {
        border: 1.5px solid #e5e7eb;
        border-radius: 8px;
        padding: .45rem .75rem;
        font-size: .85rem;
        font-family: 'DM Sans', sans-serif;
        transition: border-color .18s, box-shadow .18s;
        background: #fff;
    }
    .c-input:focus {
        outline: none;
        border-color: #1a1a1a;
        box-shadow: 0 0 0 3px rgba(26,26,26,0.07);
    }

    /* Table */
    .cart-table thead { background: #fafafa; border-bottom: 1.5px solid #f0f0f0; }
    .cart-table th { font-size: .7rem; font-weight: 600; letter-spacing: .07em; color: #999; text-transform: uppercase; padding: .9rem 1.25rem; }
    .cart-table td { padding: 1rem 1.25rem; border-bottom: 1px solid #f5f5f5; vertical-align: middle; }
    .cart-table tr:last-child td { border-bottom: none; }
    .cart-table tbody tr:hover { background: #fafafa; }

    /* Item image in table */
    .cart-item-img {
        width: 60px; height: 60px;
        border-radius: 10px;
        object-fit: cover;
        border: 1px solid #f0f0f0;
        flex-shrink: 0;
    }
    .cart-item-img-placeholder {
        width: 60px; height: 60px;
        border-radius: 10px;
        background: #f5f5f5;
        border: 1px solid #eee;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }

    /* Action icon buttons */
    .btn-icon {
        width: 32px; height: 32px;
        border-radius: 8px;
        display: inline-flex; align-items: center; justify-content: center;
        transition: background .15s, color .15s;
        border: none; cursor: pointer; background: transparent;
    }
    .btn-icon-update { color: #6E7DA2; }
    .btn-icon-update:hover { background: rgba(110,125,162,0.1); color: #4a5878; }
    .btn-icon-save { color: #7bbfc3; }
    .btn-icon-save:hover { background: rgba(174,218,221,0.2); color: #5a9ea0; }
    .btn-icon-remove { color: #e0523a; }
    .btn-icon-remove:hover { background: rgba(224,82,58,0.08); color: #c0392b; }

    /* Summary card */
    .summary-card { background: #fff; border-radius: 16px; border: 1px solid #f0f0f0; padding: 1.5rem; }
    .summary-row { display: flex; justify-content: space-between; font-size: .85rem; padding: .4rem 0; }
    .summary-row span:first-child { color: #888; }
    .summary-row span:last-child { font-weight: 600; color: #1a1a1a; }
    .summary-divider { border: none; border-top: 1px solid #f0f0f0; margin: .75rem 0; }

    /* Submit form card */
    .form-card { background: #fff; border-radius: 16px; border: 1px solid #f0f0f0; padding: 1.5rem; }
    .form-label { display: block; font-size: .72rem; font-weight: 700; color: #888; text-transform: uppercase; letter-spacing: .07em; margin-bottom: .45rem; }
    .form-textarea {
        width: 100%;
        border: 1.5px solid #e5e7eb;
        border-radius: 10px;
        padding: .65rem .9rem;
        font-size: .875rem;
        font-family: 'DM Sans', sans-serif;
        resize: none;
        transition: border-color .18s, box-shadow .18s;
    }
    .form-textarea:focus {
        outline: none;
        border-color: #1a1a1a;
        box-shadow: 0 0 0 3px rgba(26,26,26,0.07);
    }
    .form-select {
        width: 100%;
        border: 1.5px solid #e5e7eb;
        border-radius: 10px;
        padding: .6rem .9rem;
        font-size: .875rem;
        font-family: 'DM Sans', sans-serif;
        background: #fff;
        transition: border-color .18s;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23999'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right .75rem center;
        background-size: 1rem;
    }
    .form-select:focus { outline: none; border-color: #1a1a1a; box-shadow: 0 0 0 3px rgba(26,26,26,0.07); }

    /* Buttons */
    .btn-submit {
        width: 100%; padding: .8rem; border: none; border-radius: 10px;
        background: #1a1a1a; color: #fff;
        font-size: .9rem; font-weight: 700; font-family: 'DM Sans', sans-serif;
        cursor: pointer; transition: background .18s;
        display: flex; align-items: center; justify-content: center; gap: .5rem;
    }
    .btn-submit:hover { background: #DB996C; }

    .btn-clear {
        width: 100%; padding: .65rem; border: 1.5px solid #e5e7eb; border-radius: 10px;
        background: #fff; color: #888;
        font-size: .82rem; font-weight: 600; font-family: 'DM Sans', sans-serif;
        cursor: pointer; transition: border-color .18s, color .18s;
    }
    .btn-clear:hover { border-color: #e0523a; color: #e0523a; }

    /* Empty state */
    .empty-wrap { background: #fafafa; border-radius: 16px; padding: 5rem 2rem; text-align: center; }
    .btn-browse {
        display: inline-flex; align-items: center; gap: .5rem;
        padding: .75rem 2rem; background: #1a1a1a; color: #fff;
        border-radius: 100px; font-size: .875rem; font-weight: 600;
        text-decoration: none; transition: background .18s;
    }
    .btn-browse:hover { background: #DB996C; }
</style>

<div class="cart-wrap max-w-7xl mx-auto">

    <div class="mb-6">
        @if(!empty($cartItems))
            <p class="text-sm text-gray-400 mt-1">{{ count($cartItems) }} item{{ count($cartItems) !== 1 ? 's' : '' }} ready for request</p>
        @endif
    </div>

    @if(empty($cartItems))
        <div class="empty-wrap">
            <svg class="w-20 h-20 text-gray-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <h3 class="text-lg font-semibold text-gray-600 mb-1">Your cart is empty</h3>
            <p class="text-sm text-gray-400 mb-6">Browse items and add them to your cart to submit a request.</p>
            <a href="{{ route('requests.index') }}" class="btn-browse">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                Browse Items
            </a>
        </div>

    @else

        {{-- Cart Table --}}
        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden mb-6 shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full cart-table">
                    <thead>
                        <tr>
                            <th class="text-left" style="width:72px;">Image</th>
                            <th class="text-left">Item</th>
                            <th class="text-left">Category</th>
                            <th class="text-left">Quantity</th>
                            <th class="text-left">Notes</th>
                            <th class="text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cartItems as $itemId => $cartItem)
                            @php
                                $item     = $cartItem['item'] ?? null;
                                $quantity = $cartItem['quantity'] ?? 0;
                                $notes    = $cartItem['notes'] ?? '';
                            @endphp
                            @if($item)
                            <tr>
                                {{-- Image --}}
                                <td>
                                    @if($item->image)
                                        <img src="{{ asset('storage/'.$item->image) }}"
                                             alt="{{ $item->name }}"
                                             class="cart-item-img">
                                    @else
                                        <div class="cart-item-img-placeholder">
                                            <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" stroke-width="1.4" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </td>

                                {{-- Item name --}}
                                <td>
                                    <div class="text-sm font-semibold text-gray-900">{{ $item->name }}</div>
                                    @if($item->storage_location)
                                        <div class="text-xs text-gray-400 mt-0.5">📍 {{ $item->storage_location }}</div>
                                    @endif
                                </td>

                                {{-- Category --}}
                                <td>
                                    <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-gray-100 text-gray-500">
                                        {{ $item->category->name ?? 'Uncategorized' }}
                                    </span>
                                </td>

                                {{-- Quantity --}}
                                <td>
                                    <form method="POST" action="{{ route('requests.cart.update', $itemId) }}" class="flex items-center gap-1.5">
                                        @csrf
                                        <input type="number" name="quantity"
                                               value="{{ $quantity }}"
                                               min="1" max="{{ $item->quantity }}"
                                               class="c-input w-20 text-center">
                                        <button type="submit" class="btn-icon btn-icon-update" title="Update quantity">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                            </svg>
                                        </button>
                                    </form>
                                </td>

                                {{-- Notes --}}
                                <td>
                                    <form method="POST" action="{{ route('requests.cart.update', $itemId) }}" class="flex items-center gap-1.5">
                                        @csrf
                                        <input type="hidden" name="quantity" value="{{ $quantity }}">
                                        <input type="text" name="notes"
                                               value="{{ $notes }}"
                                               placeholder="Add notes…"
                                               class="c-input w-36">
                                        <button type="submit" class="btn-icon btn-icon-save" title="Save notes">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </button>
                                    </form>
                                </td>

                                {{-- Remove --}}
                                <td>
                                    <form method="POST" action="{{ route('requests.cart.remove', $itemId) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                onclick="return confirm('Remove this item from your cart?')"
                                                class="btn-icon btn-icon-remove" title="Remove">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

        {{-- Summary + Submit --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Summary --}}
            <div class="lg:col-span-1 space-y-4">
                <div class="summary-card">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Order Summary</p>
                    <div class="summary-row">
                        <span>Total Items</span>
                        <span>{{ count($cartItems) }}</span>
                    </div>
                    <div class="summary-row">
                        <span>Total Quantity</span>
                        <span>{{ array_sum(array_column($cartItems, 'quantity')) }}</span>
                    </div>
                    <hr class="summary-divider">
                    <div class="summary-row">
                        <span>Status</span>
                        <span class="text-green-600">Ready to submit</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('requests.cart.clear') }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            onclick="return confirm('Clear all items from your cart?')"
                            class="btn-clear">
                        Clear Cart
                    </button>
                </form>
            </div>

            {{-- Submit Form --}}
            <div class="lg:col-span-2">
                <div class="form-card">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Request Details</p>

                    <form method="POST" action="{{ route('requests.submit') }}" class="space-y-4">
                        @csrf

                        <div>
                            <label for="purpose" class="form-label">Purpose of Request *</label>
                            <textarea id="purpose" name="purpose" rows="3" required
                                      class="form-textarea"
                                      placeholder="Explain why you need these items…">{{ old('purpose') }}</textarea>
                            @error('purpose')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="priority" class="form-label">Priority *</label>
                                <select id="priority" name="priority" class="form-select">
                                    <option value="low">Low</option>
                                    <option value="medium" selected>Medium</option>
                                    <option value="high">High</option>
                                    <option value="urgent">Urgent</option>
                                </select>
                            </div>
                            <div>
                                <label for="required_date" class="form-label">Required By (Optional)</label>
                                <input type="date" id="required_date" name="required_date"
                                       min="{{ date('Y-m-d') }}"
                                       class="c-input w-full"
                                       value="{{ old('required_date') }}">
                            </div>
                        </div>

                        <div>
                            <label for="notes" class="form-label">Additional Notes</label>
                            <textarea id="notes" name="notes" rows="2"
                                      class="form-textarea"
                                      placeholder="Any additional notes about your request…">{{ old('notes') }}</textarea>
                        </div>

                        <div>
                            <label for="remarks" class="form-label">Remarks for Admin</label>
                            <textarea id="remarks" name="remarks" rows="2"
                                      class="form-textarea"
                                      placeholder="Any special instructions for the admin…">{{ old('remarks') }}</textarea>
                        </div>

                        <button type="submit" class="btn-submit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Submit Request
                        </button>
                    </form>
                </div>
            </div>

        </div>
    @endif
</div>
@endsection