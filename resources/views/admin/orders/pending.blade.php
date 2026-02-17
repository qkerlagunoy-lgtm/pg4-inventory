@extends('layouts.admin')

@section('title', 'Pending Requests')

@section('page-title', 'Pending Requests')

@section('breadcrumb')
    <nav class="mb-4" aria-label="breadcrumb">
        <ol class="flex items-center space-x-2 text-sm">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-gray-700">Dashboard</a>
            </li>
            <li>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </li>
            <li>
                <a href="{{ route('admin.orders.index') }}" class="text-gray-500 hover:text-gray-700">Order Management</a>
            </li>
            <li>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </li>
            <li class="text-blue-600 font-medium" aria-current="page">Pending Requests</li>
        </ol>
    </nav>
@endsection

@section('content')

<style>
:root {
    --cream:    #FAF7F0;
    --sand:     #D8D2C2;
    --sienna:   #B17457;
    --charcoal: #4A4947;
}

.pending-page {
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
.flash svg { width: 1.15rem; height: 1.15rem; flex-shrink: 0; }

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
    margin-bottom: 1.5rem;
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
.header-actions {
    display: flex;
    gap: .75rem;
    align-items: center;
    flex-wrap: wrap;
}
.search-wrap {
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
}
.search-input {
    padding: .5rem .9rem .5rem 2.5rem;
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
.btn {
    padding: .5rem 1rem;
    font-size: .875rem;
    font-weight: 600;
    border-radius: 7px;
    border: none;
    cursor: pointer;
    transition: opacity .15s;
    font-family: inherit;
}
.btn:hover { opacity: .88; }
.btn-primary { background: var(--sienna); color: #fff; }
.btn-muted { background: #f5f1e8; color: var(--charcoal); }
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

/* ── STATS GRID ── */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
    margin-top: 1.5rem;
}
.stat-mini {
    background: var(--bg);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 1rem;
}
.stat-mini p:first-child {
    font-size: .8rem;
    font-weight: 600;
    color: var(--text);
    margin-bottom: .4rem;
}
.stat-mini p:last-child {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--charcoal);
}
.stat-blue { --bg: #d9ebf7; --border: #6ba3d4; --text: #2d5f8a; }
.stat-yellow { --bg: #fff4e6; --border: #e6ccb3; --text: #c77d11; }
.stat-red { --bg: #ffe6e6; --border: #f4b8b8; --text: #a02f23; }
.stat-green { --bg: #eef6ee; --border: #b8ddb8; --text: #2e7d32; }

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

/* ── USER AVATAR ── */
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
.user-info .user-name {
    font-size: .875rem;
    font-weight: 600;
    color: var(--charcoal);
}
.user-info .user-unit {
    font-size: .75rem;
    color: #6b6966;
    margin-top: .1rem;
}

/* ── PRIORITY BADGES ── */
.badge {
    display: inline-block;
    padding: .25rem .65rem;
    font-size: .7rem;
    font-weight: 700;
    border-radius: 20px;
    letter-spacing: .04em;
    text-transform: uppercase;
}
.badge-low { background: #f5f3f0; color: #6b6966; }
.badge-medium { background: #fff4e6; color: #c77d11; }
.badge-high { background: #ffe6e6; color: #d87070; }
.badge-urgent { background: #ffe6e6; color: #a02f23; }

/* ── ACTION BUTTONS ── */
.actions { display: flex; gap: .4rem; flex-wrap: wrap; }
.btn-action {
    display: inline-flex;
    align-items: center;
    gap: .3rem;
    padding: .45rem .75rem;
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
.btn-review { background: #d9ebf7; color: #2d5f8a; }
.btn-approve { background: #eef6ee; color: #2e7d32; }
.btn-reject { background: #ffe6e6; color: #a02f23; position: relative; }

/* ── REJECT MODAL ── */
.reject-modal {
    display: none;
    position: absolute;
    right: 0;
    top: calc(100% + .5rem);
    width: 16rem;
    background: #fff;
    border: 1px solid var(--sand);
    border-radius: 8px;
    box-shadow: 0 8px 24px rgba(0,0,0,.15);
    z-index: 50;
    padding: 1rem;
}
.reject-modal.show { display: block; }
.reject-modal h4 {
    font-size: .85rem;
    font-weight: 600;
    color: var(--charcoal);
    margin-bottom: .5rem;
}
.reject-textarea {
    width: 100%;
    padding: .5rem;
    border: 1px solid var(--sand);
    border-radius: 6px;
    font-size: .8rem;
    font-family: inherit;
    outline: none;
    resize: vertical;
}
.reject-textarea:focus { border-color: #d87070; }
.reject-actions {
    display: flex;
    justify-content: flex-end;
    gap: .5rem;
    margin-top: .75rem;
}
.btn-cancel {
    padding: .4rem .75rem;
    font-size: .75rem;
    color: #6b6966;
    background: none;
    border: none;
    cursor: pointer;
}
.btn-confirm {
    padding: .4rem .75rem;
    font-size: .75rem;
    background: #c0392b;
    color: #fff;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}

/* ── EMPTY STATE ── */
.empty-state {
    padding: 3rem 1rem;
    text-align: center;
}
.empty-state svg { color: var(--sand); margin: 0 auto 1rem; }
.empty-state h3 { font-size: 1rem; font-weight: 600; color: var(--charcoal); margin-bottom: .25rem; }
.empty-state p { font-size: .875rem; color: #9a9591; }

/* ── BULK ACTIONS ── */
.bulk-card {
    background: #fff;
    border: 1px solid var(--sand);
    border-radius: 10px;
    padding: 1rem 1.5rem;
    margin-top: 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.bulk-left { font-size: .875rem; color: #6b6966; }
.bulk-right { display: flex; gap: .75rem; align-items: center; }
.bulk-link {
    font-size: .85rem;
    color: var(--sienna);
    cursor: pointer;
    font-weight: 600;
}
.bulk-link:hover { opacity: .8; }

@media (max-width: 768px) {
    .header-top { flex-direction: column; }
    .header-actions { width: 100%; }
    .search-input { min-width: 100%; }
}
</style>

<div class="pending-page">

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
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <!-- Header Card -->
    <div class="header-card">
        <div class="header-top">
            <div class="header-left">
                <h2>Pending Requests</h2>
                <p>Review and process new item requests</p>
            </div>
            
            <div class="header-actions">
                <!-- Search -->
                <form method="GET" action="{{ route('admin.orders.pending') }}" style="display:flex; gap:.5rem; align-items:center;">
                    <div class="search-wrap">
                        <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Search requests..." class="search-input">
                    </div>
                    <button type="submit" class="btn btn-primary">Search</button>
                    @if(request()->has('search') || request()->has('priority'))
                        <a href="{{ route('admin.orders.pending') }}" class="btn btn-muted">Clear</a>
                    @endif
                </form>

                <!-- Priority Filter -->
                <form method="GET" action="{{ route('admin.orders.pending') }}">
                    <select name="priority" onchange="this.form.submit()" class="filter-select">
                        <option value="">All Priorities</option>
                        <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low Priority</option>
                        <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium Priority</option>
                        <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High Priority</option>
                        <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                    </select>
                </form>
            </div>
        </div>

        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-mini stat-blue">
                <p>Total Pending</p>
                <p>{{ $requests->total() }}</p>
            </div>
            <div class="stat-mini stat-yellow">
                <p>High Priority</p>
                <p>{{ \App\Models\ItemRequest::where('status', 'pending')->where('priority', 'high')->count() }}</p>
            </div>
            <div class="stat-mini stat-red">
                <p>Urgent</p>
                <p>{{ \App\Models\ItemRequest::where('status', 'pending')->where('priority', 'urgent')->count() }}</p>
            </div>
            <div class="stat-mini stat-green">
                <p>Today's Requests</p>
                <p>{{ \App\Models\ItemRequest::where('status', 'pending')->whereDate('created_at', today())->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="table-card">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Request #</th>
                        <th>Requester</th>
                        <th>Purpose</th>
                        <th>Items</th>
                        <th>Priority</th>
                        <th>Requested</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $request)
                        <tr>
                            <td>
                                <div style="font-weight:600;">#{{ $request->id }}</div>
                                <div style="font-size:.7rem;color:#9a9591;">{{ $request->created_at->format('M d, Y h:i A') }}</div>
                            </td>
                            <td>
                                <div class="user-cell">
                                    <div class="user-avatar">
                                        {{ strtoupper(substr($request->user->first_name, 0, 1) . substr($request->user->last_name, 0, 1)) }}
                                    </div>
                                    <div class="user-info">
                                        <div class="user-name">{{ $request->user->first_name }} {{ $request->user->last_name }}</div>
                                        <div class="user-unit">{{ $request->user->unit ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>{{ Str::limit($request->purpose, 50) }}</div>
                                @if($request->remarks)
                                    <div style="font-size:.75rem;color:#6b6966;margin-top:.2rem;">{{ Str::limit($request->remarks, 30) }}</div>
                                @endif
                            </td>
                            <td>
                                <div>{{ $request->requestItems->count() }} items</div>
                                <div style="font-size:.75rem;color:#6b6966;">{{ $request->requestItems->sum('quantity') }} total qty</div>
                            </td>
                            <td>
                                @php
                                    $badgeClass = [
                                        'low' => 'badge-low',
                                        'medium' => 'badge-medium',
                                        'high' => 'badge-high',
                                        'urgent' => 'badge-urgent'
                                    ][$request->priority] ?? 'badge-low';
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ ucfirst($request->priority) }}</span>
                            </td>
                            <td style="font-size:.8rem;color:#6b6966;">
                                {{ $request->created_at->diffForHumans() }}
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('admin.orders.review', $request->id) }}" class="btn-action btn-review">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Review
                                    </a>
                                    
                                    <form action="{{ route('admin.orders.approve', $request->id) }}" method="POST" 
                                          style="display:inline;" onsubmit="return confirm('Approve this request?')">
                                        @csrf
                                        <button type="submit" class="btn-action btn-approve">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Approve
                                        </button>
                                    </form>

                                    <div style="position:relative;">
                                        <button type="button" class="btn-action btn-reject reject-btn" data-request-id="{{ $request->id }}">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            Reject
                                        </button>
                                        
                                        <div id="reject-modal-{{ $request->id }}" class="reject-modal">
                                            <form action="{{ route('admin.orders.reject', $request->id) }}" method="POST">
                                                @csrf
                                                <h4>Rejection Reason</h4>
                                                <textarea name="rejection_reason" rows="3" class="reject-textarea" 
                                                          placeholder="Enter reason..." required></textarea>
                                                <div class="reject-actions">
                                                    <button type="button" class="btn-cancel cancel-reject-btn">Cancel</button>
                                                    <button type="submit" class="btn-confirm">Confirm</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <h3>No pending requests</h3>
                                    <p>All requests have been processed.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($requests->hasPages())
            <div style="padding:1rem 1.5rem;border-top:1px solid var(--sand);">
                {{ $requests->links() }}
            </div>
        @endif
    </div>

    <!-- Bulk Actions -->
    @if($requests->count() > 0)
        <div class="bulk-card">
            <div class="bulk-left">{{ $requests->total() }} request(s) found</div>
            <div class="bulk-right">
                <span class="bulk-link" onclick="selectAllRequests()">Select All</span>
                <span class="bulk-link" onclick="deselectAllRequests()">Deselect All</span>
                <button type="button" class="btn btn-primary">Bulk Approve</button>
            </div>
        </div>
    @endif

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.reject-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            const requestId = this.getAttribute('data-request-id');
            const modal = document.getElementById(`reject-modal-${requestId}`);
            
            document.querySelectorAll('[id^="reject-modal-"]').forEach(m => {
                if (m.id !== `reject-modal-${requestId}`) m.classList.remove('show');
            });
            
            modal.classList.toggle('show');
        });
    });

    document.querySelectorAll('.cancel-reject-btn').forEach(button => {
        button.addEventListener('click', function() {
            this.closest('[id^="reject-modal-"]').classList.remove('show');
        });
    });

    document.addEventListener('click', function(e) {
        if (!e.target.closest('[id^="reject-modal-"]') && !e.target.closest('.reject-btn')) {
            document.querySelectorAll('[id^="reject-modal-"]').forEach(modal => {
                modal.classList.remove('show');
            });
        }
    });
});

function selectAllRequests() {
    document.querySelectorAll('input[name="selected_requests[]"]').forEach(cb => cb.checked = true);
}

function deselectAllRequests() {
    document.querySelectorAll('input[name="selected_requests[]"]').forEach(cb => cb.checked = false);
}
</script>

@endsection