<x-app-layout>
    <div class="flex min-h-screen bg-gray-100 dark:bg-gray-900">

        <!-- Sidebar -->
        <aside class="w-64 min-w-[16rem] bg-slate-800 shadow-md flex flex-col">
            <div class="flex items-center justify-center py-8 border-b border-gray-700">
                <a href="{{ route('dashboard') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="App Logo" class="h-32 w-auto">
                </a>
            </div>

            <nav class="mt-6 flex-1">
                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-3 px-6 py-3 text-gray-300 hover:bg-slate-700 border-l-4 border-transparent hover:border-blue-500">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                    </svg>
                    Dashboard
                </a>

                <a href="{{ route('requests.index') }}"
                   class="flex items-center gap-3 px-6 py-3 text-gray-300 hover:bg-slate-700 border-l-4 border-transparent hover:border-blue-500">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                    </svg>
                    Request Items
                </a>

                {{-- FIXED: Changed to correct route name --}}
                <a href="{{ route('requests.my-requests') }}"
                   class="flex items-center gap-3 px-6 py-3 text-white bg-slate-700 border-l-4 border-blue-500">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3z"/>
                    </svg>
                    Ordered Items
                </a>
            </nav>

            <div class="p-4 border-t border-gray-700">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 w-full px-6 py-3 text-gray-300 hover:bg-slate-700 rounded-lg">
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col">

            <!-- Header -->
            <header class="bg-slate-800 shadow">
                <div class="px-10 py-5 flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-semibold text-white">My Orders</h2>
                        <p class="text-sm text-gray-400">AFPPGMC Logistics Division</p>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="text-right">
                            <p class="text-white font-medium">{{ Auth::user()->name }}</p>
                            <p class="text-gray-400 text-sm">User</p>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="flex-1 bg-gray-50 px-10 py-6">

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Filters -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <form method="GET" action="{{ route('requests.my-requests') }}">
                        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 items-end">

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Status</label>
                                <select name="status" class="w-full h-11 border rounded-lg px-4">
                                    <option value="">All Statuses</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Priority</label>
                                <select name="priority" class="w-full h-11 border rounded-lg px-4">
                                    <option value="">All Priorities</option>
                                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                                    <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                </select>
                            </div>

                            <div class="lg:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Search by purpose</label>
                                <div class="flex gap-3">
                                    <input type="text" name="search" placeholder="Search by purpose..."
                                           value="{{ request('search') }}"
                                           class="flex-1 h-11 border rounded-lg px-4">
                                    <button type="submit" class="h-11 px-6 bg-blue-600 text-white rounded-lg">Search</button>
                                    <a href="{{ route('requests.my-requests') }}" class="h-11 px-6 bg-gray-600 text-white rounded-lg flex items-center">
                                        Reset
                                    </a>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>

                <!-- Orders Table -->
                <div class="bg-white rounded-lg shadow overflow-x-auto">
                    <table class="w-full min-w-[1100px]">
                        <thead class="bg-gray-100 border-b">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Request ID</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Purpose</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Priority</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Status</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Date Requested</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Items Count</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($requests as $request)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">#{{ str_pad($request->id, 6, '0', STR_PAD_LEFT) }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-800">{{ Str::limit($request->purpose, 50) }}</td>
                                    <td class="px-6 py-4">
                                        @php
                                            $priorityColors = [
                                                'low' => 'bg-gray-100 text-gray-800',
                                                'medium' => 'bg-blue-100 text-blue-800',
                                                'high' => 'bg-yellow-100 text-yellow-800',
                                                'urgent' => 'bg-red-100 text-red-800'
                                            ];
                                            $color = $priorityColors[$request->priority] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <span class="px-3 py-1 text-xs font-medium rounded-full {{ $color }}">
                                            {{ ucfirst($request->priority) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $statusColors = [
                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                'approved' => 'bg-green-100 text-green-800',
                                                'rejected' => 'bg-red-100 text-red-800',
                                                'cancelled' => 'bg-gray-100 text-gray-800'
                                            ];
                                            $color = $statusColors[$request->status] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <span class="px-3 py-1 text-xs font-medium rounded-full {{ $color }}">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $request->request_date->format('M d, Y h:i A') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $request->requestItems->count() }} item(s)
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex gap-2">
                                            <a href="{{ route('requests.show', $request->id) }}" 
                                               class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                View Details
                                            </a>
                                            @if($request->isPending() || $request->isApproved())
                                                <form method="POST" action="{{ route('requests.cancel', $request->id) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            onclick="return confirm('Are you sure you want to cancel this request?')"
                                                            class="text-red-600 hover:text-red-800 text-sm font-medium ml-4">
                                                        Cancel
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-20 text-gray-500">
                                        No orders found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    
                    {{-- Pagination --}}
                    @if($requests->hasPages())
                        <div class="px-6 py-4 border-t">
                            {{ $requests->links() }}
                        </div>
                    @endif
                </div>

            </div>
        </main>
    </div>
</x-app-layout>