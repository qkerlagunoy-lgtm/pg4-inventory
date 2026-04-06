@extends('layouts.admin')

@section('title', 'View Issuance')

@section('page-title', 'Issuance Details')

@section('content')

<style>
:root {
    --cream:    #FAF7F0;
    --sand:     #D8D2C2;
    --sienna:   #B17457;
    --charcoal: #4A4947;
}

.view-issuance-page {
    background: var(--cream);
    padding: 2rem;
    font-family: 'Georgia', serif;
    min-height: 100vh;
}

/* ── HEADER CARD ── */
.header-card {
    background: #fff;
    border: 1px solid var(--sand);
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 1.5rem;
}
.header-top {
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid var(--sand);
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.header-title h2 {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--charcoal);
}
.header-title p {
    font-size: .85rem;
    color: #6b6966;
    margin-top: .2rem;
}
.header-actions {
    display: flex;
    align-items: center;
    gap: .75rem;
}
.badge {
    padding: .5rem 1rem;
    border-radius: 20px;
    font-size: .8rem;
    font-weight: 700;
    letter-spacing: .04em;
}
.badge-completed { background: #eef6ee; color: #2e7d32; }
.badge-partial { background: #fff4e6; color: #c77d11; }
.badge-pending { background: #d9ebf7; color: #2d5f8a; }
.btn {
    padding: .5rem 1rem;
    font-size: .875rem;
    font-weight: 600;
    border-radius: 7px;
    border: none;
    cursor: pointer;
    transition: opacity .15s;
    font-family: inherit;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: .4rem;
}
.btn:hover { opacity: .88; }
.btn-primary { background: var(--sienna); color: #fff; }
.btn-muted { background: #f5f1e8; color: var(--charcoal); border: 1px solid var(--sand); }
.btn-green { background: #4a8c4a; color: #fff; }
.btn svg { width: 1rem; height: 1rem; }

/* ── INFO GRID ── */
.info-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
    padding: 1.5rem;
}
@media (max-width: 768px) {
    .info-grid { grid-template-columns: 1fr; }
}
.info-section h3 {
    font-size: .9rem;
    font-weight: 700;
    color: #6b6966;
    margin-bottom: 1rem;
}
.info-item {
    margin-bottom: .85rem;
}
.info-item p:first-child {
    font-size: .75rem;
    color: #9a9591;
    margin-bottom: .25rem;
}
.info-item p:nth-child(2) {
    font-size: .875rem;
    font-weight: 600;
    color: var(--charcoal);
}
.info-item p:last-child {
    font-size: .8rem;
    color: #6b6966;
    margin-top: .1rem;
}
.info-stat {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: .85rem;
    color: #6b6966;
    margin-bottom: .5rem;
}
.info-stat span:last-child {
    font-weight: 600;
    color: var(--charcoal);
}

/* ── TABLE CARD ── */
.table-card {
    background: #fff;
    border: 1px solid var(--sand);
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 1.5rem;
}
.table-header {
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid var(--sand);
}
.table-header h3 {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--charcoal);
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
tbody td {
    padding: .85rem 1.2rem;
    color: var(--charcoal);
    vertical-align: middle;
}
tfoot tr { background: var(--cream); border-top: 2px solid var(--sand); }
tfoot td {
    padding: .85rem 1.2rem;
    font-weight: 600;
    color: var(--charcoal);
}

/* ── TABLE CELLS ── */
.item-name { font-weight: 600; }
.item-code { font-size: .75rem; color: #6b6966; margin-top: .15rem; }
.cat-badge {
    display: inline-block;
    padding: .2rem .5rem;
    background: #f5f1e8;
    color: var(--charcoal);
    border-radius: 20px;
    font-size: .7rem;
    font-weight: 600;
}
.status-badge {
    display: inline-block;
    padding: .25rem .6rem;
    border-radius: 20px;
    font-size: .7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .04em;
}
.status-returned { background: #eef6ee; color: #2e7d32; }
.status-issued { background: #d9ebf7; color: #2d5f8a; }
.status-lost { background: #ffe6e6; color: #c0392b; }
.status-damaged { background: #ffe6e6; color: #d87070; }
.overdue { color: #c0392b; font-weight: 600; }
.overdue-label {
    font-size: .7rem;
    color: #c0392b;
    margin-left: .3rem;
}
.no-due { color: #9a9591; }
.action-link {
    color: var(--sienna);
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    margin-right: .75rem;
}
.action-link:hover { text-decoration: underline; }
.action-view { color: #6b6966; }

/* ── EMPTY STATE ── */
.empty-state {
    padding: 3rem 1rem;
    text-align: center;
}
.empty-state svg { color: var(--sand); margin: 0 auto 1rem; }
.empty-state h3 { font-size: 1rem; font-weight: 600; color: var(--charcoal); margin-bottom: .25rem; }
.empty-state p { font-size: .875rem; color: #9a9591; }

/* ── ACTIVITY LOG ── */
.activity-card {
    background: #fff;
    border: 1px solid var(--sand);
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 1.5rem;
}
.activity-header {
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid var(--sand);
}
.activity-header h3 {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--charcoal);
}
.activity-list {
    padding: 1.5rem;
}
.activity-item {
    position: relative;
    padding-bottom: 2rem;
}
.activity-item:last-child { padding-bottom: 0; }
.activity-line {
    position: absolute;
    top: 1.75rem;
    left: 1rem;
    height: calc(100% - 1rem);
    width: 2px;
    background: var(--sand);
}
.activity-content {
    position: relative;
    display: flex;
    gap: .75rem;
}
.activity-icon {
    width: 2rem;
    height: 2rem;
    border-radius: 50%;
    background: #d9ebf7;
    color: #2d5f8a;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    border: 4px solid #fff;
}
.activity-icon svg { width: 1.15rem; height: 1.15rem; }
.activity-text {
    flex: 1;
    padding-top: .2rem;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}
.activity-desc {
    font-size: .85rem;
    color: #6b6966;
}
.activity-time {
    font-size: .75rem;
    color: #9a9591;
    white-space: nowrap;
}

/* ── ACTION CARD ── */
.action-card {
    background: #fff;
    border: 1px solid var(--sand);
    border-radius: 10px;
    padding: 1.5rem;
    display: flex;
    justify-content: space-between;
}
.action-left, .action-right {
    display: flex;
    gap: .75rem;
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
}
.modal.show { display: flex; }
.modal-content {
    background: #fff;
    border: 1px solid var(--sand);
    border-radius: 10px;
    max-width: 32rem;
    width: 100%;
    padding: 1.5rem;
    margin: 1rem;
}
.modal-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--charcoal);
    margin-bottom: 1rem;
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
}
.form-textarea {
    resize: vertical;
}
.modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: .75rem;
    margin-top: 1.5rem;
}

@media print {
    header, footer, .no-print, .action-card, .btn, .action-link {
        display: none !important;
    }
    body { font-size: 12pt; }
    .view-issuance-page { padding: 0; }
}
</style>

<div class="view-issuance-page">
    <!-- Header Card -->
    <div class="header-card">
        <div class="header-top">
            <div class="header-title">
                <h2>Issuance #{{ $issuance->issuance_code ?? $issuance->id }}</h2>
                <p>Issued on {{ $issuance->issued_at->format('F d, Y h:i A') }}</p>
            </div>
            <div class="header-actions">
                <span class="badge 
                    @if($issuance->status == 'completed') badge-completed
                    @elseif($issuance->status == 'partially_issued') badge-partial
                    @else badge-pending @endif">
                    {{ ucfirst(str_replace('_', ' ', $issuance->status)) }}
                </span>
                
                @if($issuance->canReturnItems())
                <button onclick="showReturnModal()" class="btn btn-primary">
                    Process Return
                </button>
                @endif
            </div>
        </div>
        
        <div class="info-grid">
            <!-- Request Info -->
            <div class="info-section">
                <h3>Request Information</h3>
                <div class="info-item">
                    <p>Request ID</p>
                    <p>#{{ $issuance->itemRequest->id }}</p>
                </div>
                <div class="info-item">
                    <p>Requester</p>
                    <p>{{ $issuance->itemRequest->user->name }}</p>
                    <p>{{ $issuance->itemRequest->user->unit }}</p>
                </div>
                <div class="info-item">
                    <p>Purpose</p>
                    <p>{{ $issuance->itemRequest->purpose }}</p>
                </div>
            </div>
            
            <!-- Issuance Details -->
            <div class="info-section">
                <h3>Issuance Details</h3>
                <div class="info-item">
                    <p>Issued By</p>
                    <p>{{ $issuance->issuer->name ?? 'N/A' }}</p>
                </div>
                <div class="info-item">
                    <p>Issuance Date</p>
                    <p>{{ $issuance->issued_at->format('F d, Y') }}</p>
                </div>
                <div class="info-item">
                    <p>Total Items</p>
                    <p>{{ $issuance->issuanceItems->count() }}</p>
                </div>
            </div>
            
            <!-- Status Overview -->
            <div class="info-section">
                <h3>Status Overview</h3>
                <div class="info-stat">
                    <span>Issued Items</span>
                    <span>{{ $issuance->totalItemsIssued() }}</span>
                </div>
                <div class="info-stat">
                    <span>Returned Items</span>
                    <span>{{ $issuance->totalItemsReturned() }}</span>
                </div>
                <div class="info-stat">
                    <span>Pending Returns</span>
                    <span>{{ $issuance->pendingReturnsCount() }}</span>
                </div>
                @if($issuance->remarks)
                <div class="info-item" style="margin-top:.75rem;">
                    <p>Remarks</p>
                    <p>{{ $issuance->remarks }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Items Table -->
    <div class="table-card">
        <div class="table-header">
            <h3>Issued Items</h3>
        </div>
        
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Category</th>
                        <th>Requested Qty</th>
                        <th>Issued Qty</th>
                        <th>Returned Qty</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th class="no-print">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($issuance->issuanceItems as $item)
                    <tr>
                        <td>
                            <div class="item-name">{{ $item->item->name }}</div>
                            <div class="item-code">{{ $item->item->item_code }}</div>
                        </td>
                        <td>
                            <span class="cat-badge">{{ $item->item->category->name ?? 'N/A' }}</span>
                        </td>
                        <td>{{ $item->requested_quantity ?? $item->quantity_issued }}</td>
                        <td style="font-weight:600;">{{ $item->quantity_issued }}</td>
                        <td>{{ $item->quantity_returned ?? '0' }}</td>
                        <td>
                            @if($item->due_date)
                                <span class="{{ $item->isOverdue() ? 'overdue' : '' }}">
                                    {{ $item->due_date->format('M d, Y') }}
                                    @if($item->isOverdue())
                                        <span class="overdue-label">(Overdue)</span>
                                    @endif
                                </span>
                            @else
                                <span class="no-due">No due date</span>
                            @endif
                        </td>
                        <td>
                            <span class="status-badge 
                                @if($item->status == 'returned') status-returned
                                @elseif($item->status == 'issued') status-issued
                                @elseif($item->status == 'lost') status-lost
                                @elseif($item->status == 'damaged') status-damaged
                                @endif">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                        <td class="no-print">
                            @if($item->status == 'issued')
                            <a onclick="showItemReturnModal({{ $item->id }})" class="action-link">
                                Return Item
                            </a>
                            @endif
                            
                            @if($item->quantity_returned > 0)
                            <a onclick="showReturnDetails({{ $item->id }})" class="action-link action-view">
                                View Details
                            </a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                @if($issuance->issuanceItems->count() > 0)
                <tfoot>
                    <tr>
                        <td colspan="3" style="text-align:right;">Totals:</td>
                        <td>{{ $issuance->issuanceItems->sum('quantity_issued') }}</td>
                        <td>{{ $issuance->issuanceItems->sum('quantity_returned') }}</td>
                        <td colspan="3"></td>
                    </tr>
                </tfoot>
                @endif
            </table>
            
            @if($issuance->issuanceItems->isEmpty())
            <div class="empty-state">
                <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <h3>No items issued</h3>
                <p>No items have been issued for this request.</p>
            </div>
            @endif
        </div>
    </div>
    
    <!-- Activity Log -->
    @if($issuance->activityLogs && $issuance->activityLogs->count() > 0)
    <div class="activity-card no-print">
        <div class="activity-header">
            <h3>Activity Log</h3>
        </div>
        <div class="activity-list">
            @foreach($issuance->activityLogs as $log)
            <div class="activity-item">
                @if(!$loop->last)
                <span class="activity-line"></span>
                @endif
                <div class="activity-content">
                    <div class="activity-icon">
                        <svg fill="currentColor" viewBox="0 0 20 20">
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
                    </div>
                    <div class="activity-text">
                        <p class="activity-desc">{{ $log->description }}</p>
                        <time class="activity-time">{{ $log->created_at->diffForHumans() }}</time>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    
    <!-- Action Buttons -->
    <div class="action-card no-print">
        <div class="action-left">
            <a href="{{ route('admin.orders.issuances') }}" class="btn btn-muted">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Issuances
            </a>
        </div>
        <div class="action-right">
            @if($issuance->status != 'completed')
            <form action="{{ route('admin.orders.complete-issuance', $issuance->id) }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" onclick="return confirm('Mark this issuance as completed?')" class="btn btn-green">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Mark as Completed
                </button>
            </form>
            @endif
            
            <button onclick="window.print()" class="btn btn-primary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Print Issuance
            </button>
        </div>
    </div>
</div>

<!-- Return Modal -->
<div id="returnModal" class="modal">
    <div class="modal-content">
        <h3 class="modal-title">Process Item Return</h3>
        
        <form id="returnForm" method="POST" action="">
            @csrf
            
            <div class="form-field">
                <label class="form-label">Item</label>
                <select id="itemSelect" name="item_id" class="form-select">
                    @foreach($issuance->issuanceItems->where('status', 'issued') as $item)
                    <option value="{{ $item->id }}">{{ $item->item->name }} (Issued: {{ $item->quantity_issued }})</option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-field">
                <label class="form-label">Returned Quantity</label>
                <input type="number" name="returned_quantity" min="1" class="form-input">
            </div>
            
            <div class="form-field">
                <label class="form-label">Condition</label>
                <select name="condition" class="form-select">
                    <option value="good">Good</option>
                    <option value="damaged">Damaged</option>
                    <option value="lost">Lost</option>
                </select>
            </div>
            
            <div class="form-field">
                <label class="form-label">Notes</label>
                <textarea name="notes" rows="3" class="form-textarea"></textarea>
            </div>
            
            <div class="modal-actions">
                <button type="button" onclick="closeReturnModal()" class="btn btn-muted">
                    Cancel
                </button>
                <button type="submit" class="btn btn-primary">
                    Process Return
                </button>
            </div>
        </form>
    </div>
</div>

<script>function showReturnModal() {
    const select = document.getElementById('itemSelect');
    if (!select || !select.value) return;
    document.getElementById('returnForm').action = `/admin/orders/process-return/${select.value}`;
    document.getElementById('returnModal').classList.add('show');
}

function showItemReturnModal(itemId) {
    document.getElementById('returnModal').classList.add('show');
    document.getElementById('itemSelect').value = itemId;
    document.getElementById('returnForm').action = `/admin/orders/process-return/${itemId}`;
}

function closeReturnModal() {
    document.getElementById('returnModal').classList.remove('show');
}

function showReturnDetails(itemId) {
    alert('Return details for item #' + itemId);
}

document.addEventListener('DOMContentLoaded', function() {
    const select = document.getElementById('itemSelect');
    if (select) {
        select.addEventListener('change', function() {
            document.getElementById('returnForm').action = `/admin/orders/process-return/${this.value}`;
        });
        // Set initial action
        if (select.value) {
            document.getElementById('returnForm').action = `/admin/orders/process-return/${select.value}`;
        }
    }

    const modal = document.getElementById('returnModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) closeReturnModal();
        });
    }
});
</script>


@endsection