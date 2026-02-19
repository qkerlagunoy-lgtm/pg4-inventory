{{-- resources/views/admin/partials/sidebar.blade.php --}}

<style>
:root {
    --cream:    #FAF7F0;
    --sand:     #D8D2C2;
    --sienna:   #B17457;
    --charcoal: #4A4947;
}

/* ── SIDEBAR ── */
#sidebar {
    background: var(--charcoal);
    color: var(--cream);
    width: 16rem;
    flex-shrink: 0;
    position: sticky;
    top: 0;
    height: 100vh;
    overflow-y: auto;
    transition: transform .3s ease;
    z-index: 40;
    border-right: 3px solid var(--sienna);
    font-family: 'Georgia', serif;
}

/* ── LOGO SECTION ── */
.sidebar-logo {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 1.5rem 1rem;
    border-bottom: 2px solid rgba(216,210,194,.15);
}
.sidebar-logo img {
    width: 6.5rem;
    height: 6.5rem;
    border-radius: 50%;
    margin-bottom: .75rem;
    border: 3px solid var(--sienna);
    box-shadow: 0 4px 12px rgba(177,116,87,.25);
}
.sidebar-logo h2 {
    font-size: .7rem;
    font-weight: 600;
    text-align: center;
    line-height: 1.3;
    letter-spacing: .04em;
    text-transform: uppercase;
    color: var(--sand);
}

/* ── NAV ── */
.sidebar-nav {
    margin-top: 1rem;
    padding: 0 .75rem 1rem .75rem;
}
.sidebar-nav > * { margin-bottom: .25rem; }

.nav-link {
    display: flex;
    align-items: center;
    gap: .75rem;
    padding: .7rem 1rem;
    border-radius: 8px;
    text-decoration: none;
    color: var(--sand);
    font-size: .875rem;
    font-weight: 600;
    transition: background .15s, color .15s;
}
.nav-link:hover { background: rgba(216,210,194,.08); color: var(--cream); }
.nav-link.active { background: var(--sienna); color: #fff; }
.nav-link svg { width: 1.15rem; height: 1.15rem; flex-shrink: 0; }

/* ── ACCORDION ── */
.accordion-btn {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: .7rem 1rem;
    border-radius: 8px;
    background: none;
    border: none;
    cursor: pointer;
    color: var(--sand);
    font-size: .875rem;
    font-weight: 600;
    transition: background .15s, color .15s;
    font-family: inherit;
}
.accordion-btn:hover { background: rgba(216,210,194,.08); color: var(--cream); }
.accordion-btn.active { background: rgba(177,116,87,.22); color: var(--cream); }
.accordion-left { display: flex; align-items: center; gap: .75rem; }
.accordion-left svg { width: 1.15rem; height: 1.15rem; }
.accordion-chevron {
    width: 1rem; height: 1rem;
    transition: transform .2s;
    flex-shrink: 0;
}
.accordion-chevron.rotate { transform: rotate(180deg); }

.accordion-content {
    overflow: hidden;
    transition: max-height .3s ease;
    max-height: 0;
}
.accordion-content.open { max-height: 28rem; }

.accordion-submenu {
    margin-left: 2.2rem;
    border-left: 2px solid rgba(216,210,194,.15);
    padding-left: .75rem;
    padding-top: .25rem;
    padding-bottom: .25rem;
}
.accordion-submenu a {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: .6rem .75rem;
    font-size: .8rem;
    border-radius: 6px;
    text-decoration: none;
    color: var(--sand);
    transition: background .12s, color .12s;
    margin-bottom: .15rem;
}
.accordion-submenu a:hover { background: rgba(216,210,194,.08); color: var(--cream); }
.accordion-submenu a.active { background: rgba(177,116,87,.3); color: #fff; }
.pending-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: .15rem .5rem;
    font-size: .65rem;
    font-weight: 700;
    background: #e6a23c;
    color: #fff;
    border-radius: 20px;
    box-shadow: 0 1px 4px rgba(0,0,0,.2);
}

/* ── DIVIDER ── */
.nav-divider {
    border: none;
    border-top: 1px solid rgba(216,210,194,.15);
    margin: .75rem 0;
}

/* ── LOGOUT ── */
.logout-btn {
    width: 100%;
    display: flex;
    align-items: center;
    gap: .75rem;
    padding: .7rem 1rem;
    background: none;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-family: inherit;
    font-size: .875rem;
    font-weight: 600;
    color: #e8a89a;
    transition: background .15s, color .15s, transform .1s;
    text-align: left;
}
.logout-btn:hover {
    background: rgba(192,57,43,.15);
    color: #f5c7bd;
    transform: translateY(-1px);
}
.logout-btn svg { width: 1.15rem; height: 1.15rem; flex-shrink: 0; }

/* ── MOBILE TOGGLE ── */
.mobile-toggle {
    display: none;
    position: fixed;
    top: 1rem; left: 1rem;
    z-index: 50;
    padding: .5rem;
    background: var(--charcoal);
    color: var(--cream);
    border: 2px solid var(--sienna);
    border-radius: 8px;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(74,73,71,.25);
}
.mobile-toggle svg { width: 1.4rem; height: 1.4rem; }

.mobile-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.5);
    z-index: 35;
}

/* ── LOGOUT MODAL ── */
.logout-modal {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.5);
    z-index: 60;
    align-items: center;
    justify-content: center;
    padding: 1rem;
    animation: fadeIn .2s ease;
}
.logout-modal.show { display: flex; }
.logout-modal-card {
    background: var(--cream);
    border-radius: 12px;
    box-shadow: 0 12px 48px rgba(74,73,71,.3);
    max-width: 26rem;
    width: 100%;
    padding: 1.5rem;
}
.logout-modal-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 4rem; height: 4rem;
    margin: 0 auto 1rem;
    border-radius: 50%;
    background: #ffe6e6;
}
.logout-modal-icon svg { width: 2rem; height: 2rem; color: #c0392b; }
.logout-modal-title {
    font-size: 1.15rem;
    font-weight: 700;
    color: var(--charcoal);
    text-align: center;
    margin-bottom: .5rem;
}
.logout-modal-msg {
    font-size: .875rem;
    color: #6b6966;
    text-align: center;
    margin-bottom: 1.5rem;
    line-height: 1.4;
}
.logout-modal-btns {
    display: flex;
    gap: .75rem;
}
.modal-btn {
    flex: 1;
    padding: .75rem 1rem;
    border: none;
    border-radius: 8px;
    font-family: inherit;
    font-size: .875rem;
    font-weight: 600;
    cursor: pointer;
    transition: background .15s, transform .1s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: .4rem;
}
.modal-btn-cancel {
    background: var(--sand);
    color: var(--charcoal);
}
.modal-btn-cancel:hover { background: #c5bfaf; }
.modal-btn-confirm {
    background: #c0392b;
    color: #fff;
}
.modal-btn-confirm:hover { background: #a02f23; transform: translateY(-1px); }
.modal-btn svg { width: 1.1rem; height: 1.1rem; }
.modal-btn:disabled {
    opacity: .6;
    cursor: not-allowed;
    transform: none;
}

/* ── SCROLLBAR ── */
#sidebar::-webkit-scrollbar { width: 4px; }
#sidebar::-webkit-scrollbar-track { background: rgba(0,0,0,.15); }
#sidebar::-webkit-scrollbar-thumb { background: rgba(216,210,194,.3); border-radius: 2px; }
#sidebar::-webkit-scrollbar-thumb:hover { background: rgba(216,210,194,.5); }

/* ── MOBILE ── */
@media (max-width: 768px) {
    .mobile-toggle { display: block; }
    .mobile-overlay.show { display: block; }
    #sidebar {
        position: fixed;
        top: 0; left: 0;
        height: 100vh;
        z-index: 50;
        transform: translateX(-100%);
    }
    #sidebar.open { transform: translateX(0); }
}

@keyframes fadeIn { from{opacity:0} to{opacity:1} }
@keyframes spin { to { transform: rotate(360deg); } }
</style>

<aside id="sidebar">

    {{-- Logo / Header --}}
    <div class="sidebar-logo">
        <img src="{{ asset('images/logo.png') }}" alt="AFPPGMC Logo">
        <h2>
            The Armed Forces of the Philippines<br>
            Pension and Gratuity<br>
            Management Center
        </h2>
    </div>

    {{-- Navigation --}}
    <nav class="sidebar-nav">

        {{-- Dashboard --}}
        <a href="{{ route('admin.dashboard') }}"
           class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <svg fill="currentColor" viewBox="0 0 20 20">
                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
            </svg>
            <span>Dashboard</span>
        </a>

        {{-- Order Management Accordion --}}
        <div>
            <button type="button" id="ordersAccordionHeader"
                    class="accordion-btn {{ request()->is('admin/orders*') ? 'active' : '' }}">
                <div class="accordion-left">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5z" clip-rule="evenodd"/>
                    </svg>
                    <span>Ordered Items</span>
                </div>
                <svg id="ordersChevron"
                     class="accordion-chevron {{ request()->is('admin/orders*') ? 'rotate' : '' }}"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div id="ordersAccordionContent"
                 class="accordion-content {{ request()->is('admin/orders*') ? 'open' : '' }}">
                <div class="accordion-submenu">
                    <a href="{{ route('admin.orders.index') }}"
                       class="{{ request()->routeIs('admin.orders.index') ? 'active' : '' }}">
                        Dashboard
                    </a>

                    @php
                        $pendingCount = \App\Models\ItemRequest::where('status', 'pending')->count();
                    @endphp

                    <a href="{{ route('admin.orders.pending') }}"
                       class="{{ request()->routeIs('admin.orders.pending') ? 'active' : '' }}">
                        <span>Pending Requests</span>
                        @if($pendingCount > 0)
                            <span class="pending-badge">{{ $pendingCount }}</span>
                        @endif
                    </a>

                    <a href="{{ route('admin.orders.approved') }}"
                       class="{{ request()->routeIs('admin.orders.approved') ? 'active' : '' }}">
                        Approved Requests
                    </a>

                    <a href="{{ route('admin.orders.rejected') }}"
                       class="{{ request()->routeIs('admin.orders.rejected') ? 'active' : '' }}">
                        Rejected Requests
                    </a>

                    <a href="{{ route('admin.orders.issuances') }}"
                       class="{{ request()->routeIs('admin.orders.issuances') ? 'active' : '' }}">
                        Issuances
                    </a>

                    <a href="{{ route('admin.orders.returns') }}"
                       class="{{ request()->routeIs('admin.orders.returns') ? 'active' : '' }}">
                        Returns
                    </a>

                    <a href="{{ route('admin.orders.reports') }}"
                       class="{{ request()->routeIs('admin.orders.reports') ? 'active' : '' }}">
                        Reports
                    </a>
                </div>
            </div>
        </div>

        <hr class="nav-divider">

        {{-- Inventory --}}
        <a href="{{ route('admin.inventory.index') }}"
           class="nav-link {{ request()->is('admin/inventory*') ? 'active' : '' }}">
            <svg fill="currentColor" viewBox="0 0 20 20">
                <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"/>
                <path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd"/>
            </svg>
            <span>Inventory</span>
        </a>

        {{-- Users --}}
        <a href="{{ route('admin.users.index') }}"
           class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}">
            <svg fill="currentColor" viewBox="0 0 20 20">
                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
            </svg>
            <span>Users</span>
        </a>

        {{-- Categories --}}
        <a href="{{ route('admin.categories.index') }}"
           class="nav-link {{ request()->is('admin/categories*') ? 'active' : '' }}">
            <svg fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
            </svg>
            <span>Categories</span>
        </a>

        {{-- Address Management --}}
       <a href="{{ route('admin.addresses.index') }}"
   class="nav-link {{ request()->is('admin/addresses*') ? 'active' : '' }}">
    <svg fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" 
        d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1zM12 2a1 1 0 01.967.744L14.146 7.2 17.5 9.134a1 1 0 010 1.732l-3.354 1.935-1.18 4.455a1 1 0 01-1.933 0L9.854 12.8 6.5 10.866a1 1 0 010-1.732l3.354-1.935 1.18-4.455A1 1 0 0112 2z" 
        clip-rule="evenodd"/>
    </svg>
    <span>Address Management</span>
</a>

        <hr class="nav-divider">

        {{-- Logout --}}
        <form method="POST" action="{{ route('logout') }}" id="logoutForm">
            @csrf
            <button type="button" onclick="confirmLogout()" class="logout-btn">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span>Logout</span>
            </button>
        </form>
    </nav>
</aside>

{{-- Mobile Toggle Button --}}
<button id="sidebarToggle" class="mobile-toggle">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
    </svg>
</button>

{{-- Overlay for mobile --}}
<div id="sidebarOverlay" class="mobile-overlay"></div>

{{-- Logout Confirmation Modal --}}
<div id="logoutModal" class="logout-modal">
    <div class="logout-modal-card">
        <div class="logout-modal-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
        </div>
        <h3 class="logout-modal-title">Confirm Logout</h3>
        <p class="logout-modal-msg">Are you sure you want to logout from the admin panel?</p>
        <div class="logout-modal-btns">
            <button type="button" onclick="hideLogoutModal()" class="modal-btn modal-btn-cancel">Cancel</button>
            <button type="button" onclick="performLogout()" class="modal-btn modal-btn-confirm">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Logout
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const ordersBtn = document.getElementById('ordersAccordionHeader');
    const ordersContent = document.getElementById('ordersAccordionContent');
    const ordersChevron = document.getElementById('ordersChevron');

    // Mobile sidebar toggle
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('open');
            sidebarOverlay.classList.toggle('show');
        });
        sidebarOverlay.addEventListener('click', function() {
            sidebar.classList.remove('open');
            sidebarOverlay.classList.remove('show');
        });
    }

    // Orders accordion toggle
    if (ordersBtn && ordersContent && ordersChevron) {
        ordersBtn.addEventListener('click', function(e) {
            e.preventDefault();
            ordersContent.classList.toggle('open');
            ordersChevron.classList.toggle('rotate');
        });
    }

    // Close sidebar on link click (mobile)
    document.querySelectorAll('#sidebar a').forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth < 768) {
                sidebar.classList.remove('open');
                sidebarOverlay.classList.remove('show');
            }
        });
    });

    // Close accordion when clicking outside (mobile)
    document.addEventListener('click', function(e) {
        if (window.innerWidth < 768 && 
            !sidebar.contains(e.target) && 
            !sidebarToggle.contains(e.target)) {
            sidebar.classList.remove('open');
            sidebarOverlay.classList.remove('show');
        }
    });
});

// Logout Functions
function confirmLogout() {
    const modal = document.getElementById('logoutModal');
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
}

function hideLogoutModal() {
    const modal = document.getElementById('logoutModal');
    modal.classList.remove('show');
    document.body.style.overflow = 'auto';
}

function performLogout() {
    const form = document.getElementById('logoutForm');
    if (form) {
        const btn = document.querySelector('.modal-btn-confirm');
        btn.innerHTML = '<div style="width:1.2rem;height:1.2rem;border:2px solid #fff;border-top-color:transparent;border-radius:50%;animation:spin .6s linear infinite;"></div>';
        btn.disabled = true;
        setTimeout(() => form.submit(), 500);
    }
}

// Close logout modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') hideLogoutModal();
});

// Close logout modal when clicking outside
document.getElementById('logoutModal')?.addEventListener('click', function(e) {
    if (e.target === this) hideLogoutModal();
});
</script>