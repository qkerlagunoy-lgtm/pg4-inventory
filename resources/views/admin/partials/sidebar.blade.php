<aside class="w-64 bg-slate-800 shadow-md flex flex-col sticky top-0 max-h-screen">
    <div class="flex items-center justify-center py-8 border-b border-gray-700">
        <a href="{{ route('admin.dashboard') }}">
            <img src="{{ asset('images/logo.png') }}" alt="App Logo" class="h-32 w-auto">
        </a>
    </div>

    <nav class="mt-6 flex-1">
        @php
            $currentRoute = Route::currentRouteName();
            
            // Define which routes belong to which sidebar item
            $activeRoutes = [
                'dashboard' => [
                    'active' => in_array($currentRoute, ['admin.dashboard']),
                    'routes' => ['admin.dashboard'],
                ],
                'orders' => [
                    'active' => str_starts_with($currentRoute, 'admin.orders'),
                    'routes' => [
                        'admin.orders.index',
                        'admin.orders.pending',
                        'admin.orders.approved',
                        'admin.orders.rejected',
                        'admin.orders.review',
                        'admin.orders.create-issuance',
                        'admin.orders.issuances',
                        'admin.orders.issuances.view',
                        'admin.orders.returns',
                        'admin.orders.reports',
                        'admin.orders.export',
                    ],
                ],
                'inventory' => [
                    'active' => in_array($currentRoute, ['admin.inventory']),
                    'routes' => ['admin.inventory'],
                ],
                'users' => [
                    'active' => in_array($currentRoute, ['admin.users']),
                    'routes' => ['admin.users'],
                ],
                'categories' => [
                    'active' => in_array($currentRoute, ['admin.categories']),
                    'routes' => ['admin.categories'],
                ],
            ];
        @endphp

        <a href="{{ route('admin.dashboard') }}"
            class="flex items-center gap-3 px-6 py-3 {{ $activeRoutes['dashboard']['active'] ? 'text-white bg-slate-700 border-l-4 border-blue-500' : 'text-gray-300 hover:bg-slate-700 border-l-4 border-transparent hover:border-blue-500' }}">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
            </svg>
            Dashboard
        </a>

        <div class="relative">
            <a href="{{ route('admin.orders.index') }}"
                class="flex items-center justify-between px-6 py-3 {{ $activeRoutes['orders']['active'] ? 'text-white bg-slate-700 border-l-4 border-blue-500' : 'text-gray-300 hover:bg-slate-700 border-l-4 border-transparent hover:border-blue-500' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                    </svg>
                    Ordered Items
                </div>
                <!-- Dropdown arrow -->
                <svg id="ordersDropdownArrow" class="w-4 h-4 transition-transform duration-200 {{ $activeRoutes['orders']['active'] ? 'rotate-90' : '' }}" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                </svg>
            </a>
            
            <!-- Order Management Submenu -->
            <div id="ordersSubmenu" class="{{ $activeRoutes['orders']['active'] ? 'block' : 'hidden' }} bg-slate-900">
                <!-- Order Dashboard -->
                <a href="{{ route('admin.orders.index') }}"
                    class="flex items-center gap-3 px-10 py-2 {{ $currentRoute == 'admin.orders.index' ? 'text-white bg-blue-900/30 border-l-2 border-blue-400' : 'text-gray-300 hover:bg-slate-700' }}">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"/>
                    </svg>
                    Dashboard
                </a>
                
                <!-- Pending -->
                <a href="{{ route('admin.orders.pending') }}"
                    class="flex items-center gap-3 px-10 py-2 {{ in_array($currentRoute, ['admin.orders.pending', 'admin.orders.review']) ? 'text-white bg-blue-900/30 border-l-2 border-yellow-400' : 'text-gray-300 hover:bg-slate-700' }}">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.414L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                    Pending
                    @php
                        $pendingCount = \App\Models\ItemRequest::where('status', 'pending')->count();
                    @endphp
                    @if($pendingCount > 0)
                        <span class="ml-auto bg-yellow-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                            {{ $pendingCount }}
                        </span>
                    @endif
                </a>
                
                <!-- Approved -->
                <a href="{{ route('admin.orders.approved') }}"
                    class="flex items-center gap-3 px-10 py-2 {{ $currentRoute == 'admin.orders.approved' ? 'text-white bg-blue-900/30 border-l-2 border-green-400' : 'text-gray-300 hover:bg-slate-700' }}">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    Approved
                </a>
                
                <!-- Rejected -->
                <a href="{{ route('admin.orders.rejected') }}"
                    class="flex items-center gap-3 px-10 py-2 {{ $currentRoute == 'admin.orders.rejected' ? 'text-white bg-blue-900/30 border-l-2 border-red-400' : 'text-gray-300 hover:bg-slate-700' }}">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                    Rejected
                </a>
                
                <!-- Issuances -->
                <a href="{{ route('admin.orders.issuances') }}"
                    class="flex items-center gap-3 px-10 py-2 {{ in_array($currentRoute, ['admin.orders.issuances', 'admin.orders.create-issuance', 'admin.orders.issuances.view']) ? 'text-white bg-blue-900/30 border-l-2 border-blue-400' : 'text-gray-300 hover:bg-slate-700' }}">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"/>
                        <path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd"/>
                    </svg>
                    Issuances
                </a>
                
                <!-- Returns -->
                <a href="{{ route('admin.orders.returns') }}"
                    class="flex items-center gap-3 px-10 py-2 {{ $currentRoute == 'admin.orders.returns' ? 'text-white bg-blue-900/30 border-l-2 border-purple-400' : 'text-gray-300 hover:bg-slate-700' }}">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                    </svg>
                    Returns
                </a>
                
                <!-- Reports -->
                <a href="{{ route('admin.orders.reports') }}"
                    class="flex items-center gap-3 px-10 py-2 {{ $currentRoute == 'admin.orders.reports' ? 'text-white bg-blue-900/30 border-l-2 border-teal-400' : 'text-gray-300 hover:bg-slate-700' }}">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    Reports
                </a>
            </div>
        </div>

        <a href="{{ route('admin.inventory') }}"
            class="flex items-center gap-3 px-6 py-3 {{ $activeRoutes['inventory']['active'] ? 'text-white bg-slate-700 border-l-4 border-blue-500' : 'text-gray-300 hover:bg-slate-700 border-l-4 border-transparent hover:border-blue-500' }}">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3z"/>
            </svg>
            Inventory
        </a>

        <a href="{{ route('admin.users') }}"
            class="flex items-center gap-3 px-6 py-3 {{ $activeRoutes['users']['active'] ? 'text-white bg-slate-700 border-l-4 border-blue-500' : 'text-gray-300 hover:bg-slate-700 border-l-4 border-transparent hover:border-blue-500' }}">
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

        <a href="{{ route('admin.categories') }}"
            class="flex items-center gap-3 px-6 py-3 {{ $activeRoutes['categories']['active'] ? 'text-white bg-slate-700 border-l-4 border-blue-500' : 'text-gray-300 hover:bg-slate-700 border-l-4 border-transparent hover:border-blue-500' }}">
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ordersLink = document.querySelector('a[href="{{ route('admin.orders.index') }}"]');
        const ordersSubmenu = document.getElementById('ordersSubmenu');
        const ordersDropdownArrow = document.getElementById('ordersDropdownArrow');
        
        // Toggle orders submenu
        if (ordersLink && ordersSubmenu) {
            ordersLink.addEventListener('click', function(e) {
                // Only toggle if clicking the main link, not navigating
                if (e.target.closest('a').getAttribute('href') === '{{ route('admin.orders.index') }}') {
                    e.preventDefault();
                    ordersSubmenu.classList.toggle('hidden');
                    ordersDropdownArrow.classList.toggle('rotate-90');
                }
            });
        }
        
        // Keep submenu open if any order route is active
        const isOrderRouteActive = {{ $activeRoutes['orders']['active'] ? 'true' : 'false' }};
        if (isOrderRouteActive) {
            ordersSubmenu.classList.remove('hidden');
            if (ordersDropdownArrow) {
                ordersDropdownArrow.classList.add('rotate-90');
            }
        }
    });
</script>