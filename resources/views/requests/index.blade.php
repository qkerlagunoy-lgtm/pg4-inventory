@extends('layouts.user')

@section('title', 'Request Items')
@section('header-actions')
    @php
        $cart = session()->get('cart', []);
        $cartCount = count($cart);
        $cartTotal = array_sum(array_column($cart, 'quantity'));
    @endphp
    <a href="{{ route('requests.cart') }}"
       class="relative px-5 py-2.5 bg-[#1a1a1a] text-white rounded-full text-sm font-semibold hover:bg-[#333] transition-all flex items-center gap-2.5 shadow-md">
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
        </svg>
        Cart
        @if($cartCount > 0)
            <span class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-[#DB996C] text-white text-[10px] font-bold rounded-full flex items-center justify-center shadow">{{ $cartTotal }}</span>
        @endif
    </a>
@endsection

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Serif+Display:ital@0;1&display=swap');

    .shop-wrap { font-family: 'DM Sans', sans-serif; }

    /* Search bar */
    .search-bar {
        border: 1.5px solid #e5e7eb;
        transition: border-color .2s, box-shadow .2s;
    }
    .search-bar:focus {
        outline: none;
        border-color: #1a1a1a;
        box-shadow: 0 0 0 3px rgba(26,26,26,0.08);
    }

    /* Filter pills */
    .filter-pill {
        border: 1.5px solid #e5e7eb;
        color: #555;
        background: #fff;
        transition: all .18s;
        font-size: .75rem;
        font-weight: 500;
        letter-spacing: .01em;
    }
    .filter-pill:hover, .filter-pill.active {
        border-color: #1a1a1a;
        background: #1a1a1a;
        color: #fff;
    }

    /* Product card */
    .product-card {
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        transition: box-shadow .22s, transform .22s;
        border: 1px solid #f0f0f0;
        display: flex;
        flex-direction: column;
    }
    .product-card:hover {
        box-shadow: 0 8px 32px rgba(0,0,0,0.10);
        transform: translateY(-3px);
    }

    /* Image area */
    .product-img-wrap {
        background: #f7f7f7;
        aspect-ratio: 1 / 1;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        position: relative;
    }
    .product-img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform .35s ease;
    }
    .product-card:hover .product-img-wrap img {
        transform: scale(1.05);
    }

    /* Unavailable overlay */
    .unavailable-overlay {
        position: absolute;
        inset: 0;
        background: rgba(255,255,255,0.55);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .unavailable-badge {
        background: #1a1a1a;
        color: #fff;
        font-size: .68rem;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
        padding: .3rem .8rem;
        border-radius: 100px;
    }

    /* Product info */
    .product-info { padding: 1rem 1rem .85rem; flex: 1; display: flex; flex-direction: column; gap: .25rem; }
    .product-category { font-size: .68rem; font-weight: 600; color: #aaa; text-transform: uppercase; letter-spacing: .08em; }
    .product-name { font-size: .92rem; font-weight: 600; color: #1a1a1a; line-height: 1.3; }
    .product-desc { font-size: .78rem; color: #888; margin-top: .1rem; line-height: 1.4; }
    .product-qty { font-size: .78rem; color: #555; margin-top: .2rem; }

    /* Add to cart button */
    .btn-add {
        display: block;
        width: 100%;
        padding: .65rem 1rem;
        background: #4a5878;
        color: #fff;
        font-size: .82rem;
        font-weight: 600;
        text-align: center;
        letter-spacing: .03em;
        border: none;
        cursor: pointer;
        transition: background .18s;
        border-top: 1px solid #f0f0f0;
        margin-top: auto;
    }
    .btn-add:hover { background: #DB996C; }
    .btn-add:disabled {
        background: #e5e7eb;
        color: #aaa;
        cursor: not-allowed;
    }

    /* Section title */
    .section-title {
        font-family: 'DM Serif Display', serif;
        font-size: 1.75rem;
        color: #1a1a1a;
        font-weight: 400;
    }
    .result-count { font-size: .82rem; color: #999; font-weight: 400; }

    /* Empty state */
    .empty-state { background: #fafafa; border-radius: 16px; padding: 4rem 2rem; text-align: center; }

    /* Modal */
    .modal-backdrop {
        background: rgba(0,0,0,0.45);
        backdrop-filter: blur(2px);
    }
    .modal-box {
        background: #fff;
        border-radius: 16px;
        width: 100%;
        max-width: 420px;
        padding: 2rem;
        box-shadow: 0 24px 60px rgba(0,0,0,0.18);
    }
    .modal-input {
        border: 1.5px solid #e5e7eb;
        border-radius: 8px;
        padding: .55rem .9rem;
        font-size: .9rem;
        width: 100%;
        transition: border-color .18s;
        font-family: 'DM Sans', sans-serif;
    }
    .modal-input:focus {
        outline: none;
        border-color: #1a1a1a;
        box-shadow: 0 0 0 3px rgba(26,26,26,0.07);
    }
    .btn-modal-cancel {
        flex: 1; padding: .65rem; border: 1.5px solid #e5e7eb; border-radius: 8px;
        font-size: .85rem; font-weight: 600; color: #555; background: #fff;
        cursor: pointer; transition: border-color .18s;
    }
    .btn-modal-cancel:hover { border-color: #1a1a1a; color: #1a1a1a; }
    .btn-modal-submit {
        flex: 1; padding: .65rem; border: none; border-radius: 8px;
        font-size: .85rem; font-weight: 600; color: #fff; background: #DB996C;
        cursor: pointer; transition: background .18s;
    }
    .btn-modal-submit:hover { background: #c8844f; }
</style>

<div class="shop-wrap max-w-7xl mx-auto">

    {{-- Header + Search --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-end gap-4">
        <div class="flex-1">
            <p class="result-count" id="resultCount">{{ $items->count() }} items available</p>
        </div>
        <div class="flex gap-2 flex-wrap">
            <div class="relative">
                <input type="text" id="searchInput" placeholder="Search items…"
                       class="search-bar pl-9 pr-4 py-2 rounded-full text-sm w-56">
                <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Category Filter Pills --}}
    @php
        $categories = $items->pluck('category.name')->filter()->unique()->sort()->values();
    @endphp
    @if($categories->isNotEmpty())
    <div class="flex flex-wrap gap-2 mb-6">
        <button onclick="filterByCategory('all')" id="pill-all" class="filter-pill active px-4 py-2 rounded-full">All</button>
        @foreach($categories as $cat)
            <button onclick="filterByCategory('{{ addslashes($cat) }}')"
                    id="pill-cat-{{ Str::slug($cat) }}"
                    class="filter-pill px-4 py-2 rounded-full">{{ $cat }}</button>
        @endforeach
    </div>
    @endif

    {{-- Product Grid --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4" id="productGrid">
        @forelse($items as $item)
            @php
                $isAvailable = $item->quantity > 0;
                $isLow = $item->quantity > 0 && $item->quantity <= $item->minimum_quantity;
            @endphp
            <div class="product-card"
                 data-category="{{ $item->category->name ?? 'Uncategorized' }}"
                 data-name="{{ $item->name }}"
                 data-description="{{ $item->description ?? '' }}"
                 data-stock="{{ $item->quantity }}"
                 data-minimum="{{ $item->minimum_quantity }}">

                {{-- Image --}}
                <div class="product-img-wrap">
                    @if($item->image)
                        <img src="{{ asset('storage/'.$item->image) }}" alt="{{ $item->name }}">
                    @else
                        <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" stroke-width="1.2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    @endif

                    @if(!$isAvailable)
                        <div class="unavailable-overlay">
                            <span class="unavailable-badge">Unavailable</span>
                        </div>
                    @endif

                    @if($isLow && $isAvailable)
                        <div class="absolute top-2 left-2">
                            <span class="bg-[#DB996C] text-white text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full">Low Stock</span>
                        </div>
                    @endif
                </div>

                {{-- Info --}}
                <div class="product-info">
                    <span class="product-category">{{ $item->category->name ?? 'Uncategorized' }}</span>
                    <span class="product-name">{{ $item->name }}</span>
                    <span class="product-desc">{{ Str::limit($item->description ?? 'No description available.', 55) }}</span>
                    <span class="product-qty">
                        @if($isAvailable)
                            <span class="text-green-600 font-medium">Available</span>
                        @else
                            <span class="text-gray-400 font-medium">Unavailable</span>
                        @endif
                    </span>
                </div>

                {{-- CTA --}}
                @if($isAvailable)
                    <button class="btn-add"
                            onclick="openAddToCartModal({{ $item->id }}, '{{ addslashes($item->name) }}', {{ $item->quantity }}, '{{ $item->unit }}')">
                        Add to Cart
                    </button>
                @else
                    <button class="btn-add" disabled>Out of Stock</button>
                @endif
            </div>
        @empty
            <div class="col-span-full empty-state">
                <svg class="w-14 h-14 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <p class="text-gray-400 text-sm font-medium">No items available right now.</p>
            </div>
        @endforelse
    </div>

    {{-- No results (dynamic) --}}
    <div id="noResults" class="hidden mt-6 empty-state">
        <svg class="w-14 h-14 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <p class="text-gray-400 text-sm font-medium">No items match your search.</p>
    </div>

    @if(method_exists($items, 'links'))
        <div class="mt-8">{{ $items->links() }}</div>
    @endif
</div>
@endsection

@push('modals')
{{-- Add to Cart Modal --}}
<div id="addToCartModal" class="hidden fixed inset-0 z-50 flex items-center justify-center modal-backdrop p-4">
    <div class="modal-box">
        <div class="flex items-start justify-between mb-5">
            <div>
                <h3 class="text-base font-bold text-gray-900" id="modalItemName">Add to Cart</h3>
                <p class="text-xs text-gray-400 mt-0.5">Specify the quantity you need</p>
            </div>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-700 transition ml-4 mt-0.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form id="addToCartForm" method="POST" action="{{ route('requests.cart.add') }}" class="space-y-4">
            @csrf
            <input type="hidden" id="modalItemId" name="item_id">

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Quantity</label>
                <div class="flex items-center gap-3">
                    <input type="number" id="modalQuantity" name="quantity" min="1"
                           class="modal-input" required>
                    <span class="text-sm text-gray-400 whitespace-nowrap" id="modalUnit">—</span>
                </div>
                <p class="mt-1.5 text-xs text-gray-400">Stock available: <span id="availableQty" class="font-semibold text-gray-600">0</span></p>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Notes <span class="font-normal text-gray-400">(optional)</span></label>
                <textarea id="modalNotes" name="notes" rows="2"
                          class="modal-input resize-none"
                          placeholder="Any specific requirements…"></textarea>
            </div>

            <div class="flex gap-2 pt-1">
                <button type="button" onclick="closeModal()" class="btn-modal-cancel">Cancel</button>
                <button type="submit" class="btn-modal-submit">Add to Cart</button>
            </div>
        </form>
    </div>
</div>
@endpush

@push('scripts')
<script>
    const searchInput = document.getElementById('searchInput');
    const grid = document.getElementById('productGrid');
    const cards = grid ? grid.querySelectorAll('.product-card') : [];
    const noResults = document.getElementById('noResults');
    const resultCount = document.getElementById('resultCount');

    function updateCount() {
        const visible = [...cards].filter(c => c.style.display !== 'none').length;
        if (resultCount) resultCount.textContent = `${visible} item${visible !== 1 ? 's' : ''} shown`;
        if (noResults) noResults.classList.toggle('hidden', visible > 0);
    }

    let activeCategory = 'all';

    function applyFilters() {
        const q = searchInput ? searchInput.value.toLowerCase() : '';

        cards.forEach(card => {
            const name = (card.dataset.name || '').toLowerCase();
            const cat  = (card.dataset.category || '').toLowerCase();
            const desc = (card.dataset.description || '').toLowerCase();

            const matchesSearch = !q || name.includes(q) || cat.includes(q) || desc.includes(q);
            const matchesCategory = activeCategory === 'all' || cat === activeCategory.toLowerCase();

            card.style.display = (matchesSearch && matchesCategory) ? '' : 'none';
        });

        updateCount();
    }

    if (searchInput) searchInput.addEventListener('input', applyFilters);

    window.filterByCategory = function(cat) {
        activeCategory = cat;
        document.querySelectorAll('.filter-pill').forEach(p => p.classList.remove('active'));
        if (cat === 'all') {
            document.getElementById('pill-all')?.classList.add('active');
        } else {
            // find the matching pill by its text content
            document.querySelectorAll('.filter-pill').forEach(p => {
                if (p.textContent.trim().toLowerCase() === cat.toLowerCase()) p.classList.add('active');
            });
        }
        applyFilters();
    };

    window.resetSearch = function() {
        if (searchInput) searchInput.value = '';
        activeCategory = 'all';
        document.querySelectorAll('.filter-pill').forEach(p => p.classList.remove('active'));
        document.getElementById('pill-all')?.classList.add('active');
        cards.forEach(c => c.style.display = '');
        updateCount();
    };

    // Modal
    const modal = document.getElementById('addToCartModal');

    window.openAddToCartModal = function(itemId, itemName, available, unit) {
        document.getElementById('modalItemName').textContent = itemName;
        document.getElementById('modalItemId').value = itemId;
        const qty = document.getElementById('modalQuantity');
        qty.max = available;
        qty.value = 1;
        document.getElementById('modalUnit').textContent = unit;
        document.getElementById('availableQty').textContent = `${available} ${unit}`;
        modal.classList.remove('hidden');
        setTimeout(() => qty.focus(), 80);
    };

    window.closeModal = function() { modal.classList.add('hidden'); };

    modal?.addEventListener('click', e => { if (e.target === modal) closeModal(); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

    document.getElementById('modalQuantity')?.addEventListener('input', function() {
        const max = parseInt(this.max) || 999;
        if (parseInt(this.value) < 1) this.value = 1;
        if (parseInt(this.value) > max) this.value = max;
    });
</script>
@endpush