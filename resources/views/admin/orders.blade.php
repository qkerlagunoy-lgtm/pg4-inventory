@extends('layouts.admin')

@section('content')
<div class="flex">
    <!-- Sidebar -->
    <aside class="w-64 bg-slate-800 shadow-md flex flex-col sticky top-0 max-h-screen">
        <div class="flex items-center justify-center py-8 border-b border-gray-700">
            <a href="{{ route('admin.dashboard') }}">
                <img src="{{ asset('images/logo.png') }}" alt="App Logo" class="h-32 w-auto">
            </a>
        </div>

        <nav class="mt-6 flex-1 overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-6 py-3 text-gray-300 hover:bg-slate-700 border-l-4 border-transparent hover:border-blue-500">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('admin.orders') }}"
               class="flex items-center gap-3 px-6 py-3 text-white hover:bg-slate-700 border-l-4 border-blue-500">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                </svg>
                Ordered Items
            </a>

            <a href="{{ route('admin.inventory') }}"
               class="flex items-center gap-3 px-6 py-3 text-gray-300 hover:bg-slate-700 border-l-4 border-transparent hover:border-blue-500">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3z"/>
                </svg>
                Inventory
            </a>

            <a href="{{ route('admin.users') }}"
               class="flex items-center gap-3 px-6 py-3 text-gray-300 hover:bg-slate-700 border-l-4 border-transparent hover:border-blue-500">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                </svg>
                Users
            </a>

            <a href="#"
               class="flex items-center gap-3 px-6 py-3 text-gray-300 hover:bg-slate-700 border-l-4 border-transparent hover:border-blue-500 transition">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                </svg>
                Addresses
            </a>

            <a href="{{ route('admin.categories') }}"
               class="flex items-center gap-3 px-6 py-3 text-gray-300 hover:bg-slate-700 border-l-4 border-transparent hover:border-blue-500 transition">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                </svg>
                Categories
            </a>

            <a href="#"
               class="flex items-center gap-3 px-6 py-3 text-gray-300 hover:bg-slate-700 border-l-4 border-transparent hover:border-blue-500 transition">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                </svg>
                Units
            </a>
        </nav>

        <div class="p-4 border-t border-gray-700">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center gap-3 w-full px-6 py-3 text-gray-300 hover:bg-slate-700 hover:text-white rounded-lg transition">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"/>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 min-h-screen">
        <!-- Header -->
        <header class="bg-slate-800 shadow-lg">
            <div class="px-8 py-4 flex items-center justify-between">
                <div>
                    <h2 class="font-bold text-2xl text-white">Order Requests</h2>
                    <p class="text-sm text-gray-400 mt-1">AFPPGMC Logistics Division</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 hover:bg-slate-700 px-4 py-2 rounded-lg transition">
                        <div class="text-right">
                            <span class="text-white font-medium block">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span>
                            <span class="text-gray-400 text-sm uppercase">{{ Auth::user()->type }}</span>
                        </div>
                    </a>
                   
                    <button class="p-2 hover:bg-slate-700 rounded-full relative">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <div class="min-h-screen p-8 bg-gray-50">
            {{-- Success Message --}}
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Filters -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <!-- Filter by Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Status:</label>
                        <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">All Statuses</option>
                            <option value="pending">Pending</option>
                            <option value="urgent">Urgent</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                            <option value="delivered">Delivered</option>
                        </select>
                    </div>

                    <!-- Filter by Urgency -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Urgency:</label>
                        <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">All Urgencies</option>
                            <option value="urgent">Urgent</option>
                            <option value="normal">Normal</option>
                        </select>
                    </div>
                </div>

                <!-- Search -->
                <div>
                    <div class="flex gap-2">
                        <input type="text" placeholder="Search by username or purpose..." 
                               class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <button class="px-8 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition font-medium">
                            Search
                        </button>
                        <button class="px-8 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition font-medium">
                            Reset
                        </button>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Requester</th>
                                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Purpose</th>
                                <th class="text-center py-3 px-4 text-sm font-semibold text-gray-700">Urgency</th>
                                <th class="text-center py-3 px-4 text-sm font-semibold text-gray-700">Status</th>
                                <th class="text-center py-3 px-4 text-sm font-semibold text-gray-700">Date Requested</th>
                                <th class="text-center py-3 px-4 text-sm font-semibold text-gray-700">Date Delivered</th>
                                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Items</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($orders as $order)
                                <tr class="border-b hover:bg-gray-50 transition">
                                    <td class="py-3 px-4 text-sm text-gray-900">{{ $order->requester }}</td>
                                    <td class="py-3 px-4 text-sm text-gray-900">{{ $order->purpose }}</td>

                                    <td class="py-3 px-4 text-center">
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                            {{ $order->urgency }}
                                        </span>
                                    </td>

                                    <td class="py-3 px-4 text-center">
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                                            @if($order->status === 'Pending') bg-gray-100 text-gray-800
                                            @elseif($order->status === 'Approved') bg-blue-100 text-blue-800
                                            @else bg-green-100 text-green-800
                                            @endif">
                                            {{ $order->status }}
                                        </span>
                                    </td>

                                    <td class="py-3 px-4 text-center text-sm text-gray-600">{{ $order->date_requested }}</td>
                                    <td class="py-3 px-4 text-center text-sm text-gray-600">{{ $order->date_delivered ?? '—' }}</td>

                                    <td class="py-3 px-4 text-sm">
                                        <ul class="space-y-1">
                                            @foreach ($order->items as $item)
                                                <li class="text-gray-900">
                                                    {{ $item->item->item_name }} 
                                                    <span class="text-gray-500">({{ $item->quantity }})</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-12">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900">No orders found</h3>
                                        <p class="mt-1 text-sm text-gray-500">Get started by creating a new order request.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Generate PDF Button -->
            <div class="mt-6 flex justify-end">
                <button class="px-8 py-3 bg-amber-700 text-white rounded-lg hover:bg-amber-800 transition shadow-md hover:shadow-lg font-medium">
                    Generate PDF
                </button>
            </div>
        </div>
    </main>
</div>
@endsection