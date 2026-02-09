<header class="bg-slate-800 shadow-lg">
    <div class="px-8 py-4 flex items-center justify-between">
        <div>
            <h2 class="font-bold text-2xl text-white">Admin Dashboard</h2>
            <p class="text-sm text-gray-400 mt-1">AFPPGMC Logistics Division</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 hover:bg-slate-700 px-4 py-2 rounded-lg transition">
                <div class="text-right">
                    <span class="text-white font-medium block">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span>
                    <span class="text-gray-400 text-sm uppercase">{{ Auth::user()->type }}</span>
                </div>
                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold">
                    {{ Str::upper(substr(Auth::user()->first_name, 0, 1) . substr(Auth::user()->last_name, 0, 1)) }}
                </div>
            </a>
            
            <!-- Notification Bell -->
            <div class="relative">
                <button id="notificationButton" class="p-2 hover:bg-slate-700 rounded-full relative">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                    </svg>
                    @if(Auth::user()->unreadNotifications()->count() > 0)
                        <span class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold">
                            {{ Auth::user()->unreadNotifications()->count() }}
                        </span>
                    @endif
                </button>

                <!-- Notification Dropdown -->
                <div id="notificationDropdown" class="hidden absolute right-0 mt-2 w-96 bg-white rounded-lg shadow-2xl z-50 border border-gray-200">
                    <div class="flex items-center justify-between p-4 border-b">
                        <h3 class="text-lg font-bold text-gray-800">Notifications</h3>
                        @if(Auth::user()->unreadNotifications()->count() > 0)
                            <button class="text-sm text-blue-500 hover:text-blue-700 font-medium mark-all-read">
                                Mark All as Read
                            </button>
                        @endif
                    </div>
                    
                    <div class="max-h-96 overflow-y-auto">
                        @forelse(Auth::user()->notifications()->latest()->take(10)->get() as $notification)
                            <div class="p-4 border-b hover:bg-gray-50 {{ $notification->is_read ? 'bg-gray-50' : 'bg-blue-50' }}">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-800">{{ $notification->title }}</p>
                                        <p class="text-sm text-gray-600 mt-1">{{ $notification->message }}</p>
                                        <p class="text-xs text-gray-400 mt-2">{{ $notification->created_at->diffForHumans() }}</p>
                                    </div>
                                    @if(!$notification->is_read)
                                        <span class="ml-2 inline-block w-2 h-2 bg-blue-500 rounded-full"></span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="p-6 text-center">
                                <p class="text-gray-400 italic">No notifications</p>
                            </div>
                        @endforelse
                    </div>
                    
                    <div class="p-4 border-t">
                        <a href="#" class="text-sm text-blue-500 hover:text-blue-700 font-medium block text-center">
                            View All Notifications
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Notification Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const notificationButton = document.getElementById('notificationButton');
        const notificationDropdown = document.getElementById('notificationDropdown');
        
        if (notificationButton && notificationDropdown) {
            // Toggle dropdown
            notificationButton.addEventListener('click', (e) => {
                e.stopPropagation();
                notificationDropdown.classList.toggle('hidden');
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', (e) => {
                if (!notificationButton.contains(e.target) && !notificationDropdown.contains(e.target)) {
                    notificationDropdown.classList.add('hidden');
                }
            });
            
            // Mark all as read
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