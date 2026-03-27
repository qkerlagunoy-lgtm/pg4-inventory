@extends('layouts.admin')

@section('title', 'Review Request #' . $request->id)
@section('page-title', 'Review Request')

@section('breadcrumb')
<nav class="mb-4">
    <ol class="flex items-center space-x-2 text-sm">
        <li><a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-gray-700">Dashboard</a></li>
        <li><svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></li>
        <li><a href="{{ route('admin.orders.pending') }}" class="text-gray-500 hover:text-gray-700">Pending Requests</a></li>
        <li><svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></li>
        <li class="text-blue-600 font-medium">Review #{{ $request->id }}</li>
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

.review-page {
    font-family: 'Georgia', serif;
}

/* Card */
.card {
    background: #fff;
    border: 1px solid var(--sand);
    border-radius: 10px;
    margin-bottom: 1.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,.08);
}
.card-header {
    padding: 1.5rem;
    border-bottom: 1px solid #e8e2d6;
}
.card-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--charcoal);
}
.card-subtitle {
    font-size: .875rem;
    color: #9a9591;
    margin-top: .25rem;
}
.card-body {
    padding: 1.5rem;
}
.section-title {
    font-size: 1.05rem;
    font-weight: 700;
    color: var(--charcoal);
    margin-bottom: 1rem;
}

/* Badges */
.badge {
    display: inline-block;
    padding: .4rem .85rem;
    border-radius: 20px;
    font-size: .8rem;
    font-weight: 700;
}
.badge-high { background: rgba(192,57,43,0.12); color: #c0392b; }
.badge-medium { background: rgba(219,153,108,0.12); color: #b07040; }
.badge-low { background: #f5f1e8; color: #6b6966; }
.badge-pending { background: rgba(219,153,108,0.12); color: #b07040; }
.badge-available { background: rgba(74,140,74,0.12); color: #2e6b2e; }
.badge-insufficient { background: rgba(192,57,43,0.12); color: #c0392b; }

/* Info Grid */
.info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.25rem;
}
@media (max-width: 768px) {
    .info-grid { grid-template-columns: 1fr; }
}
.info-item label {
    display: block;
    font-size: .8rem;
    color: #9a9591;
    margin-bottom: .25rem;
}
.info-item p {
    font-size: .9rem;
    font-weight: 600;
    color: var(--charcoal);
}

/* Alert */
.alert {
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1rem;
    display: flex;
    align-items: flex-start;
    gap: .75rem;
}
.alert-danger {
    background: #fef2f2;
    border: 1px solid #fecaca;
}
.alert svg {
    width: 1.25rem;
    height: 1.25rem;
    flex-shrink: 0;
    color: #c0392b;
}
.alert-title {
    font-weight: 700;
    color: #991b1b;
    font-size: .9rem;
}
.alert-text {
    font-size: .85rem;
    color: #dc2626;
    margin-top: .25rem;
}

/* Table */
.table-wrap {
    overflow-x: auto;
    border-radius: 8px;
    border: 1px solid var(--sand);
}
table {
    width: 100%;
    border-collapse: collapse;
}
thead {
    background: var(--cream);
}
th {
    padding: .85rem 1rem;
    text-align: left;
    font-size: .7rem;
    font-weight: 700;
    color: var(--charcoal);
    text-transform: uppercase;
    letter-spacing: .05em;
    border-bottom: 1px solid var(--sand);
}
td {
    padding: 1rem;
    border-bottom: 1px solid #f0ece4;
    font-size: .875rem;
}
tbody tr:hover {
    background: #fdfcfa;
}
tbody tr:last-child td {
    border-bottom: none;
}
.row-issue {
    background: #fef2f2 !important;
}
tfoot {
    background: var(--cream);
    border-top: 1px solid var(--sand);
}
tfoot td {
    font-weight: 700;
    color: var(--charcoal);
    border: none;
}

/* Forms */
.form-group {
    margin-bottom: .85rem;
}
.form-label {
    display: block;
    font-size: .8rem;
    font-weight: 600;
    color: #6b6966;
    margin-bottom: .4rem;
}
.form-control {
    width: 100%;
    padding: .65rem .85rem;
    border: 1px solid var(--sand);
    border-radius: 7px;
    background: var(--cream);
    color: var(--charcoal);
    font-size: .875rem;
    font-family: 'Georgia', serif;
    outline: none;
    transition: border-color .15s;
}
.form-control:focus {
    border-color: var(--sienna);
}
textarea.form-control {
    resize: vertical;
}
.form-note {
    padding: .75rem;
    background: rgba(219,153,108,0.08);
    border: 1px solid rgba(219,153,108,0.2);
    border-radius: 7px;
    margin-bottom: .85rem;
}
.form-note p {
    font-size: .8rem;
    color: #b07040;
}

/* Actions */
.actions-wrapper {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 1.5rem;
    flex-wrap: wrap;
}
.actions-main {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}
.action-box {
    min-width: 16rem;
}
@media (max-width: 768px) {
    .actions-wrapper {
        flex-direction: column;
    }
    .action-box {
        width: 100%;
    }
}

/* Buttons */
.btn {
    padding: .55rem 1.25rem;
    font-size: .875rem;
    font-weight: 600;
    border-radius: 7px;
    cursor: pointer;
    transition: opacity .15s;
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    border: none;
    width: 100%;
    justify-content: center;
}
.btn:hover {
    opacity: .88;
}
.btn svg {
    width: 1rem;
    height: 1rem;
}
.btn-back {
    background: #f5f1e8;
    color: var(--charcoal);
    border: 1px solid var(--sand);
    width: auto;
}
.btn-danger {
    background: #c0392b;
    color: #fff;
}
.btn-success {
    background: #4a8c4a;
    color: #fff;
}

/* Item details */
.item-name {
    font-size: .875rem;
    font-weight: 600;
    color: var(--charcoal);
}
.item-code {
    font-size: .75rem;
    color: #9a9591;
}
.category-badge {
    padding: .25rem .6rem;
    border-radius: 12px;
    font-size: .75rem;
    background: #f5f1e8;
    color: #6b6966;
}
.stock-value {
    font-weight: 600;
}
.stock-available { color: #4a8c4a; }
.stock-low { color: #c0392b; }
.shortage-badge {
    margin-left: .5rem;
    padding: .2rem .5rem;
    border-radius: 12px;
    font-size: .7rem;
    background: rgba(192,57,43,0.12);
    color: #c0392b;
    font-weight: 700;
}
</style>

<div class="review-page">

    <!-- Request Details Card -->
    <div class="card">
        <div class="card-header">
            <div class="flex justify-between items-start flex-wrap gap-3">
                <div>
                    <h2 class="card-title">Request #{{ $request->id }}</h2>
                    <p class="card-subtitle">Submitted {{ $request->created_at->format('M d, Y h:i A') }}</p>
                </div>
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="badge badge-{{ $request->priority }}">
                        {{ ucfirst($request->priority) }} Priority
                    </span>
                    <span class="badge badge-pending">Pending Review</span>
                </div>
            </div>
        </div>

        <!-- Requester Information -->
        <div class="card-body" style="border-bottom:1px solid #e8e2d6;">
            <h3 class="section-title">Requester Information</h3>
            <div class="info-grid">
                <div class="info-item">
                    <label>Name</label>
                    <p>{{ $request->user->full_name }}</p>
                </div>
                <div class="info-item">
                    <label>Email</label>
                    <p>{{ $request->user->email }}</p>
                </div>
                <div class="info-item">
                    <label>Unit/Department</label>
                    <p>{{ $request->user->unit ?? 'Not specified' }}</p>
                </div>
                <div class="info-item">
                    <label>Phone</label>
                    <p>{{ $request->user->phone ?? 'Not provided' }}</p>
                </div>
            </div>
        </div>

        <!-- Request Details -->
        <div class="card-body" style="border-bottom:1px solid #e8e2d6;">
            <h3 class="section-title">Request Details</h3>
            <div style="display:flex;flex-direction:column;gap:.85rem;">
                <div class="info-item">
                    <label>Purpose</label>
                    <p>{{ $request->purpose }}</p>
                </div>
                <div class="info-item">
                    <label>Required Date</label>
                    <p>{{ $request->required_date ? $request->required_date->format('M d, Y') : 'Not specified' }}</p>
                </div>
                <div class="info-item">
                    <label>Special Instructions</label>
                    <p>{{ $request->notes ?? 'None' }}</p>
                </div>
            </div>
        </div>

        <!-- Requested Items Table -->
        <div class="card-body">
            <h3 class="section-title">Requested Items</h3>
            
            @if(count($availabilityIssues) > 0)
                <div class="alert alert-danger">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <p class="alert-title">Stock Availability Issues Detected</p>
                        <p class="alert-text">Some items have insufficient stock. You may need to issue partial quantities or reject the request.</p>
                    </div>
                </div>
            @endif

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Category</th>
                            <th>Requested Qty</th>
                            <th>Available Stock</th>
                            <th>Status</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($request->requestItems as $item)
                            @php
                                $available = $item->item->quantity - $item->item->minimum_quantity;
                                $shortage = max(0, $item->quantity - $available);
                                $hasIssue = $available < $item->quantity;
                            @endphp
                            <tr class="{{ $hasIssue ? 'row-issue' : '' }}">
                                <td>
                                    <div class="item-name">{{ $item->item->name }}</div>
                                    <div class="item-code">{{ $item->item->code }}</div>
                                </td>
                                <td>
                                    <span class="category-badge">{{ $item->item->category->name ?? 'Uncategorized' }}</span>
                                </td>
                                <td class="stock-value">{{ $item->quantity }} {{ $item->item->unit }}</td>
                                <td>
                                    <span class="stock-value {{ $available >= $item->quantity ? 'stock-available' : 'stock-low' }}">
                                        {{ $available }} {{ $item->item->unit }}
                                    </span>
                                    @if($hasIssue)
                                        <span class="shortage-badge">Shortage: {{ $shortage }}</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-{{ $hasIssue ? 'insufficient' : 'available' }}">
                                        {{ $hasIssue ? 'Insufficient Stock' : 'Available' }}
                                    </span>
                                </td>
                                <td style="color:#9a9591;">{{ $item->notes ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3">Total Items: {{ $request->requestItems->count() }}</td>
                            <td colspan="3" style="text-align:right;">Total Quantity: {{ $request->requestItems->sum('quantity') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="card">
        <div class="card-body">
            <div class="actions-wrapper">
                <button onclick="history.back()" class="btn btn-back">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                    </svg>
                    Back to Pending
                </button>

                <div class="actions-main">
                    <!-- Reject Form -->
                    <div class="action-box">
                        <form id="rejectForm" action="{{ route('admin.orders.reject', $request->id) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="rejection_reason" class="form-label">Rejection Reason</label>
                                <textarea id="rejection_reason" name="rejection_reason" rows="2" class="form-control"
                                          placeholder="Please provide a reason for rejection..." required></textarea>
                            </div>
                            <button type="submit" onclick="return confirm('Are you sure you want to reject this request?')" class="btn btn-danger">
                                <svg fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                                Reject Request
                            </button>
                        </form>
                    </div>

                    <!-- Approve Form -->
                    <div class="action-box">
                        <form id="approveForm" action="{{ route('admin.orders.approve', $request->id) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="scheduled_date" class="form-label">Scheduled Issue Date (Optional)</label>
                                <input type="date" id="scheduled_date" name="scheduled_date" min="{{ date('Y-m-d') }}" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="notes" class="form-label">Notes (Optional)</label>
                                <textarea id="notes" name="notes" rows="2" class="form-control" placeholder="Add any notes or instructions..."></textarea>
                            </div>
                            
                            @if(count($availabilityIssues) > 0)
                                <div class="form-note">
                                    <p><strong>Note:</strong> Some items have insufficient stock. You can still approve and issue partial quantities later.</p>
                                </div>
                            @endif
                            
                            <button type="submit" onclick="return confirm('Are you sure you want to approve this request?')" class="btn btn-success">
                                <svg fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Approve Request
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Issues Details -->
    @if(count($availabilityIssues) > 0)
        <div class="card">
            <div class="card-body">
                <h3 class="section-title">Stock Availability Details</h3>
                <div class="table-wrap">
                    <table>
                        <thead style="background:#fef2f2;">
                            <tr>
                                <th style="color:#991b1b;">Item</th>
                                <th style="color:#991b1b;">Requested</th>
                                <th style="color:#991b1b;">Available</th>
                                <th style="color:#991b1b;">Shortage</th>
                                <th style="color:#991b1b;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($availabilityIssues as $issue)
                                <tr>
                                    <td>
                                        <div class="item-name">{{ $issue['item']->name }}</div>
                                        <div class="item-code">{{ $issue['item']->code }}</div>
                                    </td>
                                    <td class="stock-value">{{ $issue['requested'] }} {{ $issue['item']->unit }}</td>
                                    <td class="stock-value">{{ $issue['available'] }} {{ $issue['item']->unit }}</td>
                                    <td>
                                        <span class="badge badge-insufficient">{{ $issue['shortage'] }} {{ $issue['item']->unit }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-insufficient">Insufficient Stock</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div style="margin-top:1rem;font-size:.875rem;color:#6b6966;">
                    <p><strong>Recommendation:</strong> 
                        @if(count($availabilityIssues) == $request->requestItems->count())
                            All items have stock issues. Consider rejecting the request or requesting restocking.
                        @else
                            Some items can be issued. You can approve the request and issue partial quantities later.
                        @endif
                    </p>
                </div>
            </div>
        </div>
    @endif

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const today = new Date().toISOString().split('T')[0];
    const scheduledDateInput = document.getElementById('scheduled_date');
    if (scheduledDateInput) {
        scheduledDateInput.min = today;
    }

    const rejectForm = document.getElementById('rejectForm');
    if (rejectForm) {
        rejectForm.addEventListener('submit', function(e) {
            const reason = document.getElementById('rejection_reason').value.trim();
            if (!reason) {
                e.preventDefault();
                alert('Please provide a reason for rejection.');
                return false;
            }
        });
    }
});
</script>

@endsection