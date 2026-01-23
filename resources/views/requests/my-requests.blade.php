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

                <a href="{{ route('requests.myRequests') }}"
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
                        <button class="p-2 rounded-full hover:bg-slate-700">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="flex-1 bg-gray-50 px-10 py-6">

                <!-- Filters -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <form method="GET" action="{{ route('requests.myRequests') }}">
                        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 items-end">

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Status</label>
                                <select name="status" class="w-full h-11 border rounded-lg px-4">
                                    <option value="">All Statuses</option>
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Urgency</label>
                                <select class="w-full h-11 border rounded-lg px-4">
                                    <option>All Urgencies</option>
                                </select>
                            </div>

                            <div class="lg:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Search by purpose</label>
                                <div class="flex gap-3">
                                    <input type="text" name="search" placeholder="Search by purpose..."
                                           class="flex-1 h-11 border rounded-lg px-4">
                                    <button class="h-11 px-6 bg-blue-600 text-white rounded-lg">Search</button>
                                    <a href="{{ route('requests.myRequests') }}" class="h-11 px-6 bg-gray-600 text-white rounded-lg flex items-center">
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
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Purpose</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Urgency</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Status</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Date Requested</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Date Delivered</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Items</th>
                            </tr>
                        </thead>

                        <tbody>
                            @if($requests->count() === 0)
                                <tr>
                                    <td colspan="6" class="text-center py-20 text-gray-500">
                                        No orders found
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

            </div>
        </main>
    </div>
</x-app-layout>
