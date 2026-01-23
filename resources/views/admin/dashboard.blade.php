<x-app-layout>
    <div class="flex min-h-screen bg-gray-100 dark:bg-gray-900">

        <!-- Sidebar -->
        <aside class="w-64 bg-slate-800 shadow-md flex flex-col">
            <div class="flex items-center justify-center py-8 border-b border-gray-700">
                <a href="{{ route('admin.dashboard') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="App Logo" class="h-32 w-auto">
                </a>
            </div>

            <nav class="mt-6 flex-1">
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 px-6 py-3 text-white hover:bg-slate-700 border-l-4 border-blue-500">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                    </svg>
                    Dashboard
                </a>

                <a href="#"
                   class="flex items-center gap-3 px-6 py-3 text-gray-300 hover:bg-slate-700 border-l-4 border-transparent hover:border-blue-500">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                    </svg>
                    Ordered Items
                </a>

                <a href="#"
                   class="flex items-center gap-3 px-6 py-3 text-gray-300 hover:bg-slate-700 border-l-4 border-transparent hover:border-blue-500">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3z"/>
                    </svg>
                    Inventory
                </a>

                <a href="#"
                   class="flex items-center gap-3 px-6 py-3 text-gray-300 hover:bg-slate-700 border-l-4 border-transparent hover:border-blue-500">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                    </svg>
                    Users
                </a>

                <a href="#"
                   class="flex items-center gap-3 px-6 py-3 text-gray-300 hover:bg-slate-700 border-l-4 border-transparent hover:border-blue-500">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                    </svg>
                    Addresses
                </a>

                <a href="#"
                   class="flex items-center gap-3 px-6 py-3 text-gray-300 hover:bg-slate-700 border-l-4 border-transparent hover:border-blue-500">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                    </svg>
                    Categories
                </a>

                <a href="#"
                   class="flex items-center gap-3 px-6 py-3 text-gray-300 hover:bg-slate-700 border-l-4 border-transparent hover:border-blue-500">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                    </svg>
                    Units
                </a>
            </nav>

            <div class="p-4 border-t border-gray-700">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 w-full px-6 py-3 text-gray-300 hover:bg-slate-700 hover:text-white rounded-lg">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"/>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col">
            <!-- Header -->
            <header class="bg-slate-800 shadow-lg">
                <div class="px-8 py-4 flex items-center justify-between">
                    <div>
                        <h2 class="font-bold text-2xl text-white">Dashboard</h2>
                        <p class="text-sm text-gray-400 mt-1">AFPPGMC Logistics Division</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 hover:bg-slate-700 px-4 py-2 rounded-lg transition">
                            <div class="text-right">
                                <span class="text-white font-medium block">{{ Auth::user()->name }}</span>
                                <span class="text-gray-400 text-sm uppercase">{{ Auth::user()->role }}</span>
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
            <div class="flex-1 p-8 bg-gray-50">
                
                <!-- Stats Grid -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    <!-- Pending Requests -->
                    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Pending Requests</p>
                                <p class="text-3xl font-bold text-gray-800">{{ $stats['pending_requests'] }}</p>
                            </div>
                            <div class="bg-yellow-100 p-3 rounded-full">
                                <span class="text-2xl">⏳</span>
                            </div>
                        </div>
                    </div>

                    <!-- Urgent Requests -->
                    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Urgent Requests</p>
                                <p class="text-3xl font-bold text-gray-800">{{ $stats['urgent_requests'] }}</p>
                            </div>
                            <div class="bg-red-100 p-3 rounded-full">
                                <span class="text-2xl">🚨</span>
                            </div>
                        </div>
                    </div>

                    <!-- Approved Requests -->
                    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Approved Requests</p>
                                <p class="text-3xl font-bold text-gray-800">{{ $stats['approved_requests'] }}</p>
                            </div>
                            <div class="bg-green-100 p-3 rounded-full">
                                <span class="text-2xl">👍</span>
                            </div>
                        </div>
                    </div>

                    <!-- Rejected Requests -->
                    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Rejected Requests</p>
                                <p class="text-3xl font-bold text-gray-800">{{ $stats['rejected_requests'] }}</p>
                            </div>
                            <div class="bg-red-100 p-3 rounded-full">
                                <span class="text-2xl">👎</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Two Column Layout -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    
                    <!-- Inventory Status -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Inventory Status</h3>
                        
                        <div class="space-y-4">
                            <!-- Low Stock Items -->
                            <div class="flex items-center gap-3 p-4 bg-yellow-50 rounded-lg">
                                <div class="bg-yellow-500 text-white rounded-full w-12 h-12 flex items-center justify-center text-xl font-bold">
                                    ⚠️
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-800">Low Stock Items</p>
                                    <p class="text-sm text-gray-600">Items below threshold</p>
                                </div>
                                <span class="text-2xl font-bold text-gray-800">{{ $stats['low_stock_items'] }}</span>
                            </div>

                            <!-- Expiring Soon -->
                            <div class="flex items-center gap-3 p-4 bg-red-50 rounded-lg">
                                <div class="bg-red-500 text-white rounded-full w-12 h-12 flex items-center justify-center text-xl font-bold">
                                    🔴
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-800">Expiring Soon</p>
                                    <p class="text-sm text-gray-600">Items expiring in 30 days</p>
                                </div>
                                <span class="text-2xl font-bold text-gray-800">{{ $stats['expiring_soon'] }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Most Requested Items -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Most Requested Items</h3>
                        
                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                                <span class="font-medium text-gray-800">Laptop</span>
                                <div class="w-48 bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-500 h-2 rounded-full" style="width: 85%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Critical Requests Table -->
                <div class="bg-white rounded-lg shadow-md p-6 mt-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Critical Requests</h3>
                        <span class="text-sm text-gray-500">Urgent & Pending</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b">
                                    <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Requester</th>
                                    <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Unit</th>
                                    <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Purpose</th>
                                    <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Created</th>
                                    <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentRequests as $request)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-3 px-4 text-sm text-gray-800">{{ $request->user->name }}</td>
                                        <td class="py-3 px-4 text-sm text-gray-600">-</td>
                                        <td class="py-3 px-4 text-sm text-gray-800">{{ $request->purpose }}</td>
                                        <td class="py-3 px-4 text-sm text-gray-600">{{ $request->created_at->format('M d, Y') }}</td>
                                        <td class="py-3 px-4">
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                                @if($request->status == 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($request->status == 'urgent') bg-red-100 text-red-800
                                                @elseif($request->status == 'approved') bg-green-100 text-green-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ ucfirst($request->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-8 text-gray-400 italic">
                                            No critical requests at this time
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </main>
    </div>
</x-app-layout>