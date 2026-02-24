{{-- resources/views/user/partials/sidebar.blade.php --}}

<style>
:root {
    --cream:       #FCF8F3;
    --sand:        #AEDADD;
    --blue-accent: #DB996C;
    --dark-blue:   #4a5878;
    --navy:        #3d4d68;
}

/* ── SIDEBAR ── */
#sidebar {
    background: var(--dark-blue);
    color: var(--cream);
    width: 16rem;
    flex-shrink: 0;
    position: sticky;
    top: 0;
    height: 100vh;
    overflow-y: auto;
    transition: transform .3s ease;
    z-index: 40;
    border-right: 3px solid var(--blue-accent);
    font-family: 'Georgia', serif;
}

/* ── LOGO SECTION ── */
.sidebar-logo {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 1.5rem 1rem;
    border-bottom: 2px solid rgba(174,218,221,.15);
}
.sidebar-logo img {
    width: 6.5rem;
    height: 6.5rem;
    border-radius: 50%;
    margin-bottom: .75rem;
    border: 3px solid var(--blue-accent);
    box-shadow: 0 4px 12px rgba(219,153,108,.25);
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
.nav-link:hover { background: rgba(174,218,221,.08); color: var(--cream); }
.nav-link.active { background: var(--blue-accent); color: #fff; }
.nav-link svg { width: 1.15rem; height: 1.15rem; flex-shrink: 0; }

/* ── ACCORDION (for future use if needed) ── */
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
.accordion-btn:hover { background: rgba(174,218,221,.08); color: var(--cream); }
.accordion-btn.active { background: rgba(219,153,108,.22); color: var(--cream); }
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
    border-left: 2px solid rgba(174,218,221,.15);
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
.accordion-submenu a:hover { background: rgba(174,218,221,.08); color: var(--cream); }
.accordion-submenu a.active { background: rgba(219,153,108,.3); color: #fff; }

/* Badge for cart count */
.cart-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: .15rem .5rem;
    font-size: .65rem;
    font-weight: 700;
    background: var(--blue-accent);
    color: #fff;
    border-radius: 20px;
    box-shadow: 0 1px 4px rgba(0,0,0,.2);
}

/* Badge for request status */
.status-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: .15rem .5rem;
    font-size: .65rem;
    font-weight: 700;
    background: #6E7DA2;
    color: #fff;
    border-radius: 20px;
    box-shadow: 0 1px 4px rgba(0,0,0,.2);
}

/* ── DIVIDER ── */
.nav-divider {
    border: none;
    border-top: 1px solid rgba(174,218,221,.15);
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
    color: rgba(174,218,221,.7);
    transition: background .15s, color .15s, transform .1s;
    text-align: left;
}
.logout-btn:hover {
    background: rgba(219,153,108,.15);
    color: #FCF8F3;
    transform: translateY(-1px);
}
.logout-btn svg { width: 1.15rem; height: 1.15rem; flex-shrink: 0; }

/* ── USER INFO FOOTER ── */
.user-footer {
    margin-top: auto;
    padding: 1rem .75rem;
    border-top: 1px solid rgba(174,218,221,.15);
}
.user-info {
    display: flex;
    align-items: center;
    gap: .75rem;
    padding: .5rem .5rem .5rem .25rem;
    border-radius: 8px;
    margin-bottom: .5rem;
}
.user-avatar {
    width: 2.5rem;
    height: 2.5rem;
    background: linear-gradient(135deg, #AEDADD, #7bbfc3);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #4a5878;
    font-size: .9rem;
    font-weight: 700;
    flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(174,218,221,.35);
    font-family: 'Georgia', serif;
}
.user-details {
    flex: 1;
    min-width: 0;
}
.user-name {
    font-size: .8rem;
    font-weight: 600;
    color: var(--cream);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.user-email {
    font-size: .65rem;
    color: var(--sand);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-top: .1rem;
}

/* ── MOBILE TOGGLE ── */
.mobile-toggle {
    display: none;
    position: fixed;
    top: 1rem; left: 1rem;
    z-index: 50;
    padding: .5rem;
    background: var(--dark-blue);
    color: var(--cream);
    border: 2px solid var(--blue-accent);
    border-radius: 8px;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(74,88,120,.25);
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
    box-shadow: 0 12px 48px rgba(74,88,120,.3);
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
    background: rgba(219,153,108,.15);
}
.logout-modal-icon svg { width: 2rem; height: 2rem; color: #DB996C; }
.logout-modal-title {
    font-size: 1.15rem;
    font-weight: 700;
    color: var(--dark-blue);
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
    background: #AEDADD;
    color: #4a5878;
}
.modal-btn-cancel:hover { background: #8ecdd0; }
.modal-btn-confirm {
    background: #DB996C;
    color: #fff;
}
.modal-btn-confirm:hover { background: #c8844f; transform: translateY(-1px); }
.modal-btn svg { width: 1.1rem; height: 1.1rem; }
.modal-btn:disabled {
    opacity: .6;
    cursor: not-allowed;
    transform: none;
}

/* ── SCROLLBAR ── */
#sidebar::-webkit-scrollbar { width: 4px; }
#sidebar::-webkit-scrollbar-track { background: rgba(0,0,0,.15); }
#sidebar::-webkit-scrollbar-thumb { background: rgba(174,218,221,.3); border-radius: 2px; }
#sidebar::-webkit-scrollbar-thumb:hover { background: rgba(174,218,221,.5); }

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
        <a href="{{ route('dashboard') }}"
           class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <svg fill="currentColor" viewBox="0 0 20 20">
                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
            </svg>
            <span>Dashboard</span>
        </a>

        {{-- Request Items --}}
        <a href="{{ route('requests.index') }}"
           class="nav-link {{ request()->routeIs('requests.index') ? 'active' : '' }}">
            <svg fill="currentColor" viewBox="0 0 20 20">
                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
            </svg>
            <span>Request Items</span>
        </a>

        {{-- Cart with badge --}}
        @php
            $cartItems = session('cart', []);
            $cartCount = count($cartItems);
            $cartTotal = array_sum(array_column($cartItems, 'quantity'));
        @endphp
        <a href="{{ route('requests.cart') }}"
           class="nav-link {{ request()->routeIs('requests.cart') ? 'active' : '' }}">
            <div style="display: flex; align-items: center; gap: .75rem; flex:1;">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                </svg>
                <span>Cart</span>
            </div>
            @if($cartCount > 0)
                <span class="cart-badge">{{ $cartTotal }}</span>
            @endif
        </a>

        {{-- My Requests --}}
        <a href="{{ route('requests.my-requests') }}"
           class="nav-link {{ request()->routeIs('requests.my-requests') ? 'active' : '' }}">
            <svg fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
            </svg>
            <span>My Requests</span>
        </a>

        
    </nav>

    {{-- User Info Footer --}}
    <div class="user-footer">
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
    </div>
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
        <p class="logout-modal-msg">Are you sure you want to logout from your account?</p>
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

    // Close sidebar on link click (mobile)
    document.querySelectorAll('#sidebar a').forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth < 768) {
                sidebar.classList.remove('open');
                sidebarOverlay.classList.remove('show');
            }
        });
    });

    // Close sidebar when clicking outside (mobile)
    document.addEventListener('click', function(e) {
        if (window.innerWidth < 768 && 
            !sidebar.contains(e.target) && 
            !sidebarToggle.contains(e.target)) {
            sidebar.classList.remove('open');
            sidebarOverlay.classList.remove('show');
        }
    });

    // Auto-update cart badge count (if using AJAX)
    @if($cartCount > 0)
        setInterval(function() {
            fetch('/cart/count')
                .then(response => response.json())
                .then(data => {
                    const badge = document.querySelector('.cart-badge');
                    if (badge && data.count > 0) {
                        badge.textContent = data.count;
                    } else if (badge && data.count === 0) {
                        badge.remove();
                    }
                })
                .catch(() => {});
        }, 10000); // Update every 10 seconds
    @endif
});

// Logout Functions (identical to admin)
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