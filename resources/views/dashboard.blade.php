@extends('layouts.user')


@section('title', 'Dashboard')
@section('content')
<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

:root {
    --cream:      #FCF8F3;
    --teal:       #AEDADD;
    --teal-light: rgba(174,218,221,0.15);
    --teal-dark:  #7bbfc3;
    --terra:      #DB996C;
    --terra-light:rgba(219,153,108,0.12);
    --slate:      #6E7DA2;
    --slate-light:rgba(110,125,162,0.10);
    --slate-deep: #4a5878;
    --surface:    #ffffff;
    --bg:         #f5f6fa;
    --border:     #eaecf2;
    --text-main:  #1e2535;
    --text-sub:   #6b7280;
    --text-muted: #9ca3af;
}

.dash-wrap * { box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
.dash-wrap { display: flex; flex-direction: column; gap: 1.5rem; }

/* ══ SECTION CARD ═══════════════════════════════════════════ */
.dash-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 1.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,.04), 0 4px 16px rgba(74,88,120,.06);
}

/* ══ CARD HEADER ════════════════════════════════════════════ */
.card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.25rem;
}
.card-header-left { display: flex; align-items: center; gap: .6rem; }
.card-header-icon {
    width: 34px; height: 34px;
    border-radius: 9px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.card-header-icon.teal  { background: var(--teal-light); color: var(--teal-dark); }
.card-header-icon.terra { background: var(--terra-light); color: var(--terra); }
.card-title {
    font-size: .95rem;
    font-weight: 700;
    color: var(--text-main);
    letter-spacing: -.01em;
}
.card-badge {
    font-size: .68rem;
    font-weight: 600;
    padding: 3px 9px;
    border-radius: 20px;
    background: var(--terra-light);
    color: var(--terra);
    border: 1px solid rgba(219,153,108,.2);
}

/* ══ STAT GRID ══════════════════════════════════════════════ */
.stat-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
}
@media (max-width: 768px) { .stat-grid { grid-template-columns: repeat(2, 1fr); } }

.stat-card {
    display: flex;
    flex-direction: column;
    gap: .75rem;
    padding: 1.1rem 1.1rem 1rem;
    border-radius: 11px;
    border: 1.5px solid var(--border);
    text-decoration: none;
    background: var(--cream);
    transition: transform .18s, box-shadow .18s, border-color .18s;
    position: relative;
    overflow: hidden;
}
.stat-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    border-radius: 11px 11px 0 0;
    opacity: 0;
    transition: opacity .18s;
}
.stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(74,88,120,.12); }
.stat-card:hover::before { opacity: 1; }

.stat-card.cancelled::before { background: #f59e0b; }
.stat-card.cancelled:hover   { border-color: rgba(245,158,11,.35); }
.stat-card.urgent::before    { background: var(--terra); }
.stat-card.urgent:hover      { border-color: rgba(219,153,108,.35); }
.stat-card.approved::before  { background: #5a9e6a; }
.stat-card.approved:hover    { border-color: rgba(90,158,106,.35); }
.stat-card.rejected::before  { background: var(--slate); }
.stat-card.rejected:hover    { border-color: rgba(110,125,162,.35); }

.stat-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.stat-label {
    font-size: .75rem;
    font-weight: 600;
    color: var(--text-sub);
    letter-spacing: .01em;
}
.stat-icon {
    width: 30px; height: 30px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.stat-icon.yellow { background: rgba(245,158,11,.12); color: #d97706; }
.stat-icon.red    { background: var(--terra-light);    color: var(--terra); }
.stat-icon.green  { background: rgba(90,158,106,.12);  color: #4a8c4a; }
.stat-icon.blue   { background: var(--slate-light);    color: var(--slate); }

.stat-bottom { display: flex; align-items: flex-end; justify-content: space-between; }
.stat-number {
    font-size: 2rem;
    font-weight: 700;
    line-height: 1;
    letter-spacing: -.03em;
}
.stat-number.yellow { color: #d97706; }
.stat-number.yellow.zero { color: var(--text-muted); }
.stat-number.red    { color: var(--terra); }
.stat-number.red.zero    { color: var(--text-muted); }
.stat-number.green  { color: #4a8c4a; }
.stat-number.green.zero  { color: var(--text-muted); }
.stat-number.blue   { color: var(--slate); }
.stat-number.blue.zero   { color: var(--text-muted); }

.stat-arrow {
    color: var(--text-muted);
    opacity: 0;
    transform: translateX(-4px);
    transition: opacity .18s, transform .18s;
}
.stat-card:hover .stat-arrow { opacity: 1; transform: translateX(0); }

/* ══ TABLE ══════════════════════════════════════════════════ */
.dash-table-wrap { overflow-x: auto; margin: 0 -1.5rem; padding: 0 1.5rem; }

.dash-table {
    width: 100%;
    border-collapse: collapse;
}
.dash-table thead tr {
    background: #f8f9fb;
    border-radius: 8px;
}
.dash-table thead th {
    padding: .7rem 1rem;
    text-align: left;
    font-size: .72rem;
    font-weight: 700;
    color: var(--text-sub);
    letter-spacing: .06em;
    text-transform: uppercase;
    white-space: nowrap;
    border-bottom: 1px solid var(--border);
}
.dash-table thead th:first-child { border-radius: 8px 0 0 8px; }
.dash-table thead th:last-child  { border-radius: 0 8px 8px 0; }

.dash-table tbody tr {
    border-bottom: 1px solid #f2f3f7;
    transition: background .12s;
}
.dash-table tbody tr:last-child { border-bottom: none; }
.dash-table tbody tr:hover { background: #fafbfd; }

.dash-table td {
    padding: .85rem 1rem;
    font-size: .82rem;
    color: var(--text-main);
    vertical-align: middle;
}
.dash-table td.sub { color: var(--text-sub); }

.purpose-text {
    font-weight: 500;
    color: var(--text-main);
    max-width: 280px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    display: block;
}

/* ── BADGES ── */
.badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: .72rem;
    font-weight: 600;
    white-space: nowrap;
}
.badge-dot {
    width: 6px; height: 6px;
    border-radius: 50%;
    flex-shrink: 0;
}
.badge.pending  { background: rgba(245,158,11,.1);  color: #b45309; }
.badge.pending  .badge-dot { background: #f59e0b; }
.badge.approved { background: rgba(90,158,106,.1);  color: #4a8c4a; }
.badge.approved .badge-dot { background: #5a9e6a; }
.badge.rejected { background: var(--slate-light);   color: var(--slate-deep); }
.badge.rejected .badge-dot { background: var(--slate); }
.badge.other    { background: #f3f4f6; color: var(--text-sub); }
.badge.other    .badge-dot { background: var(--text-muted); }

.badge.urgent   { background: var(--terra-light); color: #b45309; }
.badge.urgent   .badge-dot { background: var(--terra); }
.badge.normal   { background: #f3f4f6; color: var(--text-sub); }
.badge.normal   .badge-dot { background: var(--text-muted); }

/* ── EMPTY STATE ── */
.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 3rem 1rem;
    gap: .75rem;
}
.empty-icon {
    width: 52px; height: 52px;
    background: #f4f5f8;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #c4c8d4;
}
.empty-state p {
    font-size: .84rem;
    color: var(--text-muted);
    font-weight: 500;
}
</style>

<div class="dash-wrap">

    {{-- ── REQUEST SUMMARY ────────────────────────────────── --}}
    <div class="dash-card">
        <div class="card-header">
            <div class="card-header-left">
                <div class="card-header-icon teal">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <span class="card-title">Request Summary</span>
            </div>
        </div>

        <div class="stat-grid">
            {{-- Cancelled --}}
            <a href="{{ route('requests.my-requests') }}?status=cancelled" class="stat-card cancelled">
                <div class="stat-top">
                    <span class="stat-label">Cancelled</span>
                    <div class="stat-icon yellow">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                </div>
                <div class="stat-bottom">
                    <span class="stat-number yellow {{ $stats['cancelled'] == 0 ? 'zero' : '' }}">{{ $stats['cancelled'] }}</span>
                    <svg class="stat-arrow" width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </a>

            {{-- Urgent --}}
            <a href="{{ route('requests.my-requests') }}?status=urgent" class="stat-card urgent">
                <div class="stat-top">
                    <span class="stat-label">Urgent</span>
                    <div class="stat-icon red">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="stat-bottom">
                    <span class="stat-number red {{ $stats['urgent'] == 0 ? 'zero' : '' }}">{{ $stats['urgent'] }}</span>
                    <svg class="stat-arrow" width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </a>

            {{-- Approved --}}
            <a href="{{ route('requests.my-requests') }}?status=approved" class="stat-card approved">
                <div class="stat-top">
                    <span class="stat-label">Approved</span>
                    <div class="stat-icon green">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </div>
                <div class="stat-bottom">
                    <span class="stat-number green {{ $stats['approved'] == 0 ? 'zero' : '' }}">{{ $stats['approved'] }}</span>
                    <svg class="stat-arrow" width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </a>

            {{-- Rejected --}}
            <a href="{{ route('requests.my-requests') }}?status=rejected" class="stat-card rejected">
                <div class="stat-top">
                    <span class="stat-label">Rejected</span>
                    <div class="stat-icon blue">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                        </svg>
                    </div>
                </div>
                <div class="stat-bottom">
                    <span class="stat-number blue {{ $stats['rejected'] == 0 ? 'zero' : '' }}">{{ $stats['rejected'] }}</span>
                    <svg class="stat-arrow" width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </a>
        </div>
    </div>

    {{-- ── CRITICAL REQUESTS ───────────────────────────────── --}}
    <div class="dash-card">
        <div class="card-header">
            <div class="card-header-left">
                <div class="card-header-icon terra">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    </svg>
                </div>
                <span class="card-title">Critical Requests</span>
            </div>
            <span class="card-badge">Urgent &amp; Pending</span>
        </div>

        <div class="dash-table-wrap">
            <table class="dash-table">
                <thead>
                    <tr>
                        <th>Purpose</th>
                        <th>Date Created</th>
                        <th>Status</th>
                        <th>Priority</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($criticalRequests as $request)
                        <tr>
                            <td>
                                <span class="purpose-text">{{ Str::limit($request->purpose, 50) }}</span>
                            </td>
                            <td class="sub">{{ $request->created_at->format('M d, Y') }}</td>
                            <td>
                                @php
                                    $statusClass = match($request->status) {
                                        'pending'  => 'pending',
                                        'approved' => 'approved',
                                        'rejected' => 'rejected',
                                        default    => 'other',
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }}">
                                    <span class="badge-dot"></span>
                                    {{ ucfirst($request->status) }}
                                </span>
                            </td>
                            <td>
                                @if($request->priority == 'urgent')
                                    <span class="badge urgent">
                                        <span class="badge-dot"></span>
                                        Urgent
                                    </span>
                                @else
                                    <span class="badge normal">
                                        <span class="badge-dot"></span>
                                        Normal
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <p>No critical requests at this time</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection