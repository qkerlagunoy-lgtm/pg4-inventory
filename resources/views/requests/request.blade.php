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
            <li class="text-blue-600 font-medium">Request #{{ $request->id }}</li>
        </ol>
    </nav>
@endsection

@section('header-actions')
    <div class="flex items-center gap-2">
        <a href="{{ route('requests.my-requests') }}"
            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to My Requests
        </a>

        @if(in_array($request->status, ['pending', 'approved']))
            <form method="POST" action="{{ route('requests.cancel', $request->id) }}"
                onsubmit="return confirm('Are you sure you want to cancel this request?')">
                @csrf
                <button type="submit"
                    class="px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Cancel Request
                </button>
            </form>
        @endif
    </div>
@endsection

@section('content')

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center gap-2">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg flex items-center gap-2">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left Column: Request Info --}}
        <div class="lg:col-span-1 space-y-6">

            {{-- Status Card --}}
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Request Status</h3>

                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm text-gray-500">Request #{{ $request->id }}</span>
                    @php
                        $statusColors = [
                            'pending'   => 'bg-yellow-100 text-yellow-800 border border-yellow-200',
                            'approved'  => 'bg-green-100 text-green-800 border border-green-200',
                            'rejected'  => 'bg-red-100 text-red-800 border border-red-200',
                            'cancelled' => 'bg-gray-100 text-gray-600 border border-gray-200',
                            'completed' => 'bg-blue-100 text-blue-800 border border-blue-200',
                        ];
                        $statusColor = $statusColors[$request->status] ?? 'bg-gray-100 text-gray-600';
                    @endphp
                    <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $statusColor }}">
                        {{ ucfirst($request->status) }}
                    </span>
                </div>

                @php
                    $priorityColors = [
                        'low'    => 'bg-gray-100 text-gray-600',
                        'medium' => 'bg-blue-100 text-blue-700',
                        'high'   => 'bg-orange-100 text-orange-700',
                        'urgent' => 'bg-red-100 text-red-700',
                    ];
                    $priorityColor = $priorityColors[$request->priority] ?? 'bg-gray-100 text-gray-600';
                @endphp

                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Priority</span>
                    <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $priorityColor }}">
                        {{ ucfirst($request->priority) }}
                    </span>
                </div>
            </div>

            {{-- Request Details Card --}}
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Details</h3>

                <div class="space-y-3 text-sm">
                    <div>
                        <span class="text-gray-500 block mb-1">Date Submitted</span>
                        <span class="text-gray-900 font-medium">
                            {{ $request->request_date ? $request->request_date->format('M d, Y h:i A') : $request->created_at->format('M d, Y h:i A') }}
                        </span>
                    </div>

                    @if($request->required_date)
                        <div>
                            <span class="text-gray-500 block mb-1">Required By</span>
                            <span class="text-gray-900 font-medium">
                                {{ \Carbon\Carbon::parse($request->required_date)->format('M d, Y') }}
                            </span>
                        </div>
                    @endif

                    <div>
                        <span class="text-gray-500 block mb-1">Purpose</span>
                        <span class="text-gray-900">{{ $request->purpose }}</span>
                    </div>

                    @if($request->notes)
                        <div>
                            <span class="text-gray-500 block mb-1">Notes</span>
                            <span class="text-gray-900">{{ $request->notes }}</span>
                        </div>
                    @endif

                    @if($request->remarks)
                        <div>
                            <span class="text-gray-500 block mb-1">Remarks</span>
                            <span class="text-gray-900">{{ $request->remarks }}</span>
                        </div>
                    @endif

                    @if($request->status === 'cancelled' && $request->cancelled_at)
                        <div class="pt-2 border-t border-gray-100">
                            <span class="text-red-500 block mb-1">Cancelled At</span>
                            <span class="text-gray-900 font-medium">
                                {{ \Carbon\Carbon::parse($request->cancelled_at)->format('M d, Y h:i A') }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Summary Card --}}
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Summary</h3>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Total Items</span>
                        <span class="font-semibold text-gray-900">{{ $request->requestItems->count() }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Total Quantity</span>
                        <span class="font-semibold text-gray-900">{{ $request->requestItems->sum('quantity') }}</span>
                    </div>
                    @php
                        $approvedItems = $request->requestItems->where('status', 'approved')->count();
                        $rejectedItems = $request->requestItems->where('status', 'rejected')->count();
                        $pendingItems  = $request->requestItems->where('status', 'pending')->count();
                    @endphp
                    @if($approvedItems > 0)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Approved Items</span>
                            <span class="font-semibold text-green-600">{{ $approvedItems }}</span>
                        </div>
                    @endif
                    @if($rejectedItems > 0)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Rejected Items</span>
                            <span class="font-semibold text-red-600">{{ $rejectedItems }}</span>
                        </div>
                    @endif
                    @if($pendingItems > 0)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Pending Items</span>
                            <span class="font-semibold text-yellow-600">{{ $pendingItems }}</span>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        {{-- Right Column: Items Ordered --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Items Ordered</h3>
                    <p class="text-sm text-gray-500 mt-1">{{ $request->requestItems->count() }} item(s) in this request</p>
                </div>

                @if($request->requestItems->isEmpty())
                    <div class="p-12 text-center">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                        </svg>
                        <p class="text-gray-500">No items found for this request.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($request->requestItems as $requestItem)
                                    @php
                                        $itemStatusColors = [
                                            'pending'   => 'bg-yellow-100 text-yellow-700',
                                            'approved'  => 'bg-green-100 text-green-700',
                                            'rejected'  => 'bg-red-100 text-red-700',
                                            'cancelled' => 'bg-gray-100 text-gray-500',
                                            'issued'    => 'bg-blue-100 text-blue-700',
                                        ];
                                        $itemStatusColor = $itemStatusColors[$requestItem->status] ?? 'bg-gray-100 text-gray-500';
                                    @endphp
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $requestItem->item->name ?? 'Item Unavailable' }}
                                            </div>
                                            @if($requestItem->item?->storage_location)
                                                <div class="text-xs text-gray-400 mt-1">
                                                    📍 {{ $requestItem->item->storage_location }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            {{ $requestItem->item?->category?->name ?? 'Uncategorized' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900 font-medium">
                                            {{ $requestItem->quantity }}
                                            <span class="text-gray-400 font-normal text-xs">
                                                {{ $requestItem->item?->unit ?? '' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $itemStatusColor }}">
                                                {{ ucfirst($requestItem->status ?? 'pending') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            {{ $requestItem->remarks ?? '—' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            {{-- Admin Response (if rejected or has admin notes) --}}
            @if($request->status === 'rejected' && $request->remarks)
                <div class="mt-6 bg-red-50 border border-red-200 rounded-lg p-6">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <h4 class="text-sm font-semibold text-red-800 mb-1">Reason for Rejection</h4>
                            <p class="text-sm text-red-700">{{ $request->remarks }}</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Reorder Button (if cancelled or rejected) --}}
            @if(in_array($request->status, ['cancelled', 'rejected']))
                <div class="mt-6 bg-white rounded-lg shadow-md p-6 text-center">
                    <p class="text-gray-500 text-sm mb-4">Want to submit a similar request?</p>
                    <a href="{{ route('requests.index') }}"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Browse Items
                    </a>
                </div>
            @endif

        </div>
    </div>

@endsection