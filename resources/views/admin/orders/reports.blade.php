@extends('layouts.admin')

@section('title', 'Order Reports')

@section('page-title', 'Order Reports & Analytics')

@section('content')

<style>
:root {
    --cream:    #FAF7F0;
    --sand:     #D8D2C2;
    --sienna:   #B17457;
    --charcoal: #4A4947;
}

.reports-page {
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

/* ── FILTER CARD ── */
.filter-card {
    background: #fff;
    border: 1px solid var(--sand);
    border-radius: 10px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}
.filter-card h3 {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--charcoal);
    margin-bottom: 1rem;
}
.filter-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
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
.filter-select {
    width: 100%;
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
.btn-export { background: #4a8c4a; color: #fff; }

/* ── STATS GRID ── */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}
.stat-card {
    background: #fff;
    border: 1px solid var(--sand);
    border-radius: 10px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.stat-info p:first-child {
    font-size: .8rem;
    color: #6b6966;
    margin-bottom: .4rem;
}
.stat-info p:nth-child(2) {
    font-size: 2rem;
    font-weight: 700;
    color: var(--charcoal);
}
.stat-info p:last-child {
    font-size: .8rem;
    color: #9a9591;
    margin-top: .2rem;
}
.stat-icon {
    width: 3rem;
    height: 3rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.stat-icon svg { width: 1.75rem; height: 1.75rem; }
.stat-icon.blue { background: #d9ebf7; color: #2d5f8a; }
.stat-icon.green { background: #eef6ee; color: #2e7d32; }
.stat-icon.red { background: #ffe6e6; color: #c0392b; }
.stat-icon.yellow { background: #fff4e6; color: #c77d11; }

/* ── TABLE CARD ── */
.table-card {
    background: #fff;
    border: 1px solid var(--sand);
    border-radius: 10px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}
.table-card h3 {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--charcoal);
    margin-bottom: 1rem;
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
}
.td-month { font-weight: 600; }
.td-green { color: #2e7d32; font-weight: 600; }
.td-red { color: #c0392b; font-weight: 600; }
.td-yellow { color: #c77d11; font-weight: 600; }
.td-empty {
    text-align: center;
    padding: 2rem;
    color: #9a9591;
}

/* ── TWO COLUMN ── */
.two-col-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}
@media (max-width: 1024px) {
    .two-col-grid { grid-template-columns: 1fr; }
}

/* ── SECTION CARD ── */
.section-card {
    background: #fff;
    border: 1px solid var(--sand);
    border-radius: 10px;
    padding: 1.5rem;
}
.section-card h3 {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--charcoal);
    margin-bottom: 1rem;
}
.item-list { display: flex; flex-direction: column; gap: 1rem; }
.item-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: .85rem;
    background: #f5f1e8;
    border-radius: 8px;
}
.item-row p:first-child {
    font-size: .875rem;
    font-weight: 600;
    color: var(--charcoal);
}
.item-row p:last-child {
    font-size: .8rem;
    color: #6b6966;
    margin-top: .2rem;
}
.item-badge {
    padding: .25rem .65rem;
    background: #d9ebf7;
    color: #2d5f8a;
    border-radius: 20px;
    font-size: .7rem;
    font-weight: 700;
}
.empty-state {
    text-align: center;
    padding: 2rem 1rem;
    color: #9a9591;
    font-style: italic;
    font-size: .875rem;
}

/* ── ISSUANCE STATS ── */
.stat-rows { display: flex; flex-direction: column; gap: 1.5rem; }
.stat-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.stat-row-info { flex: 1; }
.stat-row-info p:first-child {
    font-size: .8rem;
    color: #6b6966;
    margin-bottom: .3rem;
}
.stat-row-info p:last-child {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--charcoal);
}
.stat-row-icon {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.stat-row-icon svg { width: 1.4rem; height: 1.4rem; }
.stat-row-icon.green { background: #eef6ee; color: #2e7d32; }
.stat-row-icon.blue { background: #d9ebf7; color: #2d5f8a; }
.stat-row-icon.purple { background: #e6d9f2; color: #7b5fa0; }
.return-summary {
    margin-top: 1rem;
    padding: 1rem;
    background: #f5f1e8;
    border-radius: 8px;
}
.return-summary p:first-child {
    font-size: .8rem;
    color: #6b6966;
    margin-bottom: .3rem;
}
.return-summary p:nth-child(2) {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--charcoal);
}
.return-summary p:last-child {
    font-size: .75rem;
    color: #9a9591;
    margin-top: .3rem;
}

/* ── EXPORT CARD ── */
.export-card {
    background: #fff;
    border: 1px solid var(--sand);
    border-radius: 10px;
    padding: 1.5rem;
}
.export-card h3 {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--charcoal);
    margin-bottom: 1rem;
}
.export-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}
.export-option {
    background: var(--bg);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 1.25rem;
    text-align: center;
    text-decoration: none;
    transition: transform .15s, box-shadow .15s;
}
.export-option:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,.1);
}
.export-option svg {
    width: 2.5rem;
    height: 2.5rem;
    color: var(--icon);
    margin: 0 auto .5rem;
}
.export-option p:first-of-type {
    font-size: .9rem;
    font-weight: 600;
    color: var(--charcoal);
    margin-bottom: .2rem;
}
.export-option p:last-of-type {
    font-size: .75rem;
    color: #6b6966;
}
.export-blue { --bg: #d9ebf7; --border: #6ba3d4; --icon: #2d5f8a; }
.export-green { --bg: #eef6ee; --border: #6aab6a; --icon: #2e7d32; }
.export-purple { --bg: #e6d9f2; --border: #a87fc7; --icon: #7b5fa0; }

@media (max-width: 768px) {
    .filter-grid { grid-template-columns: 1fr; }
}
</style>

<div class="reports-page">

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

    <!-- Filter Card -->
    <div class="filter-card">
        <h3>Filter Reports</h3>
        
        <form method="GET" action="{{ route('admin.orders.reports') }}" class="filter-grid">
            <div class="filter-field">
                <label>Year</label>
                <select name="year" class="filter-select">
                    @for($i = date('Y'); $i >= 2020; $i--)
                        <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            
            <div class="filter-field">
                <label>Month</label>
                <select name="month" class="filter-select">
                    <option value="">All Months</option>
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}" 
                                {{ $month == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                            {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                        </option>
                    @endfor
                </select>
            </div>
            
            <div>
                <button type="submit" class="btn btn-primary">Generate Report</button>
            </div>
            
            <div style="text-align:right;">
                <a href="{{ route('admin.orders.export', ['type' => 'requests', 'year' => $year, 'month' => $month]) }}" 
                   class="btn btn-export">Export to CSV</a>
            </div>
        </form>
    </div>

    <!-- Summary Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-info">
                <p>Total Requests</p>
                <p>{{ $monthlyStats->sum('total_requests') }}</p>
            </div>
            <div class="stat-icon blue">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <p>Approved</p>
                <p>{{ $monthlyStats->sum('approved') }}</p>
                <p>{{ $monthlyStats->sum('total_requests') > 0 ? 
                   round(($monthlyStats->sum('approved') / $monthlyStats->sum('total_requests')) * 100, 1) : 0 }}%</p>
            </div>
            <div class="stat-icon green">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <p>Rejected</p>
                <p>{{ $monthlyStats->sum('rejected') }}</p>
                <p>{{ $monthlyStats->sum('total_requests') > 0 ? 
                   round(($monthlyStats->sum('rejected') / $monthlyStats->sum('total_requests')) * 100, 1) : 0 }}%</p>
            </div>
            <div class="stat-icon red">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <p>Overdue Items</p>
                <p>{{ $overdueItems }}</p>
            </div>
            <div class="stat-icon yellow">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Monthly Statistics Table -->
    <div class="table-card">
        <h3>Monthly Request Statistics - {{ $year }}</h3>
        
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Month</th>
                        <th>Total Requests</th>
                        <th>Approved</th>
                        <th>Rejected</th>
                        <th>Pending</th>
                        <th>Approval Rate</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($monthlyStats as $stat)
                    <tr>
                        <td class="td-month">{{ date('F Y', strtotime($stat->month . '-01')) }}</td>
                        <td>{{ $stat->total_requests }}</td>
                        <td class="td-green">{{ $stat->approved }}</td>
                        <td class="td-red">{{ $stat->rejected }}</td>
                        <td class="td-yellow">{{ $stat->pending }}</td>
                        <td>
                            @if($stat->total_requests > 0)
                                {{ round(($stat->approved / $stat->total_requests) * 100, 1) }}%
                            @else
                                0%
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="td-empty">
                            No data available for the selected period.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Two Column Layout -->
    <div class="two-col-grid">
        <!-- Top Requested Items -->
        <div class="section-card">
            <h3>Top Requested Items - {{ $year }}</h3>
            
            <div class="item-list">
                @forelse($topItems as $item)
                <div class="item-row">
                    <div>
                        <p>{{ $item->name }}</p>
                        <p>{{ $item->request_count }} requests</p>
                    </div>
                    <span class="item-badge">{{ $item->total_requested }} units</span>
                </div>
                @empty
                <p class="empty-state">No item data available</p>
                @endforelse
            </div>
        </div>

        <!-- Issuance Statistics -->
        <div class="section-card">
            <h3>Issuance Statistics - {{ $year }}</h3>
            
            <div class="stat-rows">
                <div class="stat-row">
                    <div class="stat-row-info">
                        <p>Total Items Issued</p>
                        <p>{{ $issuanceStats->total_issued ?? 0 }}</p>
                    </div>
                    <div class="stat-row-icon green">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"/>
                            <path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
                
                <div class="stat-row">
                    <div class="stat-row-info">
                        <p>Total Items Returned</p>
                        <p>{{ $issuanceStats->total_returned ?? 0 }}</p>
                    </div>
                    <div class="stat-row-icon blue">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
                
                <div class="stat-row">
                    <div class="stat-row-info">
                        <p>Total Issuances</p>
                        <p>{{ $issuanceStats->total_issuances ?? 0 }}</p>
                    </div>
                    <div class="stat-row-icon purple">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
                
                @if(($issuanceStats->total_issued ?? 0) > 0)
                <div class="return-summary">
                    <p>Return Rate</p>
                    <p>{{ round(($issuanceStats->total_returned / $issuanceStats->total_issued) * 100, 1) }}%</p>
                    <p>{{ $issuanceStats->total_returned }} of {{ $issuanceStats->total_issued }} items returned</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Export Options -->
    <div class="export-card">
        <h3>Export Data</h3>
        
        <div class="export-grid">
            <a href="{{ route('admin.orders.export', ['type' => 'requests', 'year' => $year, 'month' => $month]) }}" 
               class="export-option export-blue">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                </svg>
                <p>Export Requests</p>
                <p>All request data as CSV</p>
            </a>
            
            <a href="{{ route('admin.orders.export', ['type' => 'issuances', 'year' => $year, 'month' => $month]) }}" 
               class="export-option export-green">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"/>
                    <path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd"/>
                </svg>
                <p>Export Issuances</p>
                <p>All issuance records</p>
            </a>
            
            <a href="{{ route('admin.orders.export', ['type' => 'returns', 'year' => $year, 'month' => $month]) }}" 
               class="export-option export-purple">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd"/>
                </svg>
                <p>Export Returns</p>
                <p>Item return records</p>
            </a>
        </div>
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const monthlyData = @json($monthlyStats);
    if (monthlyData.length > 0) {
        console.log('Monthly data available for charting');
    }
});
</script>
@endpush

@endsection