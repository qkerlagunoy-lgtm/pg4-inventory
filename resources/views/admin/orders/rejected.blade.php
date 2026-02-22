@extends('layouts.admin')

@section('title', 'Rejected Requests')

@section('page-title', 'Rejected Requests')

@section('content')

<style>
:root {
    --cream:    #FAF7F0;
    --sand:     #D8D2C2;
    --sienna:   #B17457;
    --charcoal: #4A4947;
}

.rejected-page {
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

/* ── HEADER CARD ── */
.header-card {
    background: #fff;
    border: 1px solid var(--sand);
    border-radius: 10px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.header-left h1 {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--charcoal);
}
.header-left p {
    font-size: .875rem;
    color: #6b6966;
    margin-top: .25rem;
}
.header-badge {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    padding: .5rem 1rem;
    background: #ffe6e6;
    color: #c0392b;
    border-radius: 20px;
    font-size: .85rem;
    font-weight: 600;
}
.header-badge svg { width: 1rem; height: 1rem; }

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
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
    margin-bottom: 1rem;
}
.filter-field label {
    display: block;
    font-size: .8rem;
    font-weight: 600;
    color: var(--charcoal);
    margin-bottom: .4rem;
}
.filter-input {
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
.filter-input:focus { border-color: var(--sienna); }
.filter-actions {
    display: flex;
    justify-content: flex-end;
    gap: .75rem;
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
    text-decoration: none;
    display: inline-block;
}
.btn:hover { opacity: .88; }
.btn-primary { background: var(--sienna); color: #fff; }
.btn-muted { background: #f5f1e8; color: var(--charcoal); border: 1px solid var(--sand); }

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
tbody td {
    padding: .85rem 1.2rem;
    color: var(--charcoal);
    vertical-align: middle;
}

/* ── TABLE CELLS ── */
.req-id { font-weight: 600; }
.req-date { font-size: .8rem; color: #6b6966; margin-top: .15rem; }
.user-cell { display: flex; align-items: center; gap: .75rem; }
.user-avatar {
    width: 2.5rem;
    height: 2.5rem;
    background: linear-gradient(135deg, var(--sienna), #8a5a40);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: .8rem;
    font-weight: 700;
    flex-shrink: 0;
}
.user-name { font-weight: 600; }
.user-unit { font-size: .8rem; color: #6b6966; margin-top: .15rem; }
.urgent-badge {
    display: inline-flex;
    align-items: center;
    padding: .2rem .5rem;
    background: #ffe6e6;
    color: #c0392b;
    border-radius: 20px;
    font-size: .7rem;
    font-weight: 700;
    margin-top: .3rem;
}
.item-count { font-weight: 500; }
.item-qty { font-size: .8rem; color: #6b6966; margin-top: .15rem; }
.reason-text {
    max-width: 20rem;
    font-size: .875rem;
}
.reason-link {
    font-size: .8rem;
    color: var(--sienna);
    cursor: pointer;
    margin-top: .3rem;
    display: inline-block;
}
.reason-link:hover { text-decoration: underline; }
.date-main { font-size: .875rem; }
.date-time { font-size: .8rem; color: #6b6966; margin-top: .15rem; }
.date-relative { font-size: .75rem; color: #9a9591; margin-top: .15rem; }

/* ── ACTION ICONS ── */
.actions { display: flex; gap: .5rem; }
.action-icon {
    color: inherit;
    cursor: pointer;
    transition: opacity .15s;
}
.action-icon:hover { opacity: .7; }
.action-icon svg { width: 1.15rem; height: 1.15rem; }
.action-view { color: var(--sienna); }
.action-export { color: #4a8c4a; }

/* ── EMPTY STATE ── */
.empty-state {
    padding: 3rem 1rem;
    text-align: center;
}
.empty-state svg { color: var(--sand); margin: 0 auto 1rem; }
.empty-state h3 { font-size: 1rem; font-weight: 600; color: var(--charcoal); margin-bottom: .25rem; }
.empty-state p { font-size: .875rem; color: #9a9591; margin-bottom: 1.5rem; }

/* ── PAGINATION ── */
.pagination-wrap {
    padding: 1rem 1.5rem;
    border-top: 1px solid var(--sand);
}

/* ── STATS GRID ── */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
    margin-top: 1.5rem;
}
@media (max-width: 768px) {
    .stats-grid { grid-template-columns: 1fr; }
}
.stat-card {
    background: var(--bg);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 1rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}
.stat-card.red { --bg: #fff0f0; --border: #f4b8b8; }
.stat-card.yellow { --bg: #fff4e6; --border: #e6ccb3; }
.stat-card.blue { --bg: #d9ebf7; --border: #6ba3d4; }
.stat-icon {
    width: 2.5rem;
    height: 2.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.stat-icon svg { width: 1.75rem; height: 1.75rem; }
.stat-icon.red { color: #c0392b; }
.stat-icon.yellow { color: #c77d11; }
.stat-icon.blue { color: #2d5f8a; }
.stat-text p:first-child {
    font-size: .8rem;
    font-weight: 600;
    color: var(--text);
    margin-bottom: .3rem;
}
.stat-text p:last-child {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--charcoal);
}
.stat-card.red .stat-text { --text: #a02f23; }
.stat-card.yellow .stat-text { --text: #a06611; }
.stat-card.blue .stat-text { --text: #2d5f8a; }

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
    max-width: 28rem;
    width: calc(100% - 2rem);
    padding: 1.5rem;
    margin: 1rem;
}
.modal-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--charcoal);
    margin-bottom: 1rem;
}
.modal-reason {
    padding: 1rem;
    background: #f5f1e8;
    border-radius: 8px;
    margin-bottom: 1rem;
}
.modal-reason p {
    font-size: .875rem;
    color: #6b6966;
    line-height: 1.5;
}
.modal-close {
    width: 100%;
    padding: .5rem 1rem;
    background: #f5f1e8;
    color: var(--charcoal);
    border: 1px solid var(--sand);
    border-radius: 7px;
    font-size: .875rem;
    font-weight: 600;
    cursor: pointer;
    transition: background .15s;
}
.modal-close:hover { background: #ebe6d9; }

@media (max-width: 768px) {
    .header-card { flex-direction: column; align-items: flex-start; }
    .filter-grid { grid-template-columns: 1fr; }
}
</style>

<div class="rejected-page">

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
    <div class="header-card">
        <div class="header-left">
            <h1>Rejected Requests</h1>
            <p>View all rejected item requests</p>
        </div>
        <span class="header-badge">
            <svg fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            {{ $requests->total() }} Rejected Request{{ $requests->total() !== 1 ? 's' : '' }}
        </span>
    </div>

    <!-- Filters -->
    <div class="filter-card">
        <form method="GET" action="{{ route('admin.orders.rejected') }}">
            <div class="filter-grid">
                <div class="filter-field">
                    <label for="search">Search</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                           placeholder="Search by purpose..." class="filter-input">
                </div>
                
                <div class="filter-field">
                    <label for="date_from">Date From</label>
                    <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}"
                           class="filter-input">
                </div>
                
                <div class="filter-field">
                    <label for="date_to">Date To</label>
                    <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}"
                           class="filter-input">
                </div>
            </div>
            
            <div class="filter-actions">
                <a href="{{ route('admin.orders.rejected') }}" class="btn btn-muted">
                    Clear Filters
                </a>
                <button type="submit" class="btn btn-primary">
                    Apply Filters
                </button>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="table-card">
        @if($requests->count() > 0)
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Request ID</th>
                            <th>Requester</th>
                            <th>Purpose</th>
                            <th>Items</th>
                            <th>Rejected By</th>
                            <th>Rejection Reason</th>
                            <th>Date Rejected</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($requests as $request)
                            <tr>
                                <td>
                                    <div class="req-id">#{{ str_pad($request->id, 6, '0', STR_PAD_LEFT) }}</div>
                                    <div class="req-date">{{ $request->created_at->format('M d, Y') }}</div>
                                </td>
                                
                                <td>
                                    <div class="user-cell">
                                        <div class="user-avatar">
                                            {{ Str::upper(substr($request->user->first_name, 0, 1) . substr($request->user->last_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="user-name">{{ $request->user->first_name }} {{ $request->user->last_name }}</div>
                                            <div class="user-unit">{{ $request->user->unit ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                
                                <td>
                                    <div>{{ Str::limit($request->purpose, 50) }}</div>
                                    @if($request->priority == 'urgent')
                                        <span class="urgent-badge">Urgent</span>
                                    @endif
                                </td>
                                
                                <td>
                                    <div class="item-count">{{ $request->requestItems->count() }} item(s)</div>
                                    <div class="item-qty">Qty: {{ $request->requestItems->sum('quantity') }}</div>
                                </td>
                                
                                <td>
                                    @if($request->rejectedBy)
                                        <div>{{ $request->rejectedBy->first_name }} {{ $request->rejectedBy->last_name }}</div>
                                        <div class="req-date">Admin</div>
                                    @else
                                        <span style="color:#9a9591;">System</span>
                                    @endif
                                </td>
                                
                                <td>
                                    <div class="reason-text">
                                        {{ Str::limit($request->rejection_reason, 60) }}
                                    </div>
                                    @if(strlen($request->rejection_reason) > 60)
                                        <a onclick="showReasonModal('{{ $request->rejection_reason }}')" class="reason-link">
                                            View Full
                                        </a>
                                    @endif
                                </td>
                                
                                <td>
                                    <div class="date-main">{{ $request->rejected_at->format('M d, Y') }}</div>
                                    <div class="date-time">{{ $request->rejected_at->format('h:i A') }}</div>
                                    <div class="date-relative">{{ $request->rejected_at->diffForHumans() }}</div>
                                </td>
                                
                                <td>
                                    <div class="actions">
                                        <a href="{{ route('admin.orders.review', $request->id) }}" class="action-icon action-view" title="View Details">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        
                                        <a onclick="exportRequest({{ $request->id }})" class="action-icon action-export" title="Export">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="pagination-wrap">
                {{ $requests->links() }}
            </div>
        @else
            <div class="empty-state">
                <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h3>No rejected requests</h3>
                <p>All requests are either pending or approved.</p>
                <a href="{{ route('admin.orders.pending') }}" class="btn btn-primary">
                    View Pending Requests
                </a>
            </div>
        @endif
    </div>

    <!-- Stats Summary -->
    @if($requests->count() > 0)
    <div class="stats-grid">
        <div class="stat-card red">
            <div class="stat-icon red">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="stat-text">
                <p>Total Rejected</p>
                <p>{{ $requests->total() }}</p>
            </div>
        </div>
        
        <div class="stat-card yellow">
            <div class="stat-icon yellow">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="stat-text">
                <p>This Month</p>
                <p>
                    {{ \App\Models\ItemRequest::where('status', 'rejected')
                        ->whereMonth('rejected_at', now()->month)
                        ->whereYear('rejected_at', now()->year)
                        ->count() }}
                </p>
            </div>
        </div>
        
        <div class="stat-card blue">
            <div class="stat-icon blue">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                </svg>
            </div>
            <div class="stat-text">
                <p>Top Rejector</p>
                <p style="font-size:1rem;">
                    @php
                        $topRejector = \App\Models\User::withCount(['rejectedRequests'])
                            ->where('type', 'admin')
                            ->orderBy('rejected_requests_count', 'desc')
                            ->first();
                    @endphp
                    {{ $topRejector ? $topRejector->first_name . ' ' . $topRejector->last_name : 'N/A' }}
                </p>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Reason Modal -->
<div id="reasonModal" class="modal">
    <div class="modal-content">
        <h3 class="modal-title">Rejection Reason</h3>
        <div class="modal-reason">
            <p id="reasonText"></p>
        </div>
        <button onclick="closeReasonModal()" class="modal-close">Close</button>
    </div>
</div>

<script>
function showReasonModal(reason) {
    document.getElementById('reasonText').textContent = reason;
    document.getElementById('reasonModal').classList.add('show');
}

function closeReasonModal() {
    document.getElementById('reasonModal').classList.remove('show');
}

function exportRequest(requestId) {
    window.location.href = `/admin/orders/export?type=rejected&request_id=${requestId}`;
}

document.getElementById('reasonModal').addEventListener('click', function(e) {
    if (e.target === this) closeReasonModal();
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeReasonModal();
});
</script>

@endsection