@extends('layouts.admin')

@section('title', 'Inventory Management')

@section('page-title', 'Inventory Management')

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
            <li class="text-blue-600 font-medium">Inventory</li>
        </ol>
    </nav>
@endsection

@section('header-actions')
    <div class="flex items-center space-x-2">
        <a href="{{ route('admin.inventory.low-stock') }}"
           class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.73 0L4.342 16.5c-.77.833.192 2.5 1.732 2.5z"/>
            </svg>
            Low Stock
        </a>
        <a href="{{ route('admin.inventory.create') }}"
           class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Item
        </a>
    </div>
@endsection

@section('content')

<style>
:root {
    --cream:    #FAF7F0;
    --sand:     #D8D2C2;
    --sienna:   #B17457;
    --charcoal: #4A4947;
}

/* ── PAGE ── */
.inv-page {
    background: var(--cream);
    padding: 2rem;
    font-family: 'Georgia', serif;
    min-height: 100vh;
}

/* ── FLASH ── */
.flash {
    display: flex;
    align-items: center;
    gap: .75rem;
    padding: 1rem 1.25rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    border-left: 4px solid;
}
.flash-success {
    background: #f0faf0;
    border-left-color: #6aab6a;
}
.flash-error {
    background: #fff0f0;
    border-left-color: #d87070;
}
.flash-icon { flex-shrink: 0; width: 1.25rem; height: 1.25rem; }
.flash-icon-success { color: #4a8c4a; }
.flash-icon-error   { color: #c0392b; }
.flash-text { font-size: .875rem; font-weight: 600; }
.flash-text-success { color: #2e6b2e; }
.flash-text-error   { color: #8b2020; }

/* ── STATS GRID ── */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
}
.stat-card {
    background: #fff;
    border: 1px solid var(--sand);
    border-radius: 10px;
    padding: 1.25rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: box-shadow .15s;
}
.stat-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,.08); }
.stat-card.clickable { cursor: pointer; text-decoration: none; border-left: 4px solid; }
.stat-card.clickable.active { box-shadow: 0 4px 12px rgba(0,0,0,.12); }
.stat-card.low-stock { border-left-color: #e6a23c; }
.stat-info p:first-child { font-size: .8rem; color: #6b6966; margin-bottom: .3rem; }
.stat-info p:last-child { font-size: 1.75rem; font-weight: 700; color: var(--charcoal); }
.stat-icon {
    width: 3rem; height: 3rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.stat-icon svg { width: 1.5rem; height: 1.5rem; }
.stat-icon-blue   { background: #d9ebf7; }
.stat-icon-blue svg   { color: #4a7fb5; }
.stat-icon-green  { background: #eef6ee; }
.stat-icon-green svg  { color: #4a8c4a; }
.stat-icon-yellow { background: #fff4e6; }
.stat-icon-yellow svg { color: #e6a23c; }
.stat-icon-red    { background: #ffe6e6; }
.stat-icon-red svg    { color: #c0392b; }

/* ── FILTER BAR ── */
.filter-bar {
    background: #fff;
    border: 1px solid var(--sand);
    border-radius: 10px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}
.filter-grid {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1rem;
}
.filter-field label {
    display: block;
    font-size: .75rem;
    font-weight: 600;
    color: var(--charcoal);
    margin-bottom: .4rem;
    letter-spacing: .02em;
}
.filter-input,
.filter-select {
    width: 100%;
    padding: .5rem .9rem;
    font-size: .875rem;
    font-family: inherit;
    background: var(--cream);
    border: 1px solid var(--sand);
    border-radius: 7px;
    color: var(--charcoal);
    outline: none;
    transition: border-color .2s;
}
.filter-input:focus,
.filter-select:focus { border-color: var(--sienna); }
.filter-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 1rem;
    border-top: 1px solid var(--sand);
}
.filter-count {
    font-size: .85rem;
    color: #6b6966;
}
.filter-count.low-stock {
    font-weight: 600;
    color: #c77d11;
}
.filter-btns { display: flex; gap: .75rem; }

.btn {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    padding: .5rem 1.25rem;
    font-size: .875rem;
    font-weight: 600;
    font-family: inherit;
    border: none;
    border-radius: 7px;
    cursor: pointer;
    text-decoration: none;
    transition: opacity .15s, transform .1s;
}
.btn:hover  { opacity: .88; transform: translateY(-1px); }
.btn:active { transform: translateY(0); }
.btn-primary { background: var(--sienna); color: #fff; }
.btn-muted   { background: var(--sand); color: var(--charcoal); }

/* ── TABLE CARD ── */
.table-card {
    background: #fff;
    border: 1px solid var(--sand);
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 1.5rem;
}
.table-wrap { overflow-x: auto; }
table { width: 100%; border-collapse: collapse; font-size: .875rem; }
thead tr { background: var(--cream); border-bottom: 2px solid var(--sand); }
thead th {
    padding: .85rem 1.2rem;
    text-align: left;
    font-size: .72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: var(--charcoal);
}
tbody tr {
    border-bottom: 1px solid #f0ece4;
    transition: background .12s;
}
tbody tr:last-child { border-bottom: none; }
tbody tr:hover { background: #fdfbf7; }
tbody tr.low-stock { background: #fffbf0; }
tbody tr.low-stock:hover { background: #fff6e0; }
tbody td {
    padding: 1rem 1.2rem;
    color: var(--charcoal);
    vertical-align: middle;
}

/* ── ITEM CELL ── */
.item-cell { display: flex; align-items: center; gap: 1rem; }
.item-icon {
    width: 2.5rem; height: 2.5rem;
    background: #d9ebf7;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.item-icon svg { width: 1.5rem; height: 1.5rem; color: #4a7fb5; }
.item-info .item-name {
    font-size: .875rem;
    font-weight: 600;
    color: var(--charcoal);
    margin-bottom: .15rem;
}
.item-info .item-desc {
    font-size: .75rem;
    color: #9a9591;
    max-width: 18rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* ── CATEGORY BADGE ── */
.cat-badge {
    display: inline-block;
    padding: .25rem .65rem;
    font-size: .7rem;
    font-weight: 600;
    background: #d9ebf7;
    color: #2d5f8a;
    border-radius: 20px;
}

/* ── STOCK BAR ── */
.stock-bar-wrap { display: flex; align-items: center; gap: .75rem; }
.stock-bar {
    width: 6rem;
    height: .625rem;
    background: #e8e3d8;
    border-radius: 20px;
    overflow: hidden;
}
.stock-bar-fill {
    height: 100%;
    border-radius: 20px;
    transition: width .3s;
}
.stock-bar-fill.green  { background: #4a8c4a; }
.stock-bar-fill.yellow { background: #e6a23c; }
.stock-bar-fill.red    { background: #c0392b; }
.stock-qty {
    font-size: .875rem;
    font-weight: 600;
    color: var(--charcoal);
}
.stock-min {
    font-size: .7rem;
    color: #9a9591;
    margin-top: .2rem;
}

/* ── STATUS BADGE ── */
.status-badge {
    display: inline-block;
    padding: .3rem .75rem;
    font-size: .7rem;
    font-weight: 700;
    border-radius: 20px;
    letter-spacing: .04em;
    text-transform: uppercase;
}
.status-in-stock  { background: #eef6ee; color: #2e7d32; }
.status-low-stock { background: #fff4e6; color: #c77d11; }
.status-out-stock { background: #ffe6e6; color: #a02f23; }

/* ── ACTION BUTTONS ── */
.actions { display: flex; gap: .5rem; }
.action-btn {
    padding: .4rem;
    border: none;
    background: none;
    cursor: pointer;
    border-radius: 6px;
    transition: background .12s;
}
.action-btn svg { width: 1.15rem; height: 1.15rem; }
.action-btn-view   { color: #4a7fb5; }
.action-btn-view:hover   { background: #d9ebf7; }
.action-btn-edit   { color: #4a8c4a; }
.action-btn-edit:hover   { background: #eef6ee; }
.action-btn-restock { color: #e6a23c; }
.action-btn-restock:hover { background: #fff4e6; }
.action-btn-delete { color: #c0392b; }
.action-btn-delete:hover { background: #ffe6e6; }

/* ── TFOOT NEW ITEM ── */
tfoot tr {
    background: #f5f1e8;
    border-top: 2px dashed var(--sand);
}
tfoot td {
    padding: 1rem 1.2rem;
}
.new-item-link {
    display: flex;
    align-items: center;
    gap: .5rem;
    color: var(--sienna);
    font-weight: 600;
    font-size: .875rem;
    text-decoration: none;
    width: fit-content;
    transition: opacity .15s;
}
.new-item-link:hover { opacity: .8; }
.new-item-icon {
    width: 2rem; height: 2rem;
    background: rgba(177,116,87,.15);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.new-item-icon svg { width: 1rem; height: 1rem; color: var(--sienna); }

/* ── PAGINATION ── */
.pagination-wrap {
    padding: 1rem 1.5rem;
    border-top: 1px solid var(--sand);
}
.pagination-wrap nav span[aria-current="page"] > span,
.pagination-wrap nav a {
    border-color: var(--sand) !important;
    color: var(--charcoal) !important;
}
.pagination-wrap nav span[aria-current="page"] > span {
    background: var(--sienna) !important;
    color: #fff !important;
    border-color: var(--sienna) !important;
}

/* ── EMPTY STATE ── */
.empty-state {
    padding: 3rem 1rem;
    text-align: center;
}
.empty-state svg { color: var(--sand); margin: 0 auto 1rem; }
.empty-state h3 { font-size: 1rem; font-weight: 600; color: var(--charcoal); margin-bottom: .5rem; }
.empty-state p { font-size: .875rem; color: #9a9591; margin-bottom: 1.5rem; }
.empty-state p a { color: var(--sienna); text-decoration: underline; }

/* ── EXPORT SECTION ── */
.export-section {
    background: #f5f1e8;
    border: 1px solid var(--sand);
    border-radius: 10px;
    padding: 1rem 1.25rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.export-info h4 { font-size: .875rem; font-weight: 600; color: var(--charcoal); margin-bottom: .25rem; }
.export-info p { font-size: .8rem; color: #6b6966; }

/* ── RESTOCK MODAL ── */
.modal {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.5);
    z-index: 60;
    overflow-y: auto;
}
.modal.show { display: flex; align-items: flex-start; justify-content: center; padding-top: 5rem; }
.modal-card {
    background: var(--cream);
    border-radius: 12px;
    box-shadow: 0 12px 48px rgba(0,0,0,.25);
    width: 90%;
    max-width: 26rem;
    padding: 1.5rem;
}
.modal-icon {
    width: 3rem; height: 3rem;
    background: #fff4e6;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
}
.modal-icon svg { width: 1.5rem; height: 1.5rem; color: #e6a23c; }
.modal-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--charcoal);
    text-align: center;
    margin-bottom: 1rem;
}
.modal-body { padding: 0 1rem 1rem; }
.modal-field { margin-bottom: 1rem; }
.modal-field label {
    display: block;
    font-size: .8rem;
    font-weight: 600;
    color: var(--charcoal);
    margin-bottom: .4rem;
}
.modal-input,
.modal-textarea {
    width: 100%;
    padding: .6rem;
    font-size: .875rem;
    font-family: inherit;
    background: #fff;
    border: 1px solid var(--sand);
    border-radius: 7px;
    color: var(--charcoal);
    outline: none;
    transition: border-color .2s;
}
.modal-input:focus,
.modal-textarea:focus { border-color: #e6a23c; }
.modal-btns {
    display: flex;
    justify-content: center;
    gap: .75rem;
    padding-top: .5rem;
}
.modal-btn {
    padding: .6rem 1.25rem;
    font-size: .875rem;
    font-weight: 600;
    font-family: inherit;
    border: none;
    border-radius: 7px;
    cursor: pointer;
    transition: opacity .15s;
}
.modal-btn:hover { opacity: .88; }
.modal-btn-cancel { background: var(--sand); color: var(--charcoal); }
.modal-btn-confirm { background: #e6a23c; color: #fff; }

@media (max-width: 768px) {
    .filter-grid { grid-template-columns: 1fr; }
    .stats-grid { grid-template-columns: 1fr; }
}
</style>

<div class="inv-page">

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="flash flash-success">
            <svg class="flash-icon flash-icon-success" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <p class="flash-text flash-text-success">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="flash flash-error">
            <svg class="flash-icon flash-icon-error" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <p class="flash-text flash-text-error">{{ session('error') }}</p>
        </div>
    @endif

    {{-- Quick Stats --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-info">
                <p>Total Items</p>
                <p>{{ $items->total() }}</p>
            </div>
            <div class="stat-icon stat-icon-blue">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <p>Total Stock</p>
                <p>{{ \App\Models\Item::sum('quantity') }}</p>
            </div>
            <div class="stat-icon stat-icon-green">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM14 11a1 1 0 011 1v1h1a1 1 0 110 2h-1v1a1 1 0 11-2 0v-1h-1a1 1 0 110-2h1v-1a1 1 0 011-1z"/>
                </svg>
            </div>
        </div>

        <a href="{{ route('admin.inventory.index', ['stock_level' => 'low']) }}"
           class="stat-card clickable low-stock {{ request('stock_level') == 'low' ? 'active' : '' }}">
            <div class="stat-info">
                <p>Low Stock Items</p>
                <p>{{ \App\Models\Item::where(function($q){ $q->whereColumn('quantity', '<=', 'minimum_quantity')->orWhere('quantity', 0); })->count() }}</p>
            </div>
            <div class="stat-icon stat-icon-yellow">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
            </div>
        </a>

        <div class="stat-card">
            <div class="stat-info">
                <p>Out of Stock</p>
                <p>{{ \App\Models\Item::where('quantity', 0)->count() }}</p>
            </div>
            <div class="stat-icon stat-icon-red">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Search and Filters --}}
    <div class="filter-bar">
        <form method="GET" action="{{ route('admin.inventory.index') }}" id="filterForm">
            <div class="filter-grid">
                <div class="filter-field">
                    <label for="search">Search</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                           placeholder="Search by name or description…"
                           class="filter-input">
                </div>

                <div class="filter-field">
                    <label for="category_id">Category</label>
                    <select name="category_id" id="category_id" class="filter-select">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-field">
                    <label for="stock_level">Stock Level</label>
                    <select name="stock_level" id="stock_level" class="filter-select">
                        <option value="">All Stock Levels</option>
                        <option value="low"    {{ request('stock_level') == 'low'    ? 'selected' : '' }}>Low Stock</option>
                        <option value="out"    {{ request('stock_level') == 'out'    ? 'selected' : '' }}>Out of Stock</option>
                        <option value="normal" {{ request('stock_level') == 'normal' ? 'selected' : '' }}>Normal Stock</option>
                    </select>
                </div>
            </div>

            <div class="filter-actions">
                <div class="filter-count {{ request('stock_level') == 'low' ? 'low-stock' : '' }}">
                    @if(request('stock_level') == 'low')
                        ⚠ Showing low stock items only — {{ $items->total() }} item(s) found
                    @else
                        {{ $items->total() }} item(s) found
                    @endif
                </div>
                <div class="filter-btns">
                    @if(request()->hasAny(['search', 'category_id', 'stock_level']))
                        <a href="{{ route('admin.inventory.index') }}" class="btn btn-muted">Clear Filters</a>
                    @endif
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                </div>
            </div>
        </form>
    </div>

    {{-- Inventory Table --}}
    <div class="table-card">
        @if($items->count() > 0)
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Category</th>
                            <th>Stock</th>
                            <th>Unit</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            <tr class="{{ ($item->quantity == 0 || $item->quantity <= $item->minimum_quantity) ? 'low-stock' : '' }}">
                                <td>
                                    <div class="item-cell">
                                        <div class="item-icon">
                                            <svg fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"/>
                                                <path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <div class="item-info">
                                            <div class="item-name">{{ $item->name }}</div>
                                            @if($item->description)
                                                <div class="item-desc">{{ Str::limit($item->description, 50) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($item->category)
                                        <span class="cat-badge">{{ $item->category->name }}</span>
                                    @else
                                        <span style="color:#9a9591;">—</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="stock-bar-wrap">
                                        <div class="stock-bar">
                                            @php
                                                $percentage = $item->minimum_quantity > 0
                                                    ? min(100, ($item->quantity / $item->minimum_quantity) * 100)
                                                    : 0;
                                                $color = $item->quantity == 0 ? 'red'
                                                    : ($item->quantity <= $item->minimum_quantity ? 'yellow' : 'green');
                                            @endphp
                                            <div class="stock-bar-fill {{ $color }}" style="width: {{ $percentage }}%"></div>
                                        </div>
                                        <div class="stock-qty">{{ $item->quantity }}</div>
                                    </div>
                                    <div class="stock-min">Min: {{ $item->minimum_quantity }}</div>
                                </td>
                                <td>{{ $item->unit_of_measure }}</td>
                                <td>
                                    @if($item->quantity == 0)
                                        <span class="status-badge status-out-stock">Out of Stock</span>
                                    @elseif($item->quantity <= $item->minimum_quantity)
                                        <span class="status-badge status-low-stock">Low Stock</span>
                                    @else
                                        <span class="status-badge status-in-stock">In Stock</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="actions">
                                        <a href="{{ route('admin.inventory.show', $item) }}"
                                           class="action-btn action-btn-view"
                                           title="View Details">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        <a href="{{ route('admin.inventory.edit', $item) }}"
                                           class="action-btn action-btn-edit"
                                           title="Edit">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        <button type="button"
                                                onclick="showRestockModal({{ $item->id }}, '{{ addslashes($item->name) }}')"
                                                class="action-btn action-btn-restock"
                                                title="Restock">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                            </svg>
                                        </button>
                                        <form action="{{ route('admin.inventory.destroy', $item) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    onclick="confirmDelete('{{ addslashes($item->name) }}', this)"
                                                    class="action-btn action-btn-delete"
                                                    title="Delete">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                    <tfoot>
                        <tr>
                            <td colspan="6">
                                <a href="{{ route('admin.inventory.create') }}" class="new-item-link">
                                    <div class="new-item-icon">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                    </div>
                                    Add New Item
                                </a>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            @if($items->hasPages())
                <div class="pagination-wrap">
                    {{ $items->links() }}
                </div>
            @endif

        @else
            <div class="empty-state">
                <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <h3>No inventory items found</h3>
                <p>
                    @if(request()->hasAny(['search', 'category_id', 'stock_level']))
                        Try adjusting your filters or <a href="{{ route('admin.inventory.index') }}">clear all filters</a>
                    @else
                        Get started by adding your first inventory item
                    @endif
                </p>
                <a href="{{ route('admin.inventory.create') }}" class="btn btn-primary">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add New Item
                </a>
            </div>
        @endif
    </div>

    {{-- Export Options --}}
    <div class="export-section">
        <div class="export-info">
            <h4>Export Inventory Data</h4>
            <p>Download inventory data for reporting</p>
        </div>
        <button type="button"
                onclick="alert('Export feature coming soon!')"
                class="btn btn-muted">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Export to CSV
        </button>
    </div>

</div>

@endsection

@push('modals')
<!-- Restock Modal -->
<div id="restockModal" class="modal">
    <div class="modal-card">
        <div class="modal-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
            </svg>
        </div>
        <h3 class="modal-title" id="modalTitle">Restock Item</h3>
        <div class="modal-body">
            <form id="restockForm" method="POST">
                @csrf
                <div class="modal-field">
                    <label for="restockQuantity">Quantity to Add *</label>
                    <input type="number" id="restockQuantity" name="quantity" min="1"
                           class="modal-input"
                           placeholder="Enter quantity" required>
                </div>
                <div class="modal-field">
                    <label for="restockNotes">Notes (Optional)</label>
                    <textarea id="restockNotes" name="notes" rows="2"
                              class="modal-textarea"
                              placeholder="Add any notes about this restock…"></textarea>
                </div>
            </form>
        </div>
        <div class="modal-btns">
            <button id="modalCancelBtn" class="modal-btn modal-btn-cancel">Cancel</button>
            <button id="modalConfirmBtn" class="modal-btn modal-btn-confirm">Restock</button>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
    function confirmDelete(itemName, button) {
        if (confirm(`Are you sure you want to delete "${itemName}"? This action cannot be undone.`)) {
            button.closest('form').submit();
        }
    }

    function showRestockModal(itemId, itemName) {
        const modal       = document.getElementById('restockModal');
        const modalTitle  = document.getElementById('modalTitle');
        const restockForm = document.getElementById('restockForm');

        modalTitle.textContent  = `Restock: ${itemName}`;
        restockForm.action      = `/admin/inventory/${itemId}/restock`;

        document.getElementById('restockQuantity').value = '';
        document.getElementById('restockNotes').value    = '';

        modal.classList.add('show');

        document.getElementById('modalCancelBtn').onclick = () => modal.classList.remove('show');

        document.getElementById('modalConfirmBtn').onclick = () => {
            if (document.getElementById('restockQuantity').value) {
                restockForm.submit();
            } else {
                alert('Please enter a quantity.');
            }
        };

        modal.addEventListener('click', e => {
            if (e.target === modal) modal.classList.remove('show');
        });
    }

    document.getElementById('category_id')?.addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });

    document.getElementById('stock_level')?.addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });
</script>
@endpush