@extends('layouts.admin')

@section('title', 'Item Returns Management')

@section('page-title', 'Item Returns & Tracking')

@section('content')

<style>
:root {
    --cream:    #FAF7F0;
    --sand:     #D8D2C2;
    --sienna:   #B17457;
    --charcoal: #4A4947;
}

.returns-page {
    background: var(--cream);
    padding: 2rem;
    font-family: 'Georgia', serif;
    min-height: 100vh;
}

/* ── FLASH ── */
.flash {
    display: flex;
    align-items: center;
    gap: .5rem;
    padding: .85rem 1rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    font-size: .875rem;
    font-weight: 600;
    border: 1px solid;
}
.flash-success { background: #f0faf0; border-color: #6aab6a; color: #2e6b2e; }
.flash-error { background: #fff0f0; border-color: #d87070; color: #8b2020; }

/* ── HEADER ── */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1.5rem;
}
.page-header h1 {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--charcoal);
}
.page-header p {
    font-size: .875rem;
    color: #6b6966;
    margin-top: .25rem;
}
.header-badges {
    display: flex;
    gap: .5rem;
}
.count-badge {
    padding: .35rem .75rem;
    border-radius: 20px;
    font-size: .8rem;
    font-weight: 600;
}
.badge-blue { background: #d9ebf7; color: #2d5f8a; }
.badge-red { background: #ffe6e6; color: #c0392b; }

/* ── FILTER CARD ── */
.filter-card {
    background: #fff;
    border: 1px solid var(--sand);
    border-radius: 10px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}
.filter-grid {
    display: grid;
    grid-template-columns: 1fr 1fr auto;
    gap: 1rem;
    align-items: end;
}
.filter-field label {
    display: block;
    font-size: .8rem;
    font-weight: 600;
    color: var(--charcoal);
    margin-bottom: .4rem;
}
.filter-input, .filter-select {
    width: 100%;
    padding: .5rem .9rem;
    border: 1px solid var(--sand);
    border-radius: 7px;
    font-size: .875rem;
    background: var(--cream);
    color: var(--charcoal);
    outline: none;
    font-family: inherit;
    transition: border-color .2s;
}
.filter-input:focus, .filter-select:focus { border-color: var(--sienna); }
.filter-actions {
    display: flex;
    gap: .5rem;
}
.btn {
    padding: .5rem 1rem;
    font-size: .875rem;
    font-weight: 600;
    border-radius: 7px;
    border: none;
    cursor: pointer;
    transition: opacity .15s;
    font-family: inherit;
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    text-decoration: none;
}
.btn:hover { opacity: .88; }
.btn svg { width: 1rem; height: 1rem; }
.btn-primary { background: var(--sienna); color: #fff; }
.btn-muted { background: #f5f1e8; color: var(--charcoal); }

/* ── TABLE CARD ── */
.table-card {
    background: #fff;
    border: 1px solid var(--sand);
    border-radius: 10px;
    overflow: hidden;
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
tbody tr { border-bottom: 1px solid #f0ece4; transition: background .12s; }
tbody tr:last-child { border-bottom: none; }
tbody tr:hover { background: #fdfbf7; }
tbody tr.overdue-row { background: rgba(254, 226, 226, 0.3); }
tbody tr.overdue-row:hover { background: rgba(254, 226, 226, 0.5); }
tbody td {
    padding: .85rem 1.2rem;
    color: var(--charcoal);
    vertical-align: middle;
}

/* ── TABLE CELLS ── */
.item-cell { display: flex; align-items: center; gap: .75rem; }
.item-icon {
    width: 2.5rem;
    height: 2.5rem;
    background: #d9ebf7;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.item-icon svg { width: 1.4rem; height: 1.4rem; color: #2d5f8a; }
.item-info .item-name {
    font-size: .875rem;
    font-weight: 600;
    color: var(--charcoal);
}
.item-info .item-issuance {
    font-size: .75rem;
    color: #6b6966;
    margin-top: .15rem;
}
.user-name { font-size: .875rem; font-weight: 500; color: var(--charcoal); }
.user-unit { font-size: .75rem; color: #6b6966; margin-top: .15rem; }
.qty-issued { font-weight: 600; }
.qty-returned { color: #2e7d32; margin-left: .5rem; }
.due-date { display: flex; align-items: center; }
.due-normal { color: var(--charcoal); }
.due-overdue { color: #c0392b; }
.due-main { font-size: .875rem; font-weight: 500; }
.due-sub {
    font-size: .75rem;
    margin-top: .15rem;
    display: flex;
    align-items: center;
    gap: .25rem;
}
.due-sub svg { width: .95rem; height: .95rem; }
.no-due { font-size: .875rem; color: #9a9591; }

/* ── STATUS BADGES ── */
.status-badge {
    display: inline-block;
    padding: .25rem .6rem;
    border-radius: 20px;
    font-size: .7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .04em;
}
.status-overdue { background: #ffe6e6; color: #c0392b; }
.status-pending { background: #fff4e6; color: #c77d11; }
.status-returned { background: #eef6ee; color: #2e7d32; }
.status-lost { background: #ffe6e6; color: #c0392b; }

/* ── ACTION LINKS ── */
.action-link {
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    margin-right: .75rem;
    transition: opacity .15s;
}
.action-link:hover { opacity: .8; }
.action-return { color: var(--sienna); }
.action-view { color: #6b6966; }

/* ── EMPTY STATE ── */
.empty-state {
    padding: 3rem 1rem;
    text-align: center;
}
.empty-state svg { color: var(--sand); margin: 0 auto 1rem; }
.empty-state h3 { font-size: 1rem; font-weight: 600; color: var(--charcoal); margin-bottom: .25rem; }
.empty-state p { font-size: .875rem; color: #9a9591; }

/* ── PAGINATION ── */
.pagination-wrap {
    padding: 1rem 1.5rem;
    border-top: 1px solid var(--sand);
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
    max-width: 32rem;
    width: calc(100% - 2rem);
    padding: 1.5rem;
    margin: 1rem;
    position: relative;
}
.modal-close {
    position: absolute;
    right: 1rem;
    top: 1rem;
    background: none;
    border: none;
    color: #9a9591;
    cursor: pointer;
    padding: .25rem;
}
.modal-close:hover { color: var(--charcoal); }
.modal-close svg { width: 1.5rem; height: 1.5rem; }
.modal-header {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    margin-bottom: 1.5rem;
}
.modal-icon {
    width: 2.5rem;
    height: 2.5rem;
    background: #d9ebf7;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.modal-icon svg { width: 1.4rem; height: 1.4rem; color: #2d5f8a; }
.modal-title { flex: 1; }
.modal-title h3 {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--charcoal);
    margin-bottom: .5rem;
}
.modal-title p {
    font-size: .85rem;
    color: #6b6966;
    line-height: 1.5;
}
.modal-title p span {
    font-weight: 600;
    color: var(--charcoal);
}
.form-field {
    margin-bottom: 1rem;
}
.form-label {
    display: block;
    font-size: .8rem;
    font-weight: 600;
    color: var(--charcoal);
    margin-bottom: .4rem;
}
.form-input, .form-select, .form-textarea {
    width: 100%;
    padding: .5rem .75rem;
    border: 1px solid var(--sand);
    border-radius: 7px;
    font-size: .875rem;
    background: var(--cream);
    color: var(--charcoal);
    outline: none;
    font-family: inherit;
    transition: border-color .2s;
}
.form-input:focus, .form-select:focus, .form-textarea:focus {
    border-color: var(--sienna);
}
.form-textarea {
    resize: vertical;
}
.form-hint {
    font-size: .75rem;
    color: #9a9591;
    margin-top: .3rem;
}
.form-hint span {
    font-weight: 600;
    color: var(--charcoal);
}
.modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: .75rem;
    margin-top: 1.5rem;
}

@media (max-width: 768px) {
    .page-header { flex-direction: column; }
    .filter-grid { grid-template-columns: 1fr; }
}
</style>

<div class="returns-page">

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="flash flash-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="flash flash-error">
            {{ session('error') }}
        </div>
    @endif

    <!-- Header -->
    <div class="page-header">
        <div>
            <h1>Item Returns Management</h1>
            <p>Track and manage issued items that need to be returned</p>
        </div>
        <div class="header-badges">
            <span class="count-badge badge-blue">
                Total Items: {{ $issuanceItems->total() }}
            </span>
            @php
                $overdueCount = collect($issuanceItems->items())->filter(function($item) {
                    return $item->due_date && $item->due_date->lt(now());
                })->count();
            @endphp
            <span class="count-badge badge-red">
                Overdue: {{ $overdueCount }}
            </span>
        </div>
    </div>

    <!-- Filters -->
    <div class="filter-card">
        <form method="GET" action="{{ route('admin.orders.returns') }}" class="filter-grid">
            <div class="filter-field">
                <label for="search">Search</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}"
                       placeholder="Search by item name or user..." class="filter-input">
            </div>

            <div class="filter-field">
                <label for="overdue">Filter</label>
                <select name="overdue" id="overdue" class="filter-select">
                    <option value="">All Items</option>
                    <option value="1" {{ request('overdue') == '1' ? 'selected' : '' }}>Overdue Items Only</option>
                    <option value="0" {{ request('overdue') == '0' ? 'selected' : '' }}>Not Overdue</option>
                </select>
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn btn-primary">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                    </svg>
                    Apply
                </button>
                <a href="{{ route('admin.orders.returns') }}" class="btn btn-muted">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="table-card">
        @if($issuanceItems->count() > 0)
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Item Details</th>
                            <th>Issued To</th>
                            <th>Quantity</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($issuanceItems as $item)
                            @php
                                $isOverdue = $item->due_date && $item->due_date->lt(now());
                                $daysLeft = $item->due_date ? now()->diffInDays($item->due_date, false) : null;
                            @endphp
                            <tr class="{{ $isOverdue ? 'overdue-row' : '' }}">
                                <td>
                                    <div class="item-cell">
                                        <div class="item-icon">
                                            <svg fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"/>
                                                <path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <div class="item-info">
                                            <div class="item-name">{{ $item->item->name }}</div>
                                            <div class="item-issuance">Issuance #{{ $item->issuance->id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="user-name">
                                        {{ $item->issuance->itemRequest->user->first_name }} 
                                        {{ $item->issuance->itemRequest->user->last_name }}
                                    </div>
                                    <div class="user-unit">{{ $item->issuance->itemRequest->user->unit ?? 'N/A' }}</div>
                                </td>
                                <td>
                                    <span class="qty-issued">{{ $item->quantity_issued }}</span> issued
                                    @if($item->quantity_returned)
                                        <span class="qty-returned">
                                            ({{ $item->quantity_returned }} returned)
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->due_date)
                                        <div class="due-date {{ $isOverdue ? 'due-overdue' : 'due-normal' }}">
                                            <div>
                                                <div class="due-main">{{ $item->due_date->format('M d, Y') }}</div>
                                                <div class="due-sub">
                                                    @if($isOverdue)
                                                        <svg fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                        </svg>
                                                        Overdue by {{ abs($daysLeft) }} days
                                                    @else
                                                        Due in {{ $daysLeft }} days
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="no-due">No due date</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->status === 'issued')
                                        <span class="status-badge {{ $isOverdue ? 'status-overdue' : 'status-pending' }}">
                                            {{ $isOverdue ? 'Overdue' : 'Pending Return' }}
                                        </span>
                                    @elseif($item->status === 'returned')
                                        <span class="status-badge status-returned">Returned</span>
                                    @elseif($item->status === 'lost')
                                        <span class="status-badge status-lost">Lost</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->status === 'issued')
                                        <a onclick="openReturnModal({{ json_encode([
                                            'id' => $item->id,
                                            'item_name' => $item->item->name,
                                            'quantity_issued' => $item->quantity_issued,
                                            'user_name' => $item->issuance->itemRequest->user->first_name . ' ' . $item->issuance->itemRequest->user->last_name,
                                            'issuance_id' => $item->issuance->id
                                        ]) }})" class="action-link action-return">
                                            Process Return
                                        </a>
                                    @endif
                                    
                                    <a href="{{ route('admin.orders.issuances.view', $item->issuance->id) }}" class="action-link action-view">
                                        View Issuance
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pagination-wrap">
                {{ $issuanceItems->withQueryString()->links() }}
            </div>
        @else
            <div class="empty-state">
                <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <h3>No items pending return</h3>
                <p>All issued items have been returned or don't have return requirements.</p>
            </div>
        @endif
    </div>
</div>

<!-- Return Modal -->
<div id="returnModal" class="modal">
    <div class="modal-content">
        <button type="button" onclick="closeReturnModal()" class="modal-close">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        
        <div class="modal-header">
            <div class="modal-icon">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="modal-title">
                <h3>Process Item Return</h3>
                <p>
                    Item: <span id="modal-item-name"></span><br>
                    Issued to: <span id="modal-user-name"></span><br>
                    Issued quantity: <span id="modal-quantity"></span>
                </p>
            </div>
        </div>
        
        <form id="returnForm" method="POST">
            @csrf
            <input type="hidden" name="_method" value="POST">
            
            <div class="form-field">
                <label for="returned_quantity" class="form-label">Returned Quantity *</label>
                <input type="number" name="returned_quantity" id="returned_quantity" min="1" required class="form-input">
                <p class="form-hint">Maximum: <span id="max-quantity"></span></p>
            </div>
            
            <div class="form-field">
                <label for="condition" class="form-label">Item Condition *</label>
                <select name="condition" id="condition" required class="form-select">
                    <option value="">Select condition...</option>
                    <option value="good">Good - Restock to inventory</option>
                    <option value="damaged">Damaged - Do not restock</option>
                    <option value="lost">Lost</option>
                </select>
            </div>
            
            <div class="form-field">
                <label for="notes" class="form-label">Notes</label>
                <textarea name="notes" id="notes" rows="3" placeholder="Any additional notes about the return..." class="form-textarea"></textarea>
            </div>
            
            <div class="modal-actions">
                <button type="button" onclick="closeReturnModal()" class="btn btn-muted">Cancel</button>
                <button type="submit" class="btn btn-primary">Process Return</button>
            </div>
        </form>
    </div>
</div>

<script>
let currentItemId = null;
let maxQuantity = 0;

function openReturnModal(itemData) {
    currentItemId = itemData.id;
    maxQuantity = itemData.quantity_issued;
    
    document.getElementById('modal-item-name').textContent = itemData.item_name;
    document.getElementById('modal-user-name').textContent = itemData.user_name;
    document.getElementById('modal-quantity').textContent = itemData.quantity_issued;
    document.getElementById('max-quantity').textContent = itemData.quantity_issued;
    
    const form = document.getElementById('returnForm');
    form.action = `{{ url('admin/orders/process-return') }}/${itemData.id}`;
    
    form.reset();
    document.getElementById('returned_quantity').max = maxQuantity;
    document.getElementById('returned_quantity').value = maxQuantity;
    
    document.getElementById('returnModal').classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeReturnModal() {
    document.getElementById('returnModal').classList.remove('show');
    document.body.style.overflow = '';
    currentItemId = null;
}

document.getElementById('returnModal').addEventListener('click', function(e) {
    if (e.target.id === 'returnModal') closeReturnModal();
});

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
    
    if (condition === 'lost') {
        if (!confirm('Marking this item as LOST. This cannot be undone. Continue?')) {
            e.preventDefault();
            return false;
        }
    }
});
</script>

@endsection