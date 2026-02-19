<style>
/* ── HEADER ── */
.admin-header {
    background: var(--charcoal, #4A4947);
    border-bottom: 3px solid var(--sienna, #B17457);
    box-shadow: 0 2px 12px rgba(74,73,71,.18);
}
.admin-header-inner {
    padding: .9rem 2rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
}

/* title + breadcrumb */
.header-title {
    font-size: 1.3rem;
    font-weight: 700;
    color: #FAF7F0;
    letter-spacing: .02em;
    font-family: 'Georgia', serif;
}
.breadcrumb {
    display: flex;
    align-items: center;
    gap: .3rem;
    margin-top: .3rem;
    font-size: .72rem;
}
.breadcrumb a {
    color: #D8D2C2;
    text-decoration: none;
    transition: color .15s;
}
.breadcrumb a:hover { color: #FAF7F0; }
.breadcrumb-sep { color: #6b6966; }
.breadcrumb-current { color: #9a9591; }

/* right side */
.header-right { display: flex; align-items: center; gap: .5rem; }

/* profile link */
.profile-link {
    display: flex;
    align-items: center;
    gap: .75rem;
    padding: .45rem .9rem;
    border-radius: 8px;
    text-decoration: none;
    transition: background .15s;
}
.profile-link:hover { background: rgba(250,247,240,.08); }
.profile-text { text-align: right; }
.profile-name {
    display: block;
    color: #FAF7F0;
    font-size: .875rem;
    font-weight: 600;
    transition: color .15s;
    font-family: 'Georgia', serif;
}
.profile-link:hover .profile-name { color: #D8D2C2; }
.profile-role {
    display: block;
    color: #9a9591;
    font-size: .7rem;
    text-transform: uppercase;
    letter-spacing: .06em;
}
.avatar {
    width: 38px;
    height: 38px;
    background: linear-gradient(135deg, #B17457, #8a5a40);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #FAF7F0;
    font-size: .8rem;
    font-weight: 700;
    flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(177,116,87,.35);
    font-family: 'Georgia', serif;
}

/* notification bell */
.notif-btn {
    position: relative;
    padding: .5rem;
    background: transparent;
    border: none;
    cursor: pointer;
    border-radius: 50%;
    transition: background .15s;
    display: flex;
    align-items: center;
    justify-content: center;
}
.notif-btn:hover { background: rgba(250,247,240,.08); }
.notif-btn svg { color: #D8D2C2; transition: color .15s; }
.notif-btn:hover svg { color: #FAF7F0; }
.notif-badge {
    position: absolute;
    top: 2px; right: 2px;
    background: #c0392b;
    color: #fff;
    font-size: .65rem;
    font-weight: 700;
    border-radius: 50%;
    width: 18px; height: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 1px 4px rgba(0,0,0,.3);
    animation: pulse 2s infinite;
}
@keyframes pulse {
    0%,100% { opacity: 1; }
    50%      { opacity: .65; }
}

/* notification dropdown */
.notif-dropdown {
    display: none;
    position: absolute;
    right: 0; top: calc(100% + .5rem);
    width: 380px;
    background: #FAF7F0;
    border: 1px solid #D8D2C2;
    border-radius: 10px;
    box-shadow: 0 8px 32px rgba(74,73,71,.18);
    z-index: 50;
    overflow: hidden;
}
.notif-dropdown.open { display: block; }

.notif-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: .85rem 1.1rem;
    border-bottom: 1px solid #D8D2C2;
    background: #f0ece4;
}
.notif-header h3 {
    font-size: .95rem;
    font-weight: 700;
    color: #4A4947;
    font-family: 'Georgia', serif;
}
.mark-all-read {
    font-size: .75rem;
    color: #B17457;
    background: none;
    border: none;
    cursor: pointer;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: .25rem;
    transition: color .15s;
}
.mark-all-read:hover { color: #f3f3f3; }

.notif-list { max-height: 360px; overflow-y: auto; }
.notif-item {
    display: block;
    padding: .85rem 1.1rem;
    border-bottom: 1px solid #ede9e0;
    text-decoration: none;
    transition: background .12s;
}
.notif-item:last-child { border-bottom: none; }
.notif-item:hover { background: #f5f1e8; }
.notif-item.unread { background: #fdf6ee; }
.notif-item-top {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: .5rem;
}
.notif-item-body { flex: 1; }
.notif-item-title {
    display: flex;
    align-items: center;
    gap: .4rem;
    font-size: .825rem;
    font-weight: 600;
    color: #4A4947;
    margin-bottom: .25rem;
}
.notif-item-msg  { font-size: .775rem; color: #6b6966; margin-left: 1.35rem; }
.notif-item-time { font-size: .7rem;   color: #9a9591; margin-left: 1.35rem; margin-top: .2rem; }
.unread-dot {
    width: 8px; height: 8px;
    background: #B17457;
    border-radius: 50%;
    flex-shrink: 0;
    margin-top: .25rem;
    animation: pulse 2s infinite;
}

.notif-empty {
    padding: 2.5rem 1rem;
    text-align: center;
}
.notif-empty svg { color: #D8D2C2; margin: 0 auto .75rem; display: block; }
.notif-empty p   { color: #9a9591; font-size: .85rem; font-style: italic; }

.notif-footer {
    padding: .7rem 1.1rem;
    border-top: 1px solid #D8D2C2;
    background: #f0ece4;
    text-align: center;
}
.notif-footer a {
    font-size: .78rem;
    color: #B17457;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: .25rem;
    transition: color .15s;
}
.notif-footer a:hover { color: #8a5a40; }

/* type icon colors */
.ic-green  { color: #4a8c4a; }
.ic-red    { color: #c0392b; }
.ic-blue   { color: #4a7fb5; }
.ic-purple { color: #7b5fa0; }
.ic-gray   { color: #9a9591; }
</style>

<header class="admin-header">
    <div class="admin-header-inner">

        {{-- Left: title + breadcrumb --}}
        <div>
            @php
                $moduleTitles = [
                    'admin.dashboard'             => 'Admin Dashboard',
                    'admin.orders.index'          => 'Order Management',
                    'admin.orders.pending'        => 'Pending Requests',
                    'admin.orders.approved'       => 'Approved Requests',
                    'admin.orders.rejected'       => 'Rejected Requests',
                    'admin.orders.review'         => 'Review Request',
                    'admin.orders.create-issuance'=> 'Create Issuance',
                    'admin.orders.issuances'      => 'Issuances',
                    'admin.orders.issuances.view' => 'View Issuance',
                    'admin.orders.returns'        => 'Item Returns',
                    'admin.orders.reports'        => 'Reports & Analytics',
                    'admin.inventory.index'       => 'Inventory Management',
                    'admin.users.index'           => 'User Management',
                    'admin.categories.index'      => 'Category Management',
                    'admin.addresses.index'       => 'Address Management',
                    'profile.edit'                => 'My Profile',
                ];
                $currentTitle = $moduleTitles[Route::currentRouteName()] ?? 'Admin Dashboard';
            @endphp

            <h2 class="header-title">{{ $currentTitle }}</h2>

            @php
                $breadcrumbs = [
                    'admin.dashboard'             => [['title'=>'Dashboard','url'=>route('admin.dashboard')]],
                    'admin.orders.index'          => [['title'=>'Dashboard','url'=>route('admin.dashboard')],['title'=>'Order Management','url'=>route('admin.orders.index')]],
                    'admin.orders.pending'        => [['title'=>'Dashboard','url'=>route('admin.dashboard')],['title'=>'Order Management','url'=>route('admin.orders.index')],['title'=>'Pending Requests','url'=>route('admin.orders.pending')]],
                    'admin.orders.approved'       => [['title'=>'Dashboard','url'=>route('admin.dashboard')],['title'=>'Order Management','url'=>route('admin.orders.index')],['title'=>'Approved Requests','url'=>route('admin.orders.approved')]],
                    'admin.orders.rejected'       => [['title'=>'Dashboard','url'=>route('admin.dashboard')],['title'=>'Order Management','url'=>route('admin.orders.index')],['title'=>'Rejected Requests','url'=>route('admin.orders.rejected')]],
                    'admin.orders.review'         => [['title'=>'Dashboard','url'=>route('admin.dashboard')],['title'=>'Order Management','url'=>route('admin.orders.index')],['title'=>'Pending Requests','url'=>route('admin.orders.pending')],['title'=>'Review Request','url'=>'#']],
                    'admin.orders.create-issuance'=> [['title'=>'Dashboard','url'=>route('admin.dashboard')],['title'=>'Order Management','url'=>route('admin.orders.index')],['title'=>'Create Issuance','url'=>'#']],
                    'admin.orders.issuances'      => [['title'=>'Dashboard','url'=>route('admin.dashboard')],['title'=>'Order Management','url'=>route('admin.orders.index')],['title'=>'Issuances','url'=>route('admin.orders.issuances')]],
                    'admin.orders.returns'        => [['title'=>'Dashboard','url'=>route('admin.dashboard')],['title'=>'Order Management','url'=>route('admin.orders.index')],['title'=>'Item Returns','url'=>route('admin.orders.returns')]],
                    'admin.orders.reports'        => [['title'=>'Dashboard','url'=>route('admin.dashboard')],['title'=>'Order Management','url'=>route('admin.orders.index')],['title'=>'Reports','url'=>route('admin.orders.reports')]],
                    'admin.inventory.index'       => [['title'=>'Dashboard','url'=>route('admin.dashboard')],['title'=>'Inventory','url'=>route('admin.inventory.index')]],
                    'admin.users.index'           => [['title'=>'Dashboard','url'=>route('admin.dashboard')],['title'=>'User Management','url'=>route('admin.users.index')]],
                    'admin.categories.index'      => [['title'=>'Dashboard','url'=>route('admin.dashboard')],['title'=>'Category Management','url'=>route('admin.categories.index')]],
                    'admin.addresses.index'       => [['title'=>'Dashboard','url'=>route('admin.dashboard')],['title'=>'Address Management','url'=>route('admin.addresses.index')]],
                ];
                $currentBreadcrumbs = $breadcrumbs[Route::currentRouteName()] ?? [];
            @endphp

            @if(count($currentBreadcrumbs) > 0)
                <div class="breadcrumb">
                    @foreach($currentBreadcrumbs as $crumb)
                        @if(!$loop->last)
                            <a href="{{ $crumb['url'] }}">{{ $crumb['title'] }}</a>
                            <span class="breadcrumb-sep">/</span>
                        @else
                            <span class="breadcrumb-current">{{ $crumb['title'] }}</span>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Right: profile + bell --}}
        <div class="header-right">

            {{-- Profile --}}
            <a href="{{ route('profile.edit') }}" class="profile-link">
                <div class="profile-text">
                    <span class="profile-name">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span>
                    <span class="profile-role">{{ Auth::user()->type }}</span>
                </div>
                <div class="avatar">
                    {{ Str::upper(substr(Auth::user()->first_name,0,1).substr(Auth::user()->last_name,0,1)) }}
                </div>
            </a>

            {{-- Notification Bell --}}
            <div style="position:relative;">
                <button class="notif-btn" id="notificationButton">
                    <svg width="22" height="22" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                    </svg>
                    @if(Auth::user()->unreadNotifications()->count() > 0)
                        <span class="notif-badge">{{ Auth::user()->unreadNotifications()->count() }}</span>
                    @endif
                </button>

                {{-- Dropdown --}}
                <div class="notif-dropdown" id="notificationDropdown">

                    <div class="notif-header">
                        <h3>Notifications</h3>
                        @if(Auth::user()->unreadNotifications()->count() > 0)
                            <button class="mark-all-read">
                                <svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Mark All as Read
                            </button>
                        @endif
                    </div>

                    <div class="notif-list">
                        @forelse(Auth::user()->notifications()->latest()->take(10)->get() as $notification)
                            @php
                                $iconColors = [
                                    'request_approved' => 'ic-green',
                                    'request_rejected' => 'ic-red',
                                    'items_issued'     => 'ic-blue',
                                    'item_returned'    => 'ic-purple',
                                ];
                                $ic = $iconColors[$notification->type] ?? 'ic-gray';
                            @endphp
                            <a href="#" class="notif-item {{ $notification->is_read ? '' : 'unread' }}">
                                <div class="notif-item-top">
                                    <div class="notif-item-body">
                                        <div class="notif-item-title">
                                            <svg class="w-4 h-4 {{ $ic }}" fill="currentColor" viewBox="0 0 20 20" style="width:14px;height:14px;flex-shrink:0;">
                                                <path d="M18 3a1 1 0 00-1.196-.98l-10 2A1 1 0 006 5v9.114A4.369 4.369 0 005 14c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V7.82l8-1.6v5.894A4.37 4.37 0 0015 12c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V3z"/>
                                            </svg>
                                            {{ $notification->title }}
                                        </div>
                                        <p class="notif-item-msg">{{ $notification->message }}</p>
                                        <p class="notif-item-time">{{ $notification->created_at->diffForHumans() }}</p>
                                    </div>
                                    @if(!$notification->is_read)
                                        <span class="unread-dot"></span>
                                    @endif
                                </div>
                            </a>
                        @empty
                            <div class="notif-empty">
                                <svg width="44" height="44" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                                <p>No notifications</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="notif-footer">
                        <a href="#">
                            View All Notifications
                            <svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20">
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
document.addEventListener('DOMContentLoaded', function () {
    const btn      = document.getElementById('notificationButton');
    const dropdown = document.getElementById('notificationDropdown');

    if (btn && dropdown) {
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            dropdown.classList.toggle('open');
        });

        document.addEventListener('click', function (e) {
            if (!btn.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.remove('open');
            }
        });

        const markAll = document.querySelector('.mark-all-read');
        if (markAll) {
            markAll.addEventListener('click', function (e) {
                e.preventDefault();
                fetch('{{ route("notifications.mark-all-read") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }).then(r => { if (r.ok) location.reload(); });
            });
        }
    }
});
</script>