<header class="bg-slate-800 shadow-lg">
    <div class="px-8 py-4 flex items-center justify-between">
        <div>
            @php
                // Module titles
                $moduleTitles = [
                    'admin.dashboard' => 'Admin Dashboard',
                    'admin.orders.index' => 'Order Management',
                    'admin.orders.pending' => 'Pending Requests',
                    'admin.orders.approved' => 'Approved Requests',
                    'admin.orders.rejected' => 'Rejected Requests',
                    'admin.orders.review' => 'Review Request',
                    'admin.orders.create-issuance' => 'Create Issuance',
                    'admin.orders.issuances' => 'Issuances',
                    'admin.orders.issuances.view' => 'View Issuance',
                    'admin.orders.returns' => 'Item Returns',
                    'admin.orders.reports' => 'Reports & Analytics',
                    'admin.inventory.index' => 'Inventory Management',
                    'admin.users.index' => 'User Management',
                    'admin.categories.index' => 'Category Management',
                    'profile.edit' => 'My Profile',
                ];
                
                $currentTitle = $moduleTitles[Route::currentRouteName()] ?? 'Admin Dashboard';
            @endphp
            
            <h2 class="font-bold text-2xl text-white">{{ $currentTitle }}</h2>

            @php
                // Breadcrumbs
                $breadcrumbs = [
                    'admin.dashboard' => [['title' => 'Dashboard', 'url' => route('admin.dashboard')]],
                    'admin.orders.index' => [
                        ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
                        ['title' => 'Order Management', 'url' => route('admin.orders.index')]
                    ],
                    'admin.orders.pending' => [
                        ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
                        ['title' => 'Order Management', 'url' => route('admin.orders.index')],
                        ['title' => 'Pending Requests', 'url' => route('admin.orders.pending')]
                    ],
                    'admin.orders.approved' => [
                        ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
                        ['title' => 'Order Management', 'url' => route('admin.orders.index')],
                        ['title' => 'Approved Requests', 'url' => route('admin.orders.approved')]
                    ],
                    'admin.orders.rejected' => [
                        ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
                        ['title' => 'Order Management', 'url' => route('admin.orders.index')],
                        ['title' => 'Rejected Requests', 'url' => route('admin.orders.rejected')]
                    ],
                    'admin.orders.review' => [
                        ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
                        ['title' => 'Order Management', 'url' => route('admin.orders.index')],
                        ['title' => 'Pending Requests', 'url' => route('admin.orders.pending')],
                        ['title' => 'Review Request', 'url' => '#']
                    ],
                    'admin.orders.create-issuance' => [
                        ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
                        ['title' => 'Order Management', 'url' => route('admin.orders.index')],
                        ['title' => 'Create Issuance', 'url' => '#']
                    ],
                    'admin.orders.issuances' => [
                        ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
                        ['title' => 'Order Management', 'url' => route('admin.orders.index')],
                        ['title' => 'Issuances', 'url' => route('admin.orders.issuances')]
                    ],
                    'admin.orders.returns' => [
                        ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
                        ['title' => 'Order Management', 'url' => route('admin.orders.index')],
                        ['title' => 'Item Returns', 'url' => route('admin.orders.returns')]
                    ],
                    'admin.orders.reports' => [
                        ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
                        ['title' => 'Order Management', 'url' => route('admin.orders.index')],
                        ['title' => 'Reports', 'url' => route('admin.orders.reports')]
                    ],
                    'admin.inventory.index' => [
                        ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
                        ['title' => 'Inventory', 'url' => route('admin.inventory.index')]
                    ],
                    'admin.users.index' => [
                        ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
                        ['title' => 'User Management', 'url' => route('admin.users.index')]
                    ],
                    'admin.categories.index' => [
                        ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
                        ['title' => 'Category Management', 'url' => route('admin.categories.index')]
                    ],
                ];
                
                $currentBreadcrumbs = $breadcrumbs[Route::currentRouteName()] ?? [];
            @endphp
            
            @if(count($currentBreadcrumbs) > 0)
                <div class="flex items-center space-x-1 text-xs mt-2">
                    @foreach($currentBreadcrumbs as $breadcrumb)
                        @if(!$loop->last)
                            <a href="{{ $breadcrumb['url'] }}" class="text-gray-300 hover:text-white transition">
                                {{ $breadcrumb['title'] }}
                            </a>
                            <span class="text-gray-500">/</span>
                        @else
                            <span class="text-gray-400">{{ $breadcrumb['title'] }}</span>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>

        <div class="flex items-center gap-3">
            <!-- User Profile -->
            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 hover:bg-slate-700 px-4 py-2 rounded-lg transition group">
                <div class="text-right">
                    <span class="text-white font-medium block group-hover:text-blue-300 transition">
                        {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                    </span>
                    <span class="text-gray-400 text-sm uppercase group-hover:text-gray-300 transition">
                        {{ Auth::user()->type }}
                    </span>
                </div>
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-semibold shadow-md group-hover:shadow-lg transition">
                    {{ Str::upper(substr(Auth::user()->first_name, 0, 1) . substr(Auth::user()->last_name, 0, 1)) }}
                </div>
            </a>
            
            <!-- Notification Bell -->
            <div class="relative">
                <button id="notificationButton" class="p-2 hover:bg-slate-700 rounded-full relative group">
                    <svg class="w-6 h-6 text-white group-hover:text-blue-300 transition" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                    </svg>
                    @if(Auth::user()->unreadNotifications()->count() > 0)
                        <span class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold shadow-md animate-pulse">
                            {{ Auth::user()->unreadNotifications()->count() }}
                        </span>
                    @endif
                </button>

                <!-- Notification Dropdown -->
                <div id="notificationDropdown" class="hidden absolute right-0 mt-2 w-96 bg-white rounded-lg shadow-2xl z-50 border border-gray-200">
                    <div class="flex items-center justify-between p-4 border-b bg-slate-50">
                        <h3 class="text-lg font-bold text-gray-800">Notifications</h3>
                        @if(Auth::user()->unreadNotifications()->count() > 0)
                            <button class="text-sm text-blue-600 hover:text-blue-800 font-medium mark-all-read flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Mark All as Read
                            </button>
                        @endif
                    </div>
                    
                    <div class="max-h-96 overflow-y-auto">
                        @forelse(Auth::user()->notifications()->latest()->take(10)->get() as $notification)
                            <a href="#" class="block p-4 border-b hover:bg-blue-50 transition {{ $notification->is_read ? 'bg-gray-50' : 'bg-blue-50' }}">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="flex items-center mb-1">
                                            @php
                                                $typeIcons = [
                                                    'request_approved' => 'text-green-500',
                                                    'request_rejected' => 'text-red-500',
                                                    'items_issued' => 'text-blue-500',
                                                    'item_returned' => 'text-purple-500',
                                                ];
                                                $iconColor = $typeIcons[$notification->type] ?? 'text-gray-500';
                                            @endphp
                                            <svg class="w-4 h-4 mr-2 {{ $iconColor }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M18 3a1 1 0 00-1.196-.98l-10 2A1 1 0 006 5v9.114A4.369 4.369 0 005 14c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V7.82l8-1.6v5.894A4.37 4.37 0 0015 12c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V3z"/>
                                            </svg>
                                            <p class="font-medium text-gray-800">{{ $notification->title }}</p>
                                        </div>
                                        <p class="text-sm text-gray-600 mt-1 ml-6">{{ $notification->message }}</p>
                                        <p class="text-xs text-gray-400 mt-2 ml-6">{{ $notification->created_at->diffForHumans() }}</p>
                                    </div>
                                    @if(!$notification->is_read)
                                        <span class="ml-2 inline-block w-2 h-2 bg-blue-500 rounded-full animate-pulse"></span>
                                    @endif
                                </div>
                            </a>
                        @empty
                            <div class="p-8 text-center">
                                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                                <p class="text-gray-400 italic">No notifications</p>
                            </div>
                        @endforelse
                    </div>
                    
                    <div class="p-3 border-t bg-slate-50">
                        <a href="#" class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center justify-center">
                            View All Notifications
                            <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const notificationButton = document.getElementById('notificationButton');
    const notificationDropdown = document.getElementById('notificationDropdown');
    
    if (notificationButton && notificationDropdown) {
        notificationButton.addEventListener('click', (e) => {
            e.stopPropagation();
            notificationDropdown.classList.toggle('hidden');
        });
        
        document.addEventListener('click', (e) => {
            if (!notificationButton.contains(e.target) && !notificationDropdown.contains(e.target)) {
                notificationDropdown.classList.add('hidden');
            }
        });
        
        const markAllReadBtn = document.querySelector('.mark-all-read');
        if (markAllReadBtn) {
            markAllReadBtn.addEventListener('click', function(e) {
                e.preventDefault();
                fetch('{{ route("notifications.mark-all-read") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }).then(response => {
                    if (response.ok) {
                        location.reload();
                    }
                });
            });
        }
    }
});
</script>