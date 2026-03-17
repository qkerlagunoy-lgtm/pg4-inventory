@extends('layouts.admin')

@section('title', $item->name . ' - Item Details')

@section('page-title', 'Item: ' . $item->name)

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
            <li>
                <a href="{{ route('admin.inventory.index') }}" class="text-gray-500 hover:text-gray-700">Inventory</a>
            </li>
            <li>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </li>
            <li class="text-blue-600 font-medium">{{ Str::limit($item->name, 30) }}</li>
        </ol>
    </nav>
@endsection

@section('header-actions')
    <div class="flex items-center space-x-2">
        <a href="{{ route('admin.inventory.edit', $item) }}" 
           class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit
        </a>
        
        @if($item->quantity <= $item->minimum_quantity)
            <button type="button" 
                    onclick="showRestockModal()"
                    class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Restock
            </button>
        @endif
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

.item-details-page {
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
    padding: .85rem 1rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    border-left: 4px solid;
}
.flash-success {
    background: #f0faf0;
    border-color: #4a8c4a;
}
.flash-success svg { color: #4a8c4a; }
.flash-success p { color: #2e6b2e; font-size: .875rem; font-weight: 600; }
.flash-error {
    background: #fff0f0;
    border-color: #c0392b;
}
.flash-error svg { color: #c0392b; }
.flash-error p { color: #8b2020; font-size: .875rem; font-weight: 600; }

/* ── GRID LAYOUT ── */
.details-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}
@media (max-width: 1024px) {
    .details-grid { grid-template-columns: 1fr; }
}

/* ── MAIN CARD ── */
.main-card {
    background: #fff;
    border: 1px solid var(--sand);
    border-radius: 10px;
    padding: 1.5rem;
}

/* ── ITEM HEADER ── */
.item-header {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    margin-bottom: 1.5rem;
}
.item-image {
    width: 5rem;
    height: 5rem;
    object-fit: cover;
    border-radius: 10px;
    border: 1px solid var(--sand);
    flex-shrink: 0;
}
.item-icon-placeholder {
    width: 5rem;
    height: 5rem;
    background: #d9ebf7;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.item-icon-placeholder svg {
    width: 2.5rem;
    height: 2.5rem;
    color: #2d5f8a;
}
.item-title h2 {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--charcoal);
}
.item-meta {
    display: flex;
    align-items: center;
    gap: .75rem;
    margin-top: .5rem;
}
.category-badge {
    display: inline-flex;
    align-items: center;
    padding: .25rem .75rem;
    background: #d9ebf7;
    color: #2d5f8a;
    border-radius: 20px;
    font-size: .8rem;
    font-weight: 600;
}
.item-id {
    font-size: .85rem;
    color: #6b6966;
}

/* ── STATS GRID ── */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    margin-bottom: 1.5rem;
}
@media (max-width: 768px) {
    .stats-grid { grid-template-columns: repeat(2, 1fr); }
}
.stat-box {
    background: #f5f1e8;
    border: 1px solid var(--sand);
    border-radius: 8px;
    padding: 1rem;
}
.stat-box p:first-child {
    font-size: .8rem;
    color: #6b6966;
    margin-bottom: .3rem;
}
.stat-box p:nth-child(2) {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--charcoal);
}
.stat-box p:last-child {
    font-size: .75rem;
    color: #9a9591;
    margin-top: .2rem;
}

/* ── PROGRESS BAR ── */
.progress-section {
    margin-bottom: 1.5rem;
}
.progress-header {
    display: flex;
    justify-content: space-between;
    font-size: .85rem;
    color: #6b6966;
    margin-bottom: .5rem;
}
.progress-bar-bg {
    width: 100%;
    height: .625rem;
    background: #e8e3d8;
    border-radius: 20px;
    overflow: hidden;
}
.progress-bar-fill {
    height: 100%;
    border-radius: 20px;
    transition: width .3s;
}
.progress-bar-fill.green { background: #4a8c4a; }
.progress-bar-fill.yellow { background: #e6a23c; }
.progress-bar-fill.red { background: #c0392b; }
.progress-labels {
    display: flex;
    justify-content: space-between;
    font-size: .75rem;
    color: #9a9591;
    margin-top: .4rem;
}
.reorder-point {
    color: #c77d11;
    font-weight: 600;
}

/* ── DETAILS SECTION ── */
.details-section {
    margin-bottom: 1.5rem;
}
.details-section h4 {
    font-size: .9rem;
    font-weight: 700;
    color: #6b6966;
    margin-bottom: .5rem;
}
.description-text {
    color: #6b6966;
    line-height: 1.6;
    white-space: pre-line;
}
.info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}
.info-item {
    display: flex;
    align-items: center;
    gap: .5rem;
}
.info-item svg {
    width: 1.15rem;
    height: 1.15rem;
    color: #9a9591;
    flex-shrink: 0;
}
.info-item span {
    font-size: .875rem;
    color: #6b6966;
}

/* ── SIDEBAR CARDS ── */
.sidebar {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}
.sidebar-card {
    background: #fff;
    border: 1px solid var(--sand);
    border-radius: 10px;
    padding: 1.5rem;
}
.sidebar-card h3 {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--charcoal);
    margin-bottom: 1rem;
}

/* ── QUICK ACTIONS ── */
.action-list {
    display: flex;
    flex-direction: column;
    gap: .75rem;
}
.action-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: .75rem;
    border: 1px solid var(--border);
    background: var(--bg);
    border-radius: 8px;
    cursor: pointer;
    transition: background .15s;
    text-decoration: none;
}
.action-item:hover { background: var(--hover); }
.action-yellow { --bg: #fff4e6; --border: #e6ccb3; --hover: #ffe6cc; }
.action-blue { --bg: #d9ebf7; --border: #6ba3d4; --hover: #cce5f6; }
.action-red { --bg: #ffe6e6; --border: #f4b8b8; --hover: #ffd9d9; }
.action-left {
    display: flex;
    align-items: center;
    gap: .75rem;
}
.action-icon {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.action-icon.yellow { background: #ffe6cc; }
.action-icon.yellow svg { color: #c77d11; }
.action-icon.blue { background: #cce5f6; }
.action-icon.blue svg { color: #2d5f8a; }
.action-icon.red { background: #ffd9d9; }
.action-icon.red svg { color: #c0392b; }
.action-icon svg { width: 1.15rem; height: 1.15rem; }
.action-text p:first-child {
    font-size: .875rem;
    font-weight: 600;
    color: var(--charcoal);
}
.action-text p:last-child {
    font-size: .75rem;
    color: #6b6966;
    margin-top: .1rem;
}
.action-arrow svg {
    width: 1.15rem;
    height: 1.15rem;
    color: #9a9591;
}

/* ── RELATED ITEMS ── */
.related-list {
    display: flex;
    flex-direction: column;
    gap: .75rem;
}
.related-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: .5rem;
    border-radius: 8px;
    transition: background .15s;
    text-decoration: none;
}
.related-item:hover { background: #f5f1e8; }
.related-left {
    display: flex;
    align-items: center;
    gap: .75rem;
}
.related-icon {
    width: 2rem;
    height: 2rem;
    background: #d9ebf7;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.related-icon svg {
    width: 1rem;
    height: 1rem;
    color: #2d5f8a;
}
.related-name {
    font-size: .875rem;
    font-weight: 600;
    color: var(--charcoal);
}
.related-qty {
    font-size: .75rem;
    color: #9a9591;
    margin-top: .1rem;
}
.related-arrow svg {
    width: 1rem;
    height: 1rem;
    color: #9a9591;
}
.view-all-link {
    font-size: .85rem;
    color: var(--sienna);
    text-align: center;
    display: block;
    padding-top: .5rem;
    text-decoration: none;
}
.view-all-link:hover { text-decoration: underline; }

/* ── REQUEST HISTORY TABLE ── */
.history-card {
    background: #fff;
    border: 1px solid var(--sand);
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 1.5rem;
}
.history-header {
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid var(--sand);
    display: flex;
    align-items: center;
    gap: .5rem;
}
.history-header h3 {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--charcoal);
}
.history-count {
    display: inline-flex;
    padding: .2rem .5rem;
    background: #d9ebf7;
    color: #2d5f8a;
    border-radius: 20px;
    font-size: .7rem;
    font-weight: 700;
}
.history-body {
    padding: 1.5rem;
}
.history-table-wrap { overflow-x: auto; }
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
tbody tr { border-bottom: 1px solid #f0ece4; }
tbody tr:last-child { border-bottom: none; }
tbody td {
    padding: .85rem 1.2rem;
    color: var(--charcoal);
}
.status-badge {
    display: inline-block;
    padding: .25rem .6rem;
    border-radius: 20px;
    font-size: .7rem;
    font-weight: 700;
    text-transform: uppercase;
}
.status-pending { background: #fff4e6; color: #c77d11; }
.status-approved { background: #eef6ee; color: #2e7d32; }
.status-rejected { background: #ffe6e6; color: #c0392b; }
.status-issued { background: #d9ebf7; color: #2d5f8a; }
.status-partial { background: #e6d9f2; color: #7b5fa0; }
.table-link {
    color: var(--sienna);
    font-weight: 600;
    text-decoration: none;
}
.table-link:hover { text-decoration: underline; }

/* ── EMPTY STATE ── */
.empty-state {
    text-align: center;
    padding: 2rem 1rem;
}
.empty-state svg {
    color: var(--sand);
    margin: 0 auto 1rem;
}
.empty-state h3 {
    font-size: 1rem;
    font-weight: 600;
    color: var(--charcoal);
    margin-bottom: .25rem;
}
.empty-state p {
    font-size: .875rem;
    color: #9a9591;
}

/* ── MODAL ── */
.modal {
    position: fixed;
    inset: 0;
    background: rgba(74,73,71,.5);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 999;
    overflow-y: auto;
}
.modal.show { display: flex; }
.modal-content {
    background: #fff;
    border: 1px solid var(--sand);
    border-radius: 10px;
    max-width: 28rem;
    width: calc(100% - 2rem);
    padding: 1.5rem;
    margin: 1rem;
}
.modal-icon-wrap {
    width: 3rem;
    height: 3rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
}
.modal-icon-wrap.yellow { background: #fff4e6; }
.modal-icon-wrap.yellow svg { color: #c77d11; }
.modal-icon-wrap.red { background: #ffe6e6; }
.modal-icon-wrap.red svg { color: #c0392b; }
.modal-icon-wrap svg { width: 1.5rem; height: 1.5rem; }
.modal-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--charcoal);
    text-align: center;
    margin-bottom: 1rem;
}
.modal-form {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}
.form-field label {
    display: block;
    font-size: .8rem;
    font-weight: 600;
    color: var(--charcoal);
    margin-bottom: .4rem;
}
.form-input, .form-textarea {
    width: 100%;
    padding: .5rem .75rem;
    border: 1px solid var(--sand);
    border-radius: 7px;
    font-size: .875rem;
    background: var(--cream);
    color: var(--charcoal);
    outline: none;
    font-family: inherit;
}
.form-input:focus, .form-textarea:focus { border-color: var(--sienna); }
.form-textarea { resize: vertical; }
.stock-info {
    padding: .75rem;
    background: #f5f1e8;
    border: 1px solid var(--sand);
    border-radius: 7px;
    font-size: .85rem;
    color: #6b6966;
}
.stock-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: .25rem;
}
.stock-row:last-child { margin-bottom: 0; }
.stock-row span:last-child { font-weight: 600; color: var(--charcoal); }
.stock-row.green span:last-child { color: #4a8c4a; }
.modal-actions {
    display: flex;
    justify-content: center;
    gap: .75rem;
    margin-top: 1rem;
}
.btn {
    padding: .5rem 1rem;
    font-size: .875rem;
    font-weight: 600;
    border-radius: 7px;
    border: none;
    cursor: pointer;
    transition: opacity .15s;
}
.btn:hover { opacity: .88; }
.btn-muted { background: #f5f1e8; color: var(--charcoal); }
.btn-yellow { background: #e6a23c; color: #fff; }
.btn-red { background: #c0392b; color: #fff; }
.warning-box {
    padding: .75rem;
    background: #ffe6e6;
    border: 1px solid #f4b8b8;
    border-radius: 7px;
    margin-top: 1rem;
}
.warning-box p:first-child {
    font-size: .75rem;
    font-weight: 700;
    color: #c0392b;
    margin-bottom: .4rem;
}
.warning-box ul {
    list-style: none;
    padding: 0;
}
.warning-box li {
    font-size: .75rem;
    color: #a02f23;
    margin-bottom: .2rem;
}
</style>

<div class="item-details-page">

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="flash flash-success">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="flash flash-error">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <p>{{ session('error') }}</p>
        </div>
    @endif

    <!-- Main Grid -->
    <div class="details-grid">
        <!-- Left Column -->
        <div class="main-card">
            <!-- Item Header -->
            <div class="item-header">
                @if($item->image)
                    <img src="{{ asset('storage/'.$item->image) }}" alt="{{ $item->name }}" class="item-image">
                @else
                    <div class="item-icon-placeholder">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"/>
                            <path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                @endif

                <div class="item-title">
                    <h2>{{ $item->name }}</h2>
                    <div class="item-meta">
                        @if($item->category)
                            <span class="category-badge">{{ $item->category->name }}</span>
                        @endif
                        <span class="item-id">ID: #{{ $item->id }}</span>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="stat-box">
                    <p>Current Stock</p>
                    <p>{{ $item->quantity }}</p>
                    <p>{{ $item->unit_of_measure }}</p>
                </div>
                
                <div class="stat-box">
                    <p>Minimum Quantity</p>
                    <p>{{ $item->minimum_quantity }}</p>
                    <p>{{ $item->unit_of_measure }}</p>
                </div>
                
                <div class="stat-box">
                    <p>Stock Status</p>
                    <div style="margin-top:.3rem;">
                        @if($item->quantity == 0)
                            <span class="status-badge status-rejected">Out of Stock</span>
                        @elseif($item->quantity <= $item->minimum_quantity)
                            <span class="status-badge status-pending">Low Stock</span>
                        @else
                            <span class="status-badge status-approved">In Stock</span>
                        @endif
                    </div>
                </div>
                
                <div class="stat-box">
                    <p>Usage Rate</p>
                    <p>{{ $item->requestItems->count() }}</p>
                    <p>Total requests</p>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="progress-section">
                <div class="progress-header">
                    <span>Stock Level</span>
                    <span>{{ $item->quantity }} / {{ max($item->minimum_quantity * 2, $item->quantity + 10) }} {{ $item->unit_of_measure }}</span>
                </div>
                <div class="progress-bar-bg">
                    @php
                        $max = max($item->minimum_quantity * 2, $item->quantity + 10);
                        $percentage = $max > 0 ? min(100, ($item->quantity / $max) * 100) : 0;
                        $colorClass = $item->quantity == 0 ? 'red' : 
                                    ($item->quantity <= $item->minimum_quantity ? 'yellow' : 'green');
                    @endphp
                    <div class="progress-bar-fill {{ $colorClass }}" style="width: {{ $percentage }}%"></div>
                </div>
                <div class="progress-labels">
                    <span>Empty</span>
                    <span class="{{ $item->quantity <= $item->minimum_quantity ? 'reorder-point' : '' }}">
                        Re-order Point: {{ $item->minimum_quantity }}
                    </span>
                    <span>Full</span>
                </div>
            </div>

            <!-- Details -->
            @if($item->description)
                <div class="details-section">
                    <h4>Description</h4>
                    <p class="description-text">{{ $item->description }}</p>
                </div>
            @endif
            
            <div class="info-grid">
                <div class="info-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>Created: {{ $item->created_at->format('F j, Y g:i A') }}</span>
                </div>
                
                <div class="info-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Updated: {{ $item->updated_at->format('F j, Y g:i A') }}</span>
                </div>
            </div>
        </div>

        <!-- Right Sidebar -->
        <div class="sidebar">
            <!-- Quick Actions -->
            <div class="sidebar-card">
                <h3>Quick Actions</h3>
                <div class="action-list">
                    <button type="button" onclick="showRestockModal()" class="action-item action-yellow">
                        <div class="action-left">
                            <div class="action-icon yellow">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                            </div>
                            <div class="action-text">
                                <p>Restock Item</p>
                                <p>Add more quantity</p>
                            </div>
                        </div>
                        <div class="action-arrow">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </button>
                    
                    <a href="{{ route('admin.inventory.edit', $item) }}" class="action-item action-blue">
                        <div class="action-left">
                            <div class="action-icon blue">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </div>
                            <div class="action-text">
                                <p>Edit Details</p>
                                <p>Update information</p>
                            </div>
                        </div>
                        <div class="action-arrow">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </a>
                    
                    <button type="button" onclick="showDeleteModal()" class="action-item action-red">
                        <div class="action-left">
                            <div class="action-icon red">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </div>
                            <div class="action-text">
                                <p>Delete Item</p>
                                <p>Remove from inventory</p>
                            </div>
                        </div>
                        <div class="action-arrow">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </button>
                </div>
            </div>

            <!-- Related Items -->
            @if($item->category)
                @php
                    $relatedItems = \App\Models\Item::where('category_id', $item->category_id)
                        ->where('id', '!=', $item->id)
                        ->limit(3)
                        ->get();
                    $relatedCount = \App\Models\Item::where('category_id', $item->category_id)
                        ->where('id', '!=', $item->id)
                        ->count();
                @endphp

                @if($relatedItems->count() > 0)
                    <div class="sidebar-card">
                        <h3>Related Items</h3>
                        <div class="related-list">
                            @foreach($relatedItems as $relatedItem)
                                <a href="{{ route('admin.inventory.show', $relatedItem) }}" class="related-item">
                                    <div class="related-left">
                                        <div class="related-icon">
                                            <svg fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"/>
                                                <path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="related-name">{{ Str::limit($relatedItem->name, 25) }}</div>
                                            <div class="related-qty">{{ $relatedItem->quantity }} {{ $relatedItem->unit_of_measure }}</div>
                                        </div>
                                    </div>
                                    <div class="related-arrow">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </div>
                                </a>
                            @endforeach

                            @if($relatedCount > 3)
                                <a href="{{ route('admin.inventory.index') }}?category_id={{ $item->category_id }}" class="view-all-link">
                                    View all {{ $relatedCount }} items in this category →
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>

    <!-- Request History -->
    <div class="history-card">
        <div class="history-header">
            <h3>Request History</h3>
            <span class="history-count">{{ $item->requestItems->count() }}</span>
        </div>

        <div class="history-body">
            @if($item->requestItems->count() > 0)
                <div class="history-table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Request #</th>
                                <th>Requester</th>
                                <th>Date</th>
                                <th>Qty Requested</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($item->requestItems->sortByDesc('created_at')->take(10) as $requestItem)
                                <tr>
                                    <td style="font-weight:600;">#{{ $requestItem->itemRequest->id ?? '—' }}</td>
                                    <td>{{ optional($requestItem->itemRequest->user)->name ?? '—' }}</td>
                                    <td>{{ $requestItem->created_at->format('M d, Y') }}</td>
                                    <td>{{ $requestItem->quantity }} {{ $item->unit_of_measure }}</td>
                                    <td>
                                        @php
                                            $badgeClass = [
                                                'pending' => 'status-pending',
                                                'approved' => 'status-approved',
                                                'rejected' => 'status-rejected',
                                                'issued' => 'status-issued',
                                                'partially_issued' => 'status-partial'
                                            ][$requestItem->status] ?? 'status-pending';
                                        @endphp
                                        <span class="status-badge {{ $badgeClass }}">
                                            {{ ucfirst(str_replace('_', ' ', $requestItem->status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($requestItem->itemRequest)
                                            <a href="{{ route('admin.orders.review', $requestItem->itemRequest->id) }}" class="table-link">View</a>
                                        @else
                                            <span style="color:#9a9591;">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($item->requestItems->count() > 10)
                    <div style="margin-top:1rem;text-align:center;">
                        <a href="{{ route('admin.orders.pending') }}?item_id={{ $item->id }}" class="view-all-link">
                            View all {{ $item->requestItems->count() }} requests →
                        </a>
                    </div>
                @endif
            @else
                <div class="empty-state">
                    <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3>No request history</h3>
                    <p>This item has not been requested yet.</p>
                </div>
            @endif
        </div>
    </div>

</div>

<!-- Restock Modal -->
<div id="restockModal" class="modal">
    <div class="modal-content">
        <div class="modal-icon-wrap yellow">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
            </svg>
        </div>
        <h3 class="modal-title">Restock: {{ $item->name }}</h3>
        
        <form action="{{ route('admin.inventory.restock', $item) }}" method="POST" class="modal-form">
            @csrf
            
            <div class="form-field">
                <label for="restockQuantity">Quantity to Add *</label>
                <input type="number" id="restockQuantity" name="quantity" min="1" 
                       class="form-input" placeholder="Enter quantity" required>
            </div>
            
            <div class="form-field">
                <label for="restockNotes">Notes (Optional)</label>
                <textarea id="restockNotes" name="notes" rows="3" class="form-textarea"
                          placeholder="Add any notes about this restock..."></textarea>
            </div>
            
            <div class="stock-info">
                <div class="stock-row">
                    <span>Current Stock:</span>
                    <span>{{ $item->quantity }} {{ $item->unit_of_measure }}</span>
                </div>
                <div class="stock-row green">
                    <span>After Restock:</span>
                    <span id="afterRestock">—</span>
                </div>
            </div>
            
            <div class="modal-actions">
                <button type="button" onclick="hideRestockModal()" class="btn btn-muted">Cancel</button>
                <button type="submit" class="btn btn-yellow">Restock</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <div class="modal-icon-wrap red">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
        </div>
        <h3 class="modal-title">Delete Item</h3>
        
        <p style="text-align:center;font-size:.875rem;color:#6b6966;margin-bottom:.5rem;">
            Are you sure you want to delete "<span style="font-weight:600;color:var(--charcoal);">{{ $item->name }}</span>"?
        </p>
        <p style="text-align:center;font-size:.875rem;color:#c0392b;font-weight:600;">
            Warning: This action cannot be undone!
        </p>

        @if($item->requestItems()->exists())
            <div class="warning-box">
                <p>Cannot delete this item because:</p>
                <ul>
                    <li>• Has {{ $item->requestItems()->count() }} request history records</li>
                    <li>• Items with transaction history cannot be deleted</li>
                </ul>
            </div>
        @endif

        <div class="modal-actions" style="margin-top:1.5rem;">
            <button type="button" onclick="hideDeleteModal()" class="btn btn-muted">Cancel</button>
            
            @if($item->requestItems()->doesntExist())
                <form action="{{ route('admin.inventory.destroy', $item) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-red">Delete Item</button>
                </form>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const restockQuantity = document.getElementById('restockQuantity');
    const afterRestock = document.getElementById('afterRestock');
    
    if (restockQuantity && afterRestock) {
        restockQuantity.addEventListener('input', function() {
            const currentQty = {{ $item->quantity }};
            const addQty = parseInt(this.value) || 0;
            const unit = '{{ $item->unit_of_measure }}';
            afterRestock.textContent = `${currentQty + addQty} ${unit}`;
        });
    }
});

function showRestockModal() {
    const modal = document.getElementById('restockModal');
    modal.classList.add('show');
    document.getElementById('restockQuantity').value = '';
    document.getElementById('restockNotes').value = '';
    document.getElementById('afterRestock').textContent = '—';
}

function hideRestockModal() {
    document.getElementById('restockModal').classList.remove('show');
}

function showDeleteModal() {
    document.getElementById('deleteModal').classList.add('show');
}

function hideDeleteModal() {
    document.getElementById('deleteModal').classList.remove('show');
}

document.getElementById('restockModal').addEventListener('click', function(e) {
    if (e.target === this) hideRestockModal();
});

document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) hideDeleteModal();
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        hideRestockModal();
        hideDeleteModal();
    }
});
</script>

@endsection