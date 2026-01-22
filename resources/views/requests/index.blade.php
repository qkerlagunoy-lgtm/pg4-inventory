<x-app-layout>
    <div class="flex min-h-screen bg-gray-100 dark:bg-gray-900">

        <!-- Sidebar (same as dashboard) -->
        <aside class="w-64 bg-slate-800 shadow-md flex flex-col">
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
                   class="flex items-center gap-3 px-6 py-3 text-white hover:bg-slate-700 border-l-4 border-blue-500">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                    </svg>
                    Request Items
                </a>

                <a href="{{ route('requests.myRequests') }}"
                   class="flex items-center gap-3 px-6 py-3 text-gray-300 hover:bg-slate-700 border-l-4 border-transparent hover:border-blue-500">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                    </svg>
                    Ordered Items
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
                        <h2 class="font-bold text-2xl text-white">Item Requests</h2>
                        <p class="text-sm text-gray-400 mt-1">AFPPGMC Logistics Division</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 hover:bg-slate-700 px-4 py-2 rounded-lg transition">
                            <div class="text-right">
                                <span class="text-white font-medium block">{{ Auth::user()->name }}</span>
                                <span class="text-gray-400 text-sm">User</span>
                            </div>
                        </a>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="flex-1 p-8 bg-gray-50">
                
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

                <!-- Action Buttons -->
                <div class="mb-6 flex gap-4">
                    <a href="{{ route('requests.cart') }}" class="bg-yellow-600 hover:bg-yellow-700 text-white px-6 py-2 rounded-lg font-semibold flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3z"/>
                        </svg>
                        View Cart ({{ count(session('cart', [])) }})
                    </a>
                </div>

                <!-- Search Bar -->
                <div class="mb-6 flex gap-4">
                    <input type="text" id="searchInput" placeholder="Search items..." 
                        class="flex-1 px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    <button onclick="resetSearch()" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg font-semibold">
                        Reset
                    </button>
                </div>

                <!-- Items Table -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <table class="w-full" id="itemsTable">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Category</th>
                                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Item Name</th>
                                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Description</th>
                                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Available</th>
                                <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($items as $item)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="py-3 px-4 text-sm text-gray-800">{{ $item->category }}</td>
                                    <td class="py-3 px-4 text-sm text-gray-800">{{ $item->item_name }}</td>
                                    <td class="py-3 px-4 text-sm text-gray-600">{{ $item->description ?? 'None' }}</td>
                                    <td class="py-3 px-4 text-sm text-gray-800">{{ $item->available_quantity }}</td>
                                    <td class="py-3 px-4">
                                        <form action="{{ route('requests.addToCart') }}" method="POST" class="flex items-center gap-2">
                                            @csrf
                                            <input type="hidden" name="item_id" value="{{ $item->id }}">
                                            <input type="number" name="quantity" min="1" max="{{ $item->available_quantity }}" value="1" 
                                                class="w-20 px-2 py-1 border rounded">
                                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1 rounded text-sm">
                                                Add to Cart
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-8 text-gray-400 italic">
                                        No items available
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script>
        const searchInput = document.getElementById('searchInput');
        const table = document.getElementById('itemsTable');
        const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

        searchInput.addEventListener('keyup', function() {
            const filter = searchInput.value.toLowerCase();

            for (let i = 0; i < rows.length; i++) {
                const cells = rows[i].getElementsByTagName('td');
                let found = false;

                for (let j = 0; j < cells.length - 1; j++) {
                    const cellText = cells[j].textContent || cells[j].innerText;
                    if (cellText.toLowerCase().indexOf(filter) > -1) {
                        found = true;
                        break;
                    }
                }

                rows[i].style.display = found ? '' : 'none';
            }
        });

        function resetSearch() {
            searchInput.value = '';
            for (let i = 0; i < rows.length; i++) {
                rows[i].style.display = '';
            }
        }
    </script>
</x-app-layout>