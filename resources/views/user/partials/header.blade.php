<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

:root {
    --cream:      #FCF8F3;
    --teal:       #AEDADD;
    --teal-dark:  #7bbfc3;
    --terra:      #DB996C;
    --slate:      #6E7DA2;
    --slate-dark: #5a6a8a;
    --slate-deep: #4a5878;
    --text-dim:   rgba(252,248,243,0.55);
    --text-soft:  rgba(252,248,243,0.80);
    --glass:      rgba(252,248,243,0.08);
    --glass-hover:rgba(174,218,221,0.14);
    --border:     rgba(174,218,221,0.20);
}

.user-header * { box-sizing: border-box; }
.user-header { font-family: 'Plus Jakarta Sans', sans-serif; }

/* ══ HEADER SHELL ═════════════════════════════════════════ */
.user-header {
    background: var(--slate-deep);
    border-bottom: 1px solid rgba(174,218,221,0.18);
    box-shadow: 0 1px 0 rgba(0,0,0,0.12), 0 4px 20px rgba(74,88,120,0.18);
    position: relative;
}

.user-header::before {
    content: '';
    position: absolute;
    left: 0; top: 0; bottom: 0;
    width: 3px;
    background: linear-gradient(to bottom, var(--teal), var(--terra));
    border-radius: 0 2px 2px 0;
}

.user-header-inner {
    padding: 0 2rem;
    height: 75px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
}

/* ══ LEFT: TITLE + BREADCRUMB ═════════════════════════════ */
.header-left { display: flex; flex-direction: column; justify-content: center; gap: 3px; }

.header-title {
    font-size: 1.9rem;
    font-weight: 700;
    color: var(--cream);
    letter-spacing: -0.01em;
    line-height: 1;
}

.breadcrumb {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: .72rem;
    font-weight: 500;
    line-height: 1;
}
.breadcrumb a {
    color: var(--teal);
    text-decoration: none;
    opacity: .85;
    transition: opacity .15s;
}
.breadcrumb a:hover { opacity: 1; text-decoration: underline; text-underline-offset: 2px; }
.breadcrumb-sep { color: var(--text-dim); font-size: .65rem; }
.breadcrumb-current { color: var(--text-dim); }

/* ══ RIGHT SIDE ═══════════════════════════════════════════ */
.header-right { display: flex; align-items: center; gap: .5rem; }

.header-divider {
    width: 1px;
    height: 28px;
    background: var(--border);
    margin: 0 .25rem;
}

/* ══ NOTIFICATION BELL ════════════════════════════════════ */
.notif-btn {
    position: relative;
    width: 40px; height: 40px;
    background: var(--glass);
    border: 1px solid var(--border);
    cursor: pointer;
    border-radius: 10px;
    transition: background .15s, border-color .15s;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-soft);
}
.notif-btn:hover {
    background: var(--glass-hover);
    border-color: rgba(174,218,221,0.40);
    color: var(--cream);
}
.notif-btn svg { transition: color .15s; }

.notif-badge {
    position: absolute;
    top: -5px; right: -5px;
    background: var(--terra);
    color: #fff;
    font-size: .6rem;
    font-weight: 700;
    border-radius: 10px;
    min-width: 20px; height: 18px;
    padding: 0 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 6px rgba(219,153,108,.5);
    border: 2px solid var(--slate-deep);
    font-family: 'Plus Jakarta Sans', sans-serif;
}

/* ══ PROFILE BUTTON ═══════════════════════════════════════ */
.profile-link {
    display: flex;
    align-items: center;
    gap: .65rem;
    padding: .4rem .75rem .4rem .5rem;
    border-radius: 10px;
    text-decoration: none;
    background: var(--glass);
    border: 1px solid var(--border);
    transition: background .15s, border-color .15s;
}
.profile-link:hover {
    background: var(--glass-hover);
    border-color: rgba(174,218,221,0.40);
}

/* ── AVATAR: shared base ── */
.avatar {
    width: 34px; height: 34px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    overflow: hidden;
}

/* initials fallback */
.avatar-initials {
    background: linear-gradient(135deg, var(--teal) 0%, #8ecdd0 100%);
    color: var(--slate-deep);
    font-size: .72rem;
    font-weight: 800;
    letter-spacing: .02em;
    width: 100%; height: 100%;
    display: flex; align-items: center; justify-content: center;
    border-radius: 8px;
}

/* photo */
.avatar-photo {
    width: 100%; height: 100%;
    object-fit: cover;
    border-radius: 8px;
    display: block;
}

.profile-text { display: flex; flex-direction: column; gap: 2px; }
.profile-name {
    font-size: .82rem;
    font-weight: 600;
    color: var(--cream);
    line-height: 1;
    letter-spacing: -.01em;
}
.profile-role {
    font-size: .65rem;
    font-weight: 500;
    color: var(--teal);
    line-height: 1;
    letter-spacing: .06em;
    text-transform: uppercase;
}

/* ══ DROPDOWN ═════════════════════════════════════════════ */
.notif-dropdown {
    display: none;
    position: absolute;
    right: 0;
    top: calc(100% + 10px);
    width: 380px;
    background: #fff;
    border: 1px solid #e8eaf0;
    border-radius: 14px;
    box-shadow: 0 4px 6px rgba(0,0,0,.04), 0 20px 48px rgba(74,88,120,.16);
    z-index: 100;
    overflow: hidden;
}
.notif-dropdown.open { display: block; animation: fadeDown .18s ease; }
@keyframes fadeDown {
    from { opacity: 0; transform: translateY(-8px); }
    to   { opacity: 1; transform: translateY(0); }
}

.notif-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem 1.25rem .85rem;
    border-bottom: 1px solid #f0f1f5;
}
.notif-header-left { display: flex; align-items: center; gap: .4rem; }
.notif-header h3 {
    font-size: .9rem;
    font-weight: 700;
    color: #1e2535;
    letter-spacing: -.01em;
    font-family: 'Plus Jakarta Sans', sans-serif;
}
.notif-count-pill {
    font-size: .65rem;
    font-weight: 700;
    background: rgba(219,153,108,.12);
    color: var(--terra);
    padding: 2px 8px;
    border-radius: 20px;
    border: 1px solid rgba(219,153,108,.25);
    font-family: 'Plus Jakarta Sans', sans-serif;
}

.mark-all-read {
    font-size: .72rem;
    font-weight: 600;
    color: var(--slate);
    background: none;
    border: 1px solid #e0e3ed;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: .3rem;
    padding: .3rem .7rem;
    border-radius: 8px;
    transition: all .15s;
    font-family: 'Plus Jakarta Sans', sans-serif;
}
.mark-all-read:hover {
    background: var(--slate);
    color: #fff;
    border-color: var(--slate);
}

.notif-list { max-height: 340px; overflow-y: auto; }
.notif-list::-webkit-scrollbar { width: 4px; }
.notif-list::-webkit-scrollbar-track { background: #f8f9fb; }
.notif-list::-webkit-scrollbar-thumb { background: #d0d4e0; border-radius: 4px; }

.notif-item {
    display: flex;
    align-items: flex-start;
    gap: .85rem;
    padding: .9rem 1.25rem;
    border-bottom: 1px solid #f4f5f8;
    text-decoration: none;
    transition: background .12s;
    background: #fff;
}
.notif-item:last-child { border-bottom: none; }
.notif-item:hover { background: #f8f9fc; }
.notif-item.unread { background: #fdf9f6; }

.notif-icon {
    width: 36px; height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    margin-top: 1px;
}
.notif-icon.ic-green  { background: rgba(90,158,106,.1);  color: #4a8c4a; }
.notif-icon.ic-red    { background: rgba(219,153,108,.12); color: var(--terra); }
.notif-icon.ic-blue   { background: rgba(174,218,221,.2);  color: var(--teal-dark); }
.notif-icon.ic-purple { background: rgba(155,130,192,.12); color: #8a68b8; }
.notif-icon.ic-gray   { background: #f0f1f5; color: #9aa0b4; }

.notif-item-body { flex: 1; min-width: 0; }
.notif-item-title {
    font-size: .82rem;
    font-weight: 600;
    color: #1e2535;
    margin-bottom: 3px;
    line-height: 1.3;
    font-family: 'Plus Jakarta Sans', sans-serif;
}
.notif-item-msg {
    font-size: .76rem;
    color: #6b7280;
    line-height: 1.45;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    font-family: 'Plus Jakarta Sans', sans-serif;
}
.notif-item-time {
    font-size: .68rem;
    color: #9ca3af;
    margin-top: 4px;
    display: flex;
    align-items: center;
    gap: 4px;
    font-family: 'Plus Jakarta Sans', sans-serif;
}
.notif-item-time::before {
    content: '';
    display: inline-block;
    width: 4px; height: 4px;
    border-radius: 50%;
    background: #d1d5db;
}

.unread-dot {
    width: 7px; height: 7px;
    background: var(--terra);
    border-radius: 50%;
    flex-shrink: 0;
    margin-top: 6px;
}

.notif-empty { padding: 3rem 1.5rem; text-align: center; }
.notif-empty-icon {
    width: 52px; height: 52px;
    background: #f4f5f8;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    color: #c4c8d4;
}
.notif-empty p {
    font-size: .82rem;
    color: #9ca3af;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-weight: 500;
}

.notif-footer {
    padding: .75rem 1.25rem;
    border-top: 1px solid #f0f1f5;
    background: #fafbfc;
    text-align: center;
}
.notif-footer a {
    font-size: .76rem;
    font-weight: 600;
    color: var(--slate);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: .3rem;
    transition: color .15s;
    font-family: 'Plus Jakarta Sans', sans-serif;
}
.notif-footer a:hover { color: var(--slate-deep); }
</style>

<header class="user-header">
    <div class="user-header-inner">

        {{-- Left: title + breadcrumb --}}
        <div class="header-left">
            @php
                $moduleTitles = [
                    'dashboard'            => 'Dashboard',
                    'requests.index'       => 'Request Items',
                    'requests.cart'        => 'My Cart',
                    'requests.my-requests' => 'My Requests',
                    'requests.show'        => 'Request Details',
                    'profile.edit'         => 'My Profile',
                ];
                $currentTitle = $moduleTitles[Route::currentRouteName()] ?? 'Dashboard';
            @endphp

            <h2 class="header-title">{{ $currentTitle }}</h2>

            @php
                $breadcrumbs = [
                    'dashboard'            => [['title'=>'Dashboard','url'=>route('dashboard')]],
                    'requests.index'       => [['title'=>'Dashboard','url'=>route('dashboard')],['title'=>'Request Items','url'=>route('requests.index')]],
                    'requests.cart'        => [['title'=>'Dashboard','url'=>route('dashboard')],['title'=>'Request Items','url'=>route('requests.index')],['title'=>'Cart','url'=>route('requests.cart')]],
                    'requests.my-requests' => [['title'=>'Dashboard','url'=>route('dashboard')],['title'=>'My Requests','url'=>route('requests.my-requests')]],
                    'requests.show'        => [['title'=>'Dashboard','url'=>route('dashboard')],['title'=>'My Requests','url'=>route('requests.my-requests')],['title'=>'Request Details','url'=>'#']],
                    'profile.edit'         => [['title'=>'Dashboard','url'=>route('dashboard')],['title'=>'My Profile','url'=>route('profile.edit')]],
                ];
                $currentBreadcrumbs = $breadcrumbs[Route::currentRouteName()] ?? [];
            @endphp

            @if(count($currentBreadcrumbs) > 0)
                <div class="breadcrumb">
                    @foreach($currentBreadcrumbs as $crumb)
                        @if(!$loop->last)
                            <a href="{{ $crumb['url'] }}">{{ $crumb['title'] }}</a>
                            <span class="breadcrumb-sep">›</span>
                        @else
                            <span class="breadcrumb-current">{{ $crumb['title'] }}</span>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Right: bell + divider + profile --}}
        <div class="header-right">

            {{-- Notification Bell --}}
            <div style="position:relative;">
                <button class="notif-btn" id="notificationButton" aria-label="Notifications">
                    <svg width="18" height="18" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                    </svg>
                    @if(Auth::user()->unreadNotifications()->count() > 0)
                        <span class="notif-badge">{{ Auth::user()->unreadNotifications()->count() }}</span>
                    @endif
                </button>

                <div class="notif-dropdown" id="notificationDropdown">
                    <div class="notif-header">
                        <div class="notif-header-left">
                            <h3>Notifications</h3>
                            @if(Auth::user()->unreadNotifications()->count() > 0)
                                <span class="notif-count-pill">{{ Auth::user()->unreadNotifications()->count() }} new</span>
                            @endif
                        </div>
                        @if(Auth::user()->unreadNotifications()->count() > 0)
                            <button class="mark-all-read">
                                <svg width="11" height="11" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Mark all read
                            </button>
                        @endif
                    </div>

                    <div class="notif-list">
                        @forelse(Auth::user()->notifications()->latest()->take(10)->get() as $notification)
                            @php
                                $iconMap = [
                                    'request_approved' => ['class'=>'ic-green',  'path'=>'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                    'request_rejected' => ['class'=>'ic-red',    'path'=>'M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z'],
                                    'items_issued'     => ['class'=>'ic-blue',   'path'=>'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
                                    'item_returned'    => ['class'=>'ic-purple', 'path'=>'M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6'],
                                ];
                                $icon = $iconMap[$notification->type] ?? ['class'=>'ic-gray','path'=>'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9'];
                            @endphp
                            <a href="#" class="notif-item {{ $notification->is_read ? '' : 'unread' }}">
                                <div class="notif-icon {{ $icon['class'] }}">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon['path'] }}"/>
                                    </svg>
                                </div>
                                <div class="notif-item-body">
                                    <div class="notif-item-title">{{ $notification->title }}</div>
                                    <div class="notif-item-msg">{{ $notification->message }}</div>
                                    <div class="notif-item-time">{{ $notification->created_at->diffForHumans() }}</div>
                                </div>
                                @if(!$notification->is_read)
                                    <span class="unread-dot"></span>
                                @endif
                            </a>
                        @empty
                            <div class="notif-empty">
                                <div class="notif-empty-icon">
                                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                    </svg>
                                </div>
                                <p>You're all caught up!</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="notif-footer">
                        <a href="#">
                            View all notifications
                            <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <div class="header-divider"></div>

            {{-- Profile link with avatar photo or initials fallback --}}
            <a href="{{ route('profile.edit') }}" class="profile-link">
                <div class="avatar">
                    @if(Auth::user()->avatar)
                        <img class="avatar-photo"
                             src="{{ asset('storage/' . Auth::user()->avatar) }}"
                             alt="{{ Auth::user()->first_name }}">
                    @else
                        <div class="avatar-initials">
                            {{ Str::upper(substr(Auth::user()->first_name, 0, 1) . substr(Auth::user()->last_name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div class="profile-text">
                    <span class="profile-name">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span>
                    <span class="profile-role">User</span>
                </div>
            </a>

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