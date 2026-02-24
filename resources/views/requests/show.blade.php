@extends('layouts.user')

@section('title', 'Request #' . $request->id)

@section('page-title', 'Request Details')

@section('breadcrumb')
    <nav class="mb-4">
        <ol class="flex items-center space-x-2 text-sm">
            <li><a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700">Dashboard</a></li>
            <li><svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </li>
            <li><a href="{{ route('requests.my-requests') }}" class="text-gray-500 hover:text-gray-700">My Requests</a></li>
            <li><svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </li>
            <li style="color:#6E7DA2;" class="font-medium">Request #{{ $request->id }}</li>
        </ol>
    </nav>
@endsection

@section('header-actions')
    <div class="flex items-center gap-2">
        <a href="{{ route('requests.my-requests') }}"
            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition flex items-center gap-2 text-sm font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to My Requests
        </a>

        @if(in_array($request->status, ['pending', 'approved']))
            <form method="POST" action="{{ route('requests.cancel', $request->id) }}"
                onsubmit="return confirm('Are you sure you want to cancel this request?')">
                @csrf
                <button type="submit"
                    class="px-4 py-2 bg-red-50 text-red-600 border border-red-200 rounded-lg hover:bg-red-100 transition flex items-center gap-2 text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Cancel Request
                </button>
            </form>
        @endif
    </div>
@endsection

@section('content')
<style>
    /* ── PALETTE ──────────────────────────────────────────
       #FCF8F3  cream       → banner bg, card headers
       #AEDADD  teal        → info icon, issued badge, completed
       #DB996C  terracotta  → pending, CTA buttons
       #6E7DA2  slate       → approved, breadcrumb, links
    ───────────────────────────────────────────────────── */

    /* banner icon → teal */
    .banner-icon { background:rgba(174,218,221,0.18); border-color:rgba(174,218,221,0.35); }
    .banner-icon svg { color:#7bbfc3; }

    /* banner + card surfaces */
    .banner-bg    { background:#FCF8F3; }
    .banner-divider { border-color:#eaecf2; }
    .card-hdr-bg  { background:#FCF8F3; border-color:#eaecf2; }
    .card-hdr-icon { color:#AEDADD; }
    .items-pill   { background:#f0f1f5; color:#6b7280; }

    /* STATUS BADGES */
    .badge-pending   { background:rgba(219,153,108,0.15); color:#b07040; border:1px solid rgba(219,153,108,0.25); }
    .badge-approved  { background:rgba(110,125,162,0.15); color:#4a5878; border:1px solid rgba(110,125,162,0.25); }
    .badge-rejected  { background:rgba(192,57,43,0.10);   color:#c0392b; border:1px solid rgba(192,57,43,0.18); }
    .badge-cancelled { background:#f0f1f5;                color:#6b7280; border:1px solid #e2e4ec; }
    .badge-completed { background:rgba(174,218,221,0.2);  color:#5a8fa0; border:1px solid rgba(174,218,221,0.35); }

    /* PRIORITY BADGES */
    .badge-low    { background:#f0f1f5;                    color:#6b7280; border:1px solid #e2e4ec; }
    .badge-medium { background:rgba(174,218,221,0.2);     color:#5a8fa0; border:1px solid rgba(174,218,221,0.3); }
    .badge-high   { background:rgba(219,153,108,0.15);    color:#b07040; border:1px solid rgba(219,153,108,0.25); }
    .badge-urgent { background:rgba(192,57,43,0.1);       color:#c0392b; border:1px solid rgba(192,57,43,0.18); }

    /* STAT NUMBERS */
    .stat-approved { color:#6E7DA2; }
    .stat-pending  { color:#DB996C; }

    /* ITEM ROW BADGES */
    .ibadge-pending   { background:rgba(219,153,108,0.12); color:#b07040; border:1px solid rgba(219,153,108,0.2); }
    .ibadge-approved  { background:rgba(110,125,162,0.12); color:#4a5878; border:1px solid rgba(110,125,162,0.2); }
    .ibadge-rejected  { background:rgba(192,57,43,0.08);   color:#c0392b; border:1px solid rgba(192,57,43,0.15); }
    .ibadge-cancelled { background:#f0f1f5;                color:#6b7280; border:1px solid #e2e4ec; }
    .ibadge-issued    { background:rgba(174,218,221,0.18); color:#5a8fa0; border:1px solid rgba(174,218,221,0.3); }

    /* BREAKDOWN DOTS + PILLS */
    .dot-pending  { background:#DB996C; }
    .dot-approved { background:#6E7DA2; }
    .dot-rejected { background:#c0392b; }
    .pill-pending  { background:rgba(219,153,108,0.12); border-color:rgba(219,153,108,0.2); color:#1e2535; }
    .pill-approved { background:rgba(110,125,162,0.10); border-color:rgba(110,125,162,0.2); color:#1e2535; }
    .pill-rejected { background:rgba(192,57,43,0.08);   border-color:rgba(192,57,43,0.15); color:#1e2535; }

    /* PROGRESS BAR */
    .prog-approved { background:#6E7DA2; }
    .prog-rejected { background:#c0392b; }
    .prog-pending  { background:#DB996C; }

    /* CANCELLED ROW */
    .cancelled-row   { background:rgba(219,153,108,0.06); }
    .cancelled-label { color:#b07040; }
    .cancelled-val   { color:#b07040; font-weight:600; }

    /* FLASH SUCCESS → teal */
    .flash-success {
        background:rgba(174,218,221,0.12);
        border-color:rgba(174,218,221,0.35);
        color:#4a5878;
    }
    .flash-success svg { color:#7bbfc3; }

    /* REORDER CTA → terracotta */
    .btn-reorder { background:#DB996C; color:#fff; }
    .btn-reorder:hover { background:#c8844f; }

    /* TABLE THEAD */
    .thead-cream { background:#FCF8F3; }
</style>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-6 p-4 flash-success border rounded-lg flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
    @endif

    @php
        $statusBadgeClass = [
            'pending'   => 'badge-pending',
            'approved'  => 'badge-approved',
            'rejected'  => 'badge-rejected',
            'cancelled' => 'badge-cancelled',
            'completed' => 'badge-completed',
        ][$request->status] ?? 'badge-cancelled';

        $priorityBadgeClass = [
            'low'    => 'badge-low',
            'medium' => 'badge-medium',
            'high'   => 'badge-high',
            'urgent' => 'badge-urgent',
        ][$request->priority] ?? 'badge-low';

        $statusIcons = [
            'pending'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>',
            'approved'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
            'rejected'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
            'cancelled' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>',
            'completed' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>',
        ];
        $statusIcon = $statusIcons[$request->status] ?? $statusIcons['pending'];

        $approvedItems = $request->requestItems->where('status', 'approved')->count();
        $rejectedItems = $request->requestItems->where('status', 'rejected')->count();
        $pendingItems  = $request->requestItems->where('status', 'pending')->count();
        $totalItems    = $request->requestItems->count();
        $totalQty      = $request->requestItems->sum('quantity');
    @endphp

    {{-- Top Summary Banner --}}
    <div class="banner-bg rounded-xl border banner-divider shadow-sm mb-6 overflow-hidden">
        <div class="px-6 py-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full banner-icon border flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider font-medium">Request</p>
                    <h2 class="text-xl font-bold text-gray-900">#{{ $request->id }}</h2>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-full {{ $statusBadgeClass }}">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $statusIcon !!}</svg>
                    {{ ucfirst($request->status) }}
                </span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-full {{ $priorityBadgeClass }}">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
                    </svg>
                    {{ ucfirst($request->priority) }} Priority
                </span>
                <span class="text-xs text-gray-400">
                    {{ $request->request_date ? $request->request_date->format('M d, Y') : $request->created_at->format('M d, Y') }}
                </span>
            </div>
        </div>

        {{-- Stats Row --}}
        <div class="border-t banner-divider grid grid-cols-2 sm:grid-cols-4 divide-x" style="border-color:#eaecf2;">
            <div class="px-6 py-4 text-center">
                <p class="text-2xl font-bold text-gray-900">{{ $totalItems }}</p>
                <p class="text-xs text-gray-400 mt-0.5 uppercase tracking-wide">Total Items</p>
            </div>
            <div class="px-6 py-4 text-center">
                <p class="text-2xl font-bold text-gray-900">{{ $totalQty }}</p>
                <p class="text-xs text-gray-400 mt-0.5 uppercase tracking-wide">Total Qty</p>
            </div>
            <div class="px-6 py-4 text-center">
                <p class="text-2xl font-bold {{ $approvedItems > 0 ? 'stat-approved' : 'text-gray-300' }}" style="{{ $approvedItems > 0 ? 'color:#6E7DA2' : '' }}">{{ $approvedItems }}</p>
                <p class="text-xs text-gray-400 mt-0.5 uppercase tracking-wide">Approved</p>
            </div>
            <div class="px-6 py-4 text-center">
                <p class="text-2xl font-bold" style="{{ $pendingItems > 0 ? 'color:#DB996C' : 'color:#d1d5db' }}">{{ $pendingItems }}</p>
                <p class="text-xs text-gray-400 mt-0.5 uppercase tracking-wide">Pending</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left Column --}}
        <div class="lg:col-span-1 space-y-5">

            {{-- Request Details Card --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="card-hdr-bg px-5 py-4 border-b flex items-center gap-2">
                    <svg class="w-4 h-4 card-hdr-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Request Info</h3>
                </div>
                <div class="divide-y divide-gray-50">
                    <div class="px-5 py-3.5 flex justify-between items-start gap-3">
                        <span class="text-xs text-gray-400 uppercase tracking-wide mt-0.5 flex-shrink-0">Submitted</span>
                        <span class="text-sm text-gray-800 font-medium text-right">
                            {{ $request->request_date ? $request->request_date->format('M d, Y g:i A') : $request->created_at->format('M d, Y g:i A') }}
                        </span>
                    </div>

                    @if($request->required_date)
                        <div class="px-5 py-3.5 flex justify-between items-start gap-3">
                            <span class="text-xs text-gray-400 uppercase tracking-wide mt-0.5 flex-shrink-0">Required By</span>
                            <span class="text-sm text-gray-800 font-medium text-right">
                                {{ \Carbon\Carbon::parse($request->required_date)->format('M d, Y') }}
                            </span>
                        </div>
                    @endif

                    <div class="px-5 py-3.5">
                        <span class="text-xs text-gray-400 uppercase tracking-wide block mb-1.5">Purpose</span>
                        <p class="text-sm text-gray-800 leading-relaxed">{{ $request->purpose }}</p>
                    </div>

                    @if($request->notes)
                        <div class="px-5 py-3.5">
                            <span class="text-xs text-gray-400 uppercase tracking-wide block mb-1.5">Notes</span>
                            <p class="text-sm text-gray-800 leading-relaxed">{{ $request->notes }}</p>
                        </div>
                    @endif

                    @if($request->remarks)
                        <div class="px-5 py-3.5">
                            <span class="text-xs text-gray-400 uppercase tracking-wide block mb-1.5">Remarks</span>
                            <p class="text-sm text-gray-800 leading-relaxed">{{ $request->remarks }}</p>
                        </div>
                    @endif

                    @if($request->status === 'cancelled' && $request->cancelled_at)
                        <div class="px-5 py-3.5 cancelled-row">
                            <span class="text-xs cancelled-label uppercase tracking-wide block mb-1.5">Cancelled At</span>
                            <p class="text-sm cancelled-val">
                                {{ \Carbon\Carbon::parse($request->cancelled_at)->format('M d, Y g:i A') }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Item Status Breakdown --}}
            @if($totalItems > 0)
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="card-hdr-bg px-5 py-4 border-b flex items-center gap-2">
                        <svg class="w-4 h-4 card-hdr-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Item Breakdown</h3>
                    </div>
                    <div class="px-5 py-4 space-y-3">
                        @if($pendingItems > 0)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full dot-pending flex-shrink-0"></span>
                                    <span class="text-sm text-gray-600">Pending</span>
                                </div>
                                <span class="text-sm font-semibold pill-pending px-2.5 py-0.5 rounded-full border">{{ $pendingItems }}</span>
                            </div>
                        @endif
                        @if($approvedItems > 0)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full dot-approved flex-shrink-0"></span>
                                    <span class="text-sm text-gray-600">Approved</span>
                                </div>
                                <span class="text-sm font-semibold pill-approved px-2.5 py-0.5 rounded-full border">{{ $approvedItems }}</span>
                            </div>
                        @endif
                        @if($rejectedItems > 0)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full dot-rejected flex-shrink-0"></span>
                                    <span class="text-sm text-gray-600">Rejected</span>
                                </div>
                                <span class="text-sm font-semibold pill-rejected px-2.5 py-0.5 rounded-full border">{{ $rejectedItems }}</span>
                            </div>
                        @endif

                        @if($totalItems > 0)
                            <div class="pt-2">
                                <div class="flex rounded-full overflow-hidden h-1.5 bg-gray-100">
                                    @if($approvedItems > 0)
                                        <div class="prog-approved h-1.5 transition-all" style="width: {{ ($approvedItems / $totalItems) * 100 }}%"></div>
                                    @endif
                                    @if($rejectedItems > 0)
                                        <div class="prog-rejected h-1.5 transition-all" style="width: {{ ($rejectedItems / $totalItems) * 100 }}%"></div>
                                    @endif
                                    @if($pendingItems > 0)
                                        <div class="prog-pending h-1.5 transition-all" style="width: {{ ($pendingItems / $totalItems) * 100 }}%"></div>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-400 mt-1.5 text-right">{{ $totalItems }} total item(s)</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

        </div>

        {{-- Right Column: Items Table --}}
        <div class="lg:col-span-2 space-y-5">

            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="card-hdr-bg px-6 py-4 border-b flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 card-hdr-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                        </svg>
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Items Ordered</h3>
                    </div>
                    <span class="text-xs items-pill px-2.5 py-1 rounded-full font-medium">
                        {{ $totalItems }} {{ Str::plural('item', $totalItems) }}
                    </span>
                </div>

                @if($request->requestItems->isEmpty())
                    <div class="py-16 text-center">
                        <div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-400">No items found for this request.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="thead-cream border-b border-gray-100">
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Item</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Qty</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Notes</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($request->requestItems as $requestItem)
                                    @php
                                        $ibadge = [
                                            'pending'   => 'ibadge-pending',
                                            'approved'  => 'ibadge-approved',
                                            'rejected'  => 'ibadge-rejected',
                                            'cancelled' => 'ibadge-cancelled',
                                            'issued'    => 'ibadge-issued',
                                        ][$requestItem->status] ?? 'ibadge-cancelled';
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-semibold text-gray-900">
                                                {{ $requestItem->item->name ?? 'Item Unavailable' }}
                                            </div>
                                            @if($requestItem->item?->storage_location)
                                                <div class="flex items-center gap-1 mt-1">
                                                    <svg class="w-3 h-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    </svg>
                                                    <span class="text-xs text-gray-400">{{ $requestItem->item->storage_location }}</span>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm text-gray-500">
                                                {{ $requestItem->item?->category?->name ?? 'Uncategorized' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm font-bold text-gray-900">{{ $requestItem->quantity }}</span>
                                            @if($requestItem->item?->unit)
                                                <span class="text-xs text-gray-400 ml-1">{{ $requestItem->item->unit }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full {{ $ibadge }}">
                                                {{ ucfirst($requestItem->status ?? 'pending') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($requestItem->remarks)
                                                <span class="text-sm text-gray-600">{{ $requestItem->remarks }}</span>
                                            @else
                                                <span class="text-gray-300 text-sm">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            {{-- Rejection Notice --}}
            @if($request->status === 'rejected' && $request->remarks)
                <div class="bg-red-50 border border-red-200 rounded-xl p-5">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-red-800 mb-1">Reason for Rejection</h4>
                            <p class="text-sm text-red-700 leading-relaxed">{{ $request->remarks }}</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Reorder CTA --}}
            @if(in_array($request->status, ['cancelled', 'rejected']))
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div>
                        <p class="text-sm font-semibold text-gray-700">Want to submit a similar request?</p>
                        <p class="text-xs text-gray-400 mt-0.5">Browse available items and add them to your cart.</p>
                    </div>
                    <a href="{{ route('requests.index') }}"
                        class="btn-reorder inline-flex items-center gap-2 px-5 py-2.5 rounded-lg transition text-sm font-semibold flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Browse Items
                    </a>
                </div>
            @endif

        </div>
    </div>

@endsection