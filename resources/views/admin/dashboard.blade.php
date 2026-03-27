@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('page-title', 'Admin Dashboard')

@section('content')

<style>
:root {
    --cream:    #FAF7F0;
    --sand:     #D8D2C2;
    --sienna:   #B17457;
    --charcoal: #4A4947;
}

/* ── PAGE ── */
.dashboard-page {
    background: var(--cream);
    padding: 2rem;
    font-family: 'Georgia', serif;
    min-height: 100vh;
}

/* ── STATS GRID ── */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

/* ── STAT CARD ── */
.stat-card {
    background: #fff;
    border: 1px solid var(--sand);
    border-radius: 10px;
    padding: 1.5rem;
    border-left: 4px solid;
    box-shadow: 0 2px 8px rgba(74,73,71,.08);
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.stat-card.pending  { border-left-color: #e6a23c; }
.stat-card.urgent   { border-left-color: #c0392b; }
.stat-card.approved { border-left-color: #4a8c4a; }
.stat-card.rejected { border-left-color: #c0392b; }

.stat-info p:first-child {
    font-size: .8rem;
    color: #6b6966;
    margin-bottom: .4rem;
}
.stat-info p:last-child {
    font-size: 2rem;
    font-weight: 700;
    color: var(--charcoal);
}

.stat-icon {
    width: 3.5rem;
    height: 3.5rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
}
.stat-icon.yellow { background: #fff4e6; }
.stat-icon.red    { background: #ffe6e6; }
.stat-icon.green  { background: #eef6ee; }

/* ── THREE COLUMN LAYOUT ── */
.three-col-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}
@media (max-width: 1024px) {
    .three-col-grid { grid-template-columns: 1fr; }
}
@media (min-width: 1025px) and (max-width: 1400px) {
    .three-col-grid { grid-template-columns: repeat(2, 1fr); }
}

/* ── SECTION CARD ── */
.section-card {
    background: #fff;
    border: 1px solid var(--sand);
    border-radius: 10px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(74,73,71,.08);
}
.section-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--charcoal);
    margin-bottom: 1rem;
}

/* ── INVENTORY STATUS ── */
.inv-list { display: flex; flex-direction: column; gap: 1rem; }
.inv-item {
    display: flex;
    align-items: center;
    gap: .75rem;
    padding: 1rem;
    border-radius: 8px;
    text-decoration: none;
    transition: background .12s;
}
.inv-item.low-stock {
    background: #fffbf0;
}
.inv-item.low-stock:hover {
    background: #fff4e0;
}
.inv-item.expiring {
    background: #fff0f0;
}
.inv-item.expiring:hover {
    background: #ffe6e6;
}
.inv-icon {
    width: 3rem;
    height: 3rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    font-weight: 700;
    color: #fff;
    flex-shrink: 0;
}
.inv-icon.yellow { background: #e6a23c; }
.inv-icon.red    { background: #c0392b; }
.inv-text { flex: 1; }
.inv-text p:first-child {
    font-size: .875rem;
    font-weight: 600;
    color: var(--charcoal);
    margin-bottom: .15rem;
}
.inv-text p:last-child {
    font-size: .75rem;
    color: #6b6966;
}
.inv-count {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--charcoal);
}

/* ── MOST REQUESTED (PIE CHART) ── */
.chart-legend {
    display: flex;
    flex-direction: column;
}
.empty-state {
    text-align: center;
    color: #9a9591;
    font-style: italic;
    padding: 1rem 0;
    font-size: .875rem;
}

/* ── REQUESTS TABLE ── */
.table-card {
    background: #fff;
    border: 1px solid var(--sand);
    border-radius: 10px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(74,73,71,.08);
}
.table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}
.view-all-link {
    font-size: .85rem;
    color: var(--sienna);
    text-decoration: none;
    font-weight: 600;
    transition: opacity .15s;
}
.view-all-link:hover { opacity: .8; }

.table-wrap { overflow-x: auto; }
table { width: 100%; border-collapse: collapse; font-size: .875rem; }
thead tr { border-bottom: 2px solid var(--sand); }
thead th {
    padding: .75rem 1rem;
    text-align: left;
    font-size: .75rem;
    font-weight: 700;
    color: #6b6966;
    letter-spacing: .04em;
}
tbody tr {
    border-bottom: 1px solid #f0ece4;
    transition: background .12s;
}
tbody tr:last-child { border-bottom: none; }
tbody tr:hover { background: #fdfbf7; }
tbody td {
    padding: .75rem 1rem;
    color: var(--charcoal);
}
.td-name { font-weight: 600; }
.td-muted { color: #6b6966; }
.td-empty {
    text-align: center;
    padding: 2rem 1rem;
    color: #9a9591;
    font-style: italic;
}

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
.badge-urgent  { background: #ffe6e6; color: #a02f23; }
.badge-approved{ background: #eef6ee; color: #2e7d32; }
.badge-rejected{ background: #ffe6e6; color: #a02f23; }
.badge-cancelled{ background: #f5f3f0; color: #6b6966; }
</style>

<div class="dashboard-page">

    <!-- Quick Stats -->
    <div class="stats-grid">
        <!-- Pending Requests -->
        <div class="stat-card pending">
            <div class="stat-info">
                <p>Pending Requests</p>
                <p>{{ $stats['pending_requests'] }}</p>
            </div>
            <div class="stat-icon yellow">⏳</div>
        </div>

        <!-- Urgent Requests -->
        <div class="stat-card urgent">
            <div class="stat-info">
                <p>Urgent Requests</p>
                <p>{{ $stats['urgent_requests'] }}</p>
            </div>
            <div class="stat-icon red">🚨</div>
        </div>

        <!-- Approved Requests -->
        <div class="stat-card approved">
            <div class="stat-info">
                <p>Approved Requests</p>
                <p>{{ $stats['approved_requests'] }}</p>
            </div>
            <div class="stat-icon green">👍</div>
        </div>

        <!-- Rejected Requests -->
        <div class="stat-card rejected">
            <div class="stat-info">
                <p>Rejected Requests</p>
                <p>{{ $stats['rejected_requests'] }}</p>
            </div>
            <div class="stat-icon red">👎</div>
        </div>
    </div>

    <!-- Three Column Layout -->
    <div class="three-col-grid">
       
        <!-- Inventory Status -->
        <div class="section-card">
            <h3 class="section-title">Inventory Status</h3>
           
            <div class="inv-list">
                <!-- Low Stock Items -->
                <a href="#" class="inv-item low-stock">
                    <div class="inv-icon yellow">⚠️</div>
                    <div class="inv-text">
                        <p>Low Stock Items</p>
                        <p>Items below threshold</p>
                    </div>
                    <span class="inv-count">{{ $stats['low_stock_items'] }}</span>
                </a>

                </a>
            </div>
        </div>

        <!-- Most Requested Items -->
        <div class="section-card">
            <h3 class="section-title">Most Requested Items</h3>
           
            @if(isset($mostRequestedItems) && $mostRequestedItems->count() > 0)
                <div style="display: flex; align-items: center; justify-content: center; padding: .5rem 0;">
                    <canvas id="requestedItemsChart" style="max-width: 200px; max-height: 200px;"></canvas>
                </div>
                <div class="chart-legend" style="margin-top: .75rem;">
                    @foreach($mostRequestedItems->take(3) as $index => $item)
                        <div style="display: flex; align-items: center; gap: .4rem; margin-bottom: .4rem;">
                            <div style="width: .75rem; height: .75rem; border-radius: 3px; background: {{ ['#B17457', '#8a5a40', '#d4a574'][$index % 3] }}; flex-shrink: 0;"></div>
                            <span style="font-size: .75rem; color: var(--charcoal); line-height: 1.3;">
                                <strong>{{ Str::limit($item['name'], 20) }}</strong><br>
                                <span style="font-size: .7rem; color: #6b6966;">{{ $item['count'] }} req. ({{ $item['quantity'] }} items)</span>
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="empty-state">No requested items yet</p>
            @endif
        </div>

        <!-- Most Orders by Department -->
        <div class="section-card">
            <h3 class="section-title">Most Orders by Department</h3>
           
            @if(isset($mostOrdersByDept) && $mostOrdersByDept->count() > 0)
                <div style="display: flex; align-items: center; justify-content: center; padding: .5rem 0;">
                    <canvas id="ordersByDeptChart" style="max-width: 200px; max-height: 200px;"></canvas>
                </div>
                <div class="chart-legend" style="margin-top: .75rem;">
                    @foreach($mostOrdersByDept->take(3) as $index => $dept)
                        <div style="display: flex; align-items: center; gap: .4rem; margin-bottom: .4rem;">
                            <div style="width: .75rem; height: .75rem; border-radius: 3px; background: {{ ['#4a8c4a', '#6aab6a', '#8cc68c'][$index % 3] }}; flex-shrink: 0;"></div>
                            <span style="font-size: .75rem; color: var(--charcoal); line-height: 1.3;">
                                <strong>{{ Str::limit($dept['unit'], 20) }}</strong><br>
                                <span style="font-size: .7rem; color: #6b6966;">{{ $dept['count'] }} orders</span>
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="empty-state">No orders yet</p>
            @endif
        </div>
    </div>

    <!-- Critical Requests Table -->
    <div class="table-card">
        <div class="table-header">
            <h3 class="section-title" style="margin:0;">Recent Critical Requests</h3>
            <a href="{{ route('admin.orders.pending') }}" class="view-all-link">
                View All →
            </a>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Requester</th>
                        <th>Unit</th>
                        <th>Purpose</th>
                        <th>Created</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentRequests as $request)
                        <tr>
                            <td class="td-name">{{ $request->user->first_name }} {{ $request->user->last_name }}</td>
                            <td class="td-muted">{{ $request->user->unit ?? '-' }}</td>
                            <td>{{ Str::limit($request->purpose, 40) }}</td>
                            <td class="td-muted">{{ $request->created_at->format('M d, Y') }}</td>
                            <td>
                                <span class="badge
                                    @if($request->status == 'pending') badge-pending
                                    @elseif($request->priority == 'urgent') badge-urgent
                                    @elseif($request->status == 'approved') badge-approved
                                    @elseif($request->status == 'rejected') badge-rejected
                                    @elseif($request->status == 'cancelled') badge-cancelled
                                    @else badge-cancelled @endif">
                                    @if($request->priority == 'urgent')
                                        Urgent
                                    @else
                                        {{ ucfirst($request->status) }}
                                    @endif
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="td-empty">
                                No critical requests at this time
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@if(isset($mostRequestedItems) && $mostRequestedItems->count() > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Most Requested Items Chart
    const requestedCtx = document.getElementById('requestedItemsChart');
    if (requestedCtx) {
        const requestedData = {
            labels: [
                @foreach($mostRequestedItems->take(3) as $item)
                    '{{ $item['name'] }}',
                @endforeach
            ],
            datasets: [{
                data: [
                    @foreach($mostRequestedItems->take(3) as $item)
                        {{ $item['quantity'] }},
                    @endforeach
                ],
                backgroundColor: ['#B17457', '#8a5a40', '#d4a574'],
                borderColor: '#FAF7F0',
                borderWidth: 2
            }]
        };

        new Chart(requestedCtx, {
            type: 'pie',
            data: requestedData,
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#4A4947',
                        titleColor: '#FAF7F0',
                        bodyColor: '#FAF7F0',
                        borderColor: '#D8D2C2',
                        borderWidth: 1,
                        padding: 10,
                        displayColors: true,
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                return label + ': ' + value + ' items (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    }

    // Most Orders by Department Chart
    @if(isset($mostOrdersByDept) && $mostOrdersByDept->count() > 0)
    const deptCtx = document.getElementById('ordersByDeptChart');
    if (deptCtx) {
        const deptData = {
            labels: [
                @foreach($mostOrdersByDept->take(3) as $dept)
                    '{{ $dept['unit'] }}',
                @endforeach
            ],
            datasets: [{
                data: [
                    @foreach($mostOrdersByDept->take(3) as $dept)
                        {{ $dept['count'] }},
                    @endforeach
                ],
                backgroundColor: ['#4a8c4a', '#6aab6a', '#8cc68c'],
                borderColor: '#FAF7F0',
                borderWidth: 2
            }]
        };

        new Chart(deptCtx, {
            type: 'pie',
            data: deptData,
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#4A4947',
                        titleColor: '#FAF7F0',
                        bodyColor: '#FAF7F0',
                        borderColor: '#D8D2C2',
                        borderWidth: 1,
                        padding: 10,
                        displayColors: true,
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                return label + ': ' + value + ' orders (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    }
    @endif
});
</script>
@endif

@endsection