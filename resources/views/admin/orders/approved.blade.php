@extends('layouts.admin')

@section('title', 'Approved Requests')

@section('page-title', 'Approved Requests')

@section('content')

<style>
:root {
    --cream:    #FAF7F0;
    --sand:     #D8D2C2;
    --sienna:   #B17457;
    --charcoal: #4A4947;
}

.approved-page {
    background: var(--cream);
    padding: 2rem;
    font-family: 'Georgia', serif;
    min-height: 100vh;
}

/* ── HEADER ── */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 2rem;
    gap: 1rem;
}
.page-header-left h1 {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--charcoal);
    margin-bottom: .25rem;
}
.page-header-left p {
    font-size: .875rem;
    color: #6b6966;
}
.page-header-right {
    display: flex;
    align-items: center;
    gap: 1rem;
}
.count-badge {
    padding: .35rem .9rem;
    background: #eef6ee;
    color: #2e7d32;
    border-radius: 20px;
    font-size: .85rem;
    font-weight: 600;
}
.btn-back {
    padding: .5rem 1rem;
    background: #f5f1e8;
    color: var(--charcoal);
    border-radius: 7px;
    text-decoration: none;
    font-size: .85rem;
    font-weight: 600;
    transition: background .15s;
}
.btn-back:hover { background: #ebe6d9; }

/* ── SEARCH BAR ── */
.search-card {
    background: #fff;
    border: 1px solid var(--sand);
    border-radius: 10px;
    padding: 1rem;
    margin-bottom: 1.5rem;
}
.search-form { display: flex; gap: .75rem; align-items: center; flex-wrap: wrap; }
.search-input-wrap {
    flex: 1;
    min-width: 300px;
    position: relative;
}
.search-icon {
    position: absolute;
    left: .75rem;
    top: 50%;
    transform: translateY(-50%);
    width: 1.15rem;
    height: 1.15rem;
    color: #9a9591;
    pointer-events: none;
}
.search-input {
    width: 100%;
    padding: .6rem .9rem .6rem 2.5rem;
    font-size: .875rem;
    font-family: inherit;
    background: var(--cream);
    border: 1px solid var(--sand);
    border-radius: 7px;
    color: var(--charcoal);
    outline: none;
    transition: border-color .2s;
}
.search-input:focus { border-color: var(--sienna); }
.btn-search {
    padding: .6rem 1.25rem;
    background: var(--sienna);
    color: #fff;
    border: none;
    border-radius: 7px;
    font-size: .875rem;
    font-weight: 600;
    cursor: pointer;
    transition: opacity .15s;
}
.btn-search:hover { opacity: .88; }
.btn-clear {
    padding: .6rem 1.25rem;
    background: #f5f1e8;
    color: var(--charcoal);
    border: none;
    border-radius: 7px;
    font-size: .875rem;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    transition: background .15s;
}
.btn-clear:hover { background: #ebe6d9; }

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
.flash svg { width: 1.15rem; height: 1.15rem; flex-shrink: 0; }

/* ── TABLE CARD ── */
.table-card {
    background: #fff;
    border: 1px solid var(--sand);
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 2rem;
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
    padding: 1rem 1.2rem;
    color: var(--charcoal);
    vertical-align: middle;
}

/* ── REQUEST CELL ── */
.req-cell { display: flex; align-items: center; gap: .75rem; }
.req-icon {
    width: 2.5rem;
    height: 2.5rem;
    background: #eef6ee;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.req-icon svg { width: 1.4rem; height: 1.4rem; color: #4a8c4a; }
.req-info .req-id {
    font-size: .875rem;
    font-weight: 600;
    color: var(--charcoal);
    margin-bottom: .15rem;
}
.req-info .req-purpose {
    font-size: .8rem;
    color: #6b6966;
    margin-bottom: .2rem;
}
.req-info .req-date {
    font-size: .7rem;
    color: #9a9591;
}

/* ── USER CELL ── */
.user-name { font-size: .875rem; color: var(--charcoal); font-weight: 500; }
.user-unit { font-size: .8rem; color: #6b6966; }
.user-email { font-size: .75rem; color: #9a9591; }

/* ── ITEMS CELL ── */
.item-count {
    font-size: .875rem;
    color: var(--charcoal);
    font-weight: 500;
    margin-bottom: .3rem;
}
.item-tags { display: flex; flex-wrap: wrap; gap: .3rem; }
.item-tag {
    display: inline-block;
    padding: .2rem .5rem;
    background: #f5f1e8;
    border-radius: 4px;
    font-size: .7rem;
    color: var(--charcoal);
}
.item-more { font-size: .75rem; color: var(--sienna); }

/* ── STATUS BADGES ── */
.status-badges { display: flex; flex-direction: column; gap: .3rem; }
.badge {
    display: inline-block;
    padding: .25rem .65rem;
    font-size: .7rem;
    font-weight: 700;
    border-radius: 20px;
    letter-spacing: .04em;
    text-transform: uppercase;
    width: fit-content;
}
.badge-approved { background: #eef6ee; color: #2e7d32; }
.badge-pending { background: #fff4e6; color: #c77d11; }
.badge-partial { background: #d9ebf7; color: #2d5f8a; }
.badge-completed { background: #eef6ee; color: #2e7d32; }

/* ── ACTION BUTTONS ── */
.actions { display: flex; gap: .4rem; flex-wrap: wrap; }
.btn-action {
    display: inline-flex;
    align-items: center;
    gap: .3rem;
    padding: .5rem .9rem;
    font-size: .75rem;
    font-weight: 600;
    border-radius: 7px;
    text-decoration: none;
    transition: opacity .15s;
    border: none;
    cursor: pointer;
}
.btn-action:hover { opacity: .88; }
.btn-action svg { width: .95rem; height: .95rem; }
.btn-issue { background: #4a7fb5; color: #fff; }
.btn-view { background: #f5f1e8; color: var(--charcoal); }
.btn-issuance { background: #eef6ee; color: #2e7d32; }

/* ── PAGINATION ── */
.pagination-wrap {
    padding: 1rem 1.5rem;
    border-top: 1px solid var(--sand);
}

/* ── EMPTY STATE ── */
.empty-state {
    padding: 3rem 1rem;
    text-align: center;
}
.empty-state svg { color: var(--sand); margin: 0 auto 1rem; }
.empty-state h3 { font-size: 1.1rem; font-weight: 600; color: var(--charcoal); margin-bottom: .5rem; }
.empty-state p { font-size: .875rem; color: #9a9591; margin-bottom: 1.5rem; }

/* ── SUMMARY CARDS ── */
.summary-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-top: 2rem;
}
.summary-card {
    background: linear-gradient(135deg, var(--color-from), var(--color-to));
    border-radius: 10px;
    padding: 1.5rem;
    color: #fff;
    box-shadow: 0 2px 8px rgba(0,0,0,.1);
}
.summary-card.green { --color-from: #4a8c4a; --color-to: #6aab6a; }
.summary-card.blue { --color-from: #4a7fb5; --color-to: #6a99d4; }
.summary-card.purple { --color-from: #7b5fa0; --color-to: #9b7fc0; }
.summary-content { display: flex; justify-content: space-between; align-items: center; }
.summary-text p:first-child {
    font-size: .8rem;
    opacity: .9;
    margin-bottom: .5rem;
}
.summary-text p:last-child {
    font-size: 2rem;
    font-weight: 700;
}
.summary-icon {
    width: 3.5rem;
    height: 3.5rem;
    background: rgba(255,255,255,.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.summary-icon svg { width: 2rem; height: 2rem; }

@media (max-width: 768px) {
    .page-header { flex-direction: column; }
    .search-input-wrap { min-width: 100%; }
}
</style>

<div class="approved-page">

    <!-- Header -->
    <div class="page-header">
        <div class="page-header-left">
            <h1>Approved Requests</h1>
            <p>Manage all approved item requests</p>
        </div>
        <div class="page-header-right">
            <span class="count-badge">{{ $requests->total() }} Approved</span>
            <a href="{{ route('admin.orders.index') }}" class="btn-back">Back to Dashboard</a>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="search-card">
        <form action="{{ route('admin.orders.approved') }}" method="GET" class="search-form">
            <div class="search-input-wrap">
                <svg class="search-icon" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search by purpose, user name, or email..."
                       class="search-input">
            </div>
            <button type="submit" class="btn-search">Search</button>
            @if(request()->has('search'))
                <a href="{{ route('admin.orders.approved') }}" class="btn-clear">Clear Filters</a>
            @endif
        </form>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="flash flash-success">
            <svg fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="flash flash-error">
            <svg fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <!-- Table -->
    <div class="table-card">
        @if($requests->count() > 0)
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Request Details</th>
                            <th>User & Department</th>
                            <th>Items</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($requests as $request)
                            <tr>
                                <td>
                                    <div class="req-cell">
                                        <div class="req-icon">
                                            <svg fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <div class="req-info">
                                            <div class="req-id">Request #{{ $request->id }}</div>
                                            <div class="req-purpose">{{ Str::limit($request->purpose, 40) }}</div>
                                            <div class="req-date">Approved: {{ $request->approved_at->format('M d, Y H:i') }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="user-name">{{ $request->user->full_name ?? $request->user->name }}</div>
                                    <div class="user-unit">{{ $request->user->unit ?? 'N/A' }}</div>
                                    <div class="user-email">{{ $request->user->email }}</div>
                                </td>
                                <td>
                                    <div class="item-count">{{ $request->requestItems->count() }} item(s)</div>
                                    <div class="item-tags">
                                        @foreach($request->requestItems->take(2) as $item)
                                            <span class="item-tag">{{ $item->item->name }} (x{{ $item->quantity }})</span>
                                        @endforeach
                                        @if($request->requestItems->count() > 2)
                                            <span class="item-more">+{{ $request->requestItems->count() - 2 }} more</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="status-badges">
                                        <span class="badge badge-approved">Approved</span>
                                        @if($request->issuance)
                                            @php
                                                $statusMap = [
                                                    'pending' => 'badge-pending',
                                                    'partially_issued' => 'badge-partial',
                                                    'completed' => 'badge-completed'
                                                ];
                                                $badgeClass = $statusMap[$request->issuance->status] ?? 'badge-pending';
                                            @endphp
                                            <span class="badge {{ $badgeClass }}">
                                                {{ ucfirst(str_replace('_', ' ', $request->issuance->status)) }}
                                            </span>
                                        @else
                                            <span class="badge badge-pending">Pending Issuance</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="actions">
                                        @if(!$request->issuance || $request->issuance->status !== 'completed')
                                            <a href="{{ route('admin.orders.create-issuance', $request->id) }}" class="btn-action btn-issue">
                                                <svg fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6z"/>
                                                    <path d="M13 5a1 1 0 011-1h3a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1V5z"/>
                                                </svg>
                                                Issue
                                            </a>
                                        @endif
                                        <a href="{{ route('admin.orders.review', $request->id) }}" class="btn-action btn-view">
                                            <svg fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                            </svg>
                                            View
                                        </a>
                                        @if($request->issuance)
                                            <a href="{{ route('admin.orders.issuances.view', $request->issuance->id) }}" class="btn-action btn-issuance">
                                                <svg fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                                                </svg>
                                                Issuance
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($requests->hasPages())
                <div class="pagination-wrap">
                    {{ $requests->withQueryString()->links() }}
                </div>
            @endif
        @else
            <div class="empty-state">
                <svg width="96" height="96" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h3>No approved requests</h3>
                <p>
                    @if(request()->has('search'))
                        No approved requests match your search criteria.
                    @else
                        All pending requests are awaiting approval.
                    @endif
                </p>
                <a href="{{ route('admin.orders.pending') }}" class="btn-search" style="display:inline-flex; align-items:center; gap:.5rem;">
                    <svg width="18" height="18" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"/>
                    </svg>
                    View Pending Requests
                </a>
            </div>
        @endif
    </div>

    <!-- Summary Cards -->
    <div class="summary-grid">
        <div class="summary-card green">
            <div class="summary-content">
                <div class="summary-text">
                    <p>Total Approved</p>
                    <p>{{ $requests->total() }}</p>
                </div>
                <div class="summary-icon">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="summary-card blue">
            <div class="summary-content">
                <div class="summary-text">
                    <p>Pending Issuance</p>
                    <p>{{ $requests->where('issuance', null)->count() }}</p>
                </div>
                <div class="summary-icon">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6z"/>
                        <path d="M13 5a1 1 0 011-1h3a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1V5z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="summary-card purple">
            <div class="summary-content">
                <div class="summary-text">
                    <p>Items Issued</p>
                    <p>{{ $requests->whereNotNull('issuance')->count() }}</p>
                </div>
                <div class="summary-icon">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('input[name="search"]').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') this.form.submit();
    });
});
</script>

@endsection