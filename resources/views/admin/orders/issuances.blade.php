@extends('layouts.admin')

@section('title', 'Issuance Management')

@section('page-title', 'Issuance Management')

@section('content')

<style>
:root {
    --cream:    #FAF7F0;
    --sand:     #D8D2C2;
    --sienna:   #B17457;
    --charcoal: #4A4947;
}

.issuance-page {
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
}
.header-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 1rem;
}
.header-left h2 {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--charcoal);
}
.header-left p {
    font-size: .875rem;
    color: #6b6966;
    margin-top: .25rem;
}
.header-right {
    display: flex;
    gap: .75rem;
    flex-wrap: wrap;
}
.search-wrap {
    position: relative;
}
.search-icon {
    position: absolute;
    right: .75rem;
    top: 50%;
    transform: translateY(-50%);
    width: 1.15rem;
    height: 1.15rem;
    color: #9a9591;
}
.search-input {
    padding: .5rem .9rem;
    padding-right: 2.5rem;
    border: 1px solid var(--sand);
    border-radius: 7px;
    font-size: .875rem;
    background: var(--cream);
    color: var(--charcoal);
    outline: none;
    transition: border-color .2s;
    min-width: 250px;
}
.search-input:focus { border-color: var(--sienna); }
.filter-select {
    padding: .5rem .9rem;
    border: 1px solid var(--sand);
    border-radius: 7px;
    font-size: .875rem;
    background: var(--cream);
    color: var(--charcoal);
    outline: none;
    cursor: pointer;
    font-family: inherit;
}
.btn-export {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    padding: .5rem 1rem;
    background: #4a8c4a;
    color: #fff;
    border-radius: 7px;
    font-size: .875rem;
    font-weight: 600;
    text-decoration: none;
    transition: opacity .15s;
}
.btn-export:hover { opacity: .88; }
.btn-export svg { width: 1.15rem; height: 1.15rem; }

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
    padding: 1rem 1.2rem;
    color: var(--charcoal);
    vertical-align: middle;
}

/* ── TABLE CELLS ── */
.td-id { font-weight: 600; }
.td-code { font-size: .75rem; color: #6b6966; margin-top: .1rem; }
.td-link {
    color: var(--sienna);
    text-decoration: none;
    font-weight: 500;
}
.td-link:hover { text-decoration: underline; }
.td-name { font-weight: 500; }
.td-unit { font-size: .75rem; color: #6b6966; margin-top: .1rem; }
.td-qty { font-size: .75rem; color: #6b6966; margin-top: .2rem; }
.td-date { font-size: .75rem; color: #9a9591; margin-top: .2rem; }

/* ── STATUS BADGES ── */
.badge {
    display: inline-block;
    padding: .25rem .65rem;
    font-size: .7rem;
    font-weight: 700;
    border-radius: 20px;
    letter-spacing: .04em;
    text-transform: uppercase;
}
.badge-pending { background: #fff4e6; color: #c77d11; }
.badge-partial { background: #ffe6e6; color: #d87070; }
.badge-completed { background: #eef6ee; color: #2e7d32; }

/* ── ACTION LINKS ── */
.actions { display: flex; gap: .75rem; flex-wrap: wrap; }
.action-link {
    display: inline-flex;
    align-items: center;
    gap: .3rem;
    font-size: .8rem;
    font-weight: 600;
    text-decoration: none;
    color: inherit;
    transition: opacity .15s;
}
.action-link:hover { opacity: .8; }
.action-link svg { width: .95rem; height: .95rem; }
.action-view { color: #4a7fb5; }
.action-add { color: #4a8c4a; }
.action-return { color: #7b5fa0; }

/* ── EMPTY STATE ── */
.empty-state {
    padding: 3rem 1rem;
    text-align: center;
}
.empty-state svg { color: var(--sand); margin: 0 auto 1rem; }
.empty-state h3 { font-size: 1.1rem; font-weight: 600; color: var(--charcoal); margin-bottom: .25rem; }
.empty-state p { font-size: .875rem; color: #9a9591; margin-bottom: 1.5rem; }
.btn-primary {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    padding: .6rem 1.25rem;
    background: var(--sienna);
    color: #fff;
    border-radius: 7px;
    font-size: .875rem;
    font-weight: 600;
    text-decoration: none;
    transition: opacity .15s;
}
.btn-primary:hover { opacity: .88; }
.btn-primary svg { width: 1rem; height: 1rem; }

/* ── SUMMARY CARDS ── */
.summary-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-top: 1.5rem;
}
.summary-card {
    background: #fff;
    border: 1px solid var(--sand);
    border-radius: 10px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}
.summary-icon {
    width: 3rem;
    height: 3rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.summary-icon svg { width: 1.75rem; height: 1.75rem; }
.summary-icon.blue { background: #d9ebf7; color: #2d5f8a; }
.summary-icon.green { background: #eef6ee; color: #2e7d32; }
.summary-icon.orange { background: #ffe6e6; color: #d87070; }
.summary-text p:first-child {
    font-size: .85rem;
    color: #6b6966;
    margin-bottom: .3rem;
}
.summary-text p:last-child {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--charcoal);
}

/* ── PAGINATION ── */
.pagination-wrap {
    padding: 1rem 1.5rem;
    border-top: 1px solid var(--sand);
}

@media (max-width: 768px) {
    .header-top { flex-direction: column; }
    .header-right { width: 100%; }
    .search-input { min-width: 100%; }
}
</style>

<div class="issuance-page">

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

    <!-- Header Card -->
    <div class="header-card">
        <div class="header-top">
            <div class="header-left">
                <h2>Item Issuances</h2>
                <p>Track and manage all item issuances</p>
            </div>
            
            <div class="header-right">
                <!-- Search -->
                <div class="search-wrap">
                    <input type="text" id="searchInput" placeholder="Search by ID or user..." 
                           class="search-input" value="{{ request('search') }}">
                    <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>

                <!-- Status Filter -->
                <select id="statusFilter" class="filter-select">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="partially_issued" {{ request('status') == 'partially_issued' ? 'selected' : '' }}>Partially Issued</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>

                <!-- Export -->
                <a href="{{ route('admin.orders.export', ['type' => 'issuances']) }}" class="btn-export">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                    Export
                </a>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="table-card">
        @if($issuances->count() > 0)
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Issuance ID</th>
                            <th>Request ID</th>
                            <th>User</th>
                            <th>Items Count</th>
                            <th>Status</th>
                            <th>Issued Date</th>
                            <th>Issued By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($issuances as $issuance)
                            <tr>
                                <td>
                                    <div class="td-id">#{{ $issuance->id }}</div>
                                    <div class="td-code">{{ $issuance->issuance_code ?? 'N/A' }}</div>
                                </td>
                                <td>
                                    <a href="{{ route('admin.orders.review', $issuance->itemRequest->id) }}" class="td-link">
                                        #{{ $issuance->itemRequest->id }}
                                    </a>
                                </td>
                                <td>
                                <div style="display:flex; align-items:center; gap:.75rem;">
                                    <div style="width:2.5rem;height:2.5rem;border-radius:50%;flex-shrink:0;overflow:hidden;display:flex;align-items:center;justify-content:center;font-size:.8rem;font-weight:700;color:#fff;{{ $issuance->itemRequest->user->avatar ? '' : 'background:linear-gradient(135deg,#B17457,#8a5a40);' }}">
                                        @if($issuance->itemRequest->user->avatar)
                                            <img src="{{ asset('storage/' . $issuance->itemRequest->user->avatar) }}"
                                                alt="{{ $issuance->itemRequest->user->first_name }}"
                                                style="width:100%;height:100%;object-fit:cover;">
                                        @else
                                            {{ strtoupper(substr($issuance->itemRequest->user->first_name, 0, 1) . substr($issuance->itemRequest->user->last_name, 0, 1)) }}
                                        @endif
                                    </div>
                                    <div>
                                        <div class="td-name">{{ $issuance->itemRequest->user->first_name }} {{ $issuance->itemRequest->user->last_name }}</div>
                                        <div class="td-unit">{{ $issuance->itemRequest->user->unit ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                                  <td>
                                    <div>{{ $issuance->issuanceItems->count() }} items</div>
                                    <div class="td-qty">{{ $issuance->issuanceItems->sum('quantity_issued') }} total qty</div>
                                </td>
                                <td>
                                    @php
                                        $badgeClass = [
                                            'pending' => 'badge-pending',
                                            'partially_issued' => 'badge-partial',
                                            'completed' => 'badge-completed'
                                        ][$issuance->status] ?? 'badge-pending';
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">
                                        {{ str_replace('_', ' ', ucfirst($issuance->status)) }}
                                    </span>
                                </td>
                                <td>
                                    <div style="font-size:.875rem;">{{ $issuance->issued_at ? $issuance->issued_at->format('M d, Y') : 'N/A' }}</div>
                                    <div class="td-date">{{ $issuance->issued_at ? $issuance->issued_at->format('h:i A') : '' }}</div>
                                </td>
                                <td style="font-size:.85rem;color:#6b6966;">
                                    {{ $issuance->issuer->full_name ?? $issuance->issuer->name ?? 'System' }}
                                </td>
                                <td>
                                    <div class="actions">
                                        <a href="{{ route('admin.orders.issuances.view', $issuance->id) }}" class="action-link action-view">
                                            <svg fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                            </svg>
                                            View
                                        </a>
                                        
                                        @if($issuance->status == 'pending' || $issuance->status == 'partially_issued')
                                            <a href="{{ route('admin.orders.create-issuance', $issuance->itemRequest->id) }}" class="action-link action-add">
                                                <svg fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 3a1 1 0 00-1 1v5H4a1 1 0 100 2h5v5a1 1 0 102 0v-5h5a1 1 0 100-2h-5V4a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                </svg>
                                                Add Items
                                            </a>
                                        @endif

                                        @if($issuance->issuanceItems->where('status', 'issued')->count() > 0)
                                            <a href="{{ route('admin.orders.returns', ['search' => 'issuance:' . $issuance->id]) }}" class="action-link action-return">
                                                <svg fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                                                </svg>
                                                Returns
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($issuances->hasPages())
                <div class="pagination-wrap">
                    {{ $issuances->withQueryString()->links() }}
                </div>
            @endif
        @else
            <div class="empty-state">
                <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3>No issuances found</h3>
                <p>
                    @if(request('search') || request('status'))
                        Try adjusting your search or filter to find what you're looking for.
                    @else
                        No item issuances have been created yet.
                    @endif
                </p>
                <a href="{{ route('admin.orders.approved') }}" class="btn-primary">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 00-1 1v5H4a1 1 0 100 2h5v5a1 1 0 102 0v-5h5a1 1 0 100-2h-5V4a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    Create New Issuance
                </a>
            </div>
        @endif
    </div>

    <!-- Summary Cards -->
    <div class="summary-grid">
        <div class="summary-card">
            <div class="summary-icon blue">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="summary-text">
                <p>Pending Issuances</p>
                <p>{{ $issuances->where('status', 'pending')->count() }}</p>
            </div>
        </div>
        
        <div class="summary-card">
            <div class="summary-icon green">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="summary-text">
                <p>Completed Issuances</p>
                <p>{{ $issuances->where('status', 'completed')->count() }}</p>
            </div>
        </div>
        
        <div class="summary-card">
            <div class="summary-icon orange">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="summary-text">
                <p>Total Items Issued</p>
                <p>{{ $totalItemsIssued ?? 0 }}</p>
            </div>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    
    function applyFilters() {
        const search = searchInput.value;
        const status = statusFilter.value;
        
        let url = new URL(window.location.href);
        let params = new URLSearchParams(url.search);
        
        if (search) {
            params.set('search', search);
        } else {
            params.delete('search');
        }
        
        if (status) {
            params.set('status', status);
        } else {
            params.delete('status');
        }
        
        params.delete('page');
        window.location.href = url.pathname + '?' + params.toString();
    }
    
    let searchTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(applyFilters, 500);
    });
    
    statusFilter.addEventListener('change', applyFilters);
    
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            applyFilters();
        }
    });
});
</script>

@endsection