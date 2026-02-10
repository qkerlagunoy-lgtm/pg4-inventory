{{-- resources/views/admin/partials/sidebar.blade.php --}}
{{-- resources/views/admin/partials/sidebar.blade.php --}}
<aside id="sidebar"
    class="bg-slate-900 text-white w-64 flex-shrink-0 sticky top-0 overflow-y-auto transition-all duration-300 ease-in-out h-screen">

    <!-- Logo / Header -->
    <div class="flex flex-col items-center py-6 border-b border-slate-700">
        <img src="{{ asset('images/logo.png') }}"
             alt="AFPPGMC Logo"
             class="h-24 w-24 rounded-full mb-3">

        <h2 class="text-xs font-semibold text-center leading-snug uppercase">
            The Armed Forces of the Philippines<br>
            Pension and Gratuity<br>
            Management Center
        </h2>
    </div>

    <!-- Navigation -->
    <nav class="mt-4 space-y-1">

        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center gap-3 px-6 py-3
           {{ request()->routeIs('admin.dashboard') ? 'bg-slate-800 text-white' : 'text-gray-300 hover:bg-slate-800' }}">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
            </svg>
            <span class="font-medium">Dashboard</span>
        </a>

        <div class="border-t border-slate-700 my-2"></div>

        <!-- Order Management Accordion (UNCHANGED DESIGN) -->
        <div class="space-y-1">
            <button type="button"
                    id="ordersAccordionHeader"
                    class="w-full flex items-center justify-between px-4 py-3 rounded-lg
                    {{ request()->is('admin/orders*') ? 'bg-slate-700 text-white' : 'text-gray-300 hover:bg-slate-700' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5z" clip-rule="evenodd"/>
                    </svg>
                    <span class="font-medium">Ordered Items</span>
                </div>

                <svg id="ordersChevron"
                     class="w-4 h-4 transition-transform duration-200
                     {{ request()->is('admin/orders*') ? 'rotate-180' : '' }}"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <!-- Accordion Content -->
            <div id="ordersAccordionContent"
                 class="overflow-hidden transition-all duration-300
                 {{ request()->is('admin/orders*') ? 'max-h-96' : 'max-h-0' }}">

                <div class="ml-10 space-y-1 border-l border-slate-700 pl-3 py-1">
                    <a href="{{ route('admin.orders.index') }}"
                       class="block px-3 py-2.5 text-sm rounded
                       {{ request()->routeIs('admin.orders.index') ? 'text-white bg-slate-600' : 'text-gray-300 hover:bg-slate-700' }}">
                        Dashboard
                    </a>

                    @php
                        $pendingCount = \App\Models\ItemRequest::where('status', 'pending')->count();
                    @endphp

                    <a href="{{ route('admin.orders.pending') }}"
                       class="flex items-center justify-between px-3 py-2.5 text-sm rounded
                       {{ request()->routeIs('admin.orders.pending') ? 'text-white bg-slate-600' : 'text-gray-300 hover:bg-slate-700' }}">
                        <span>Pending Requests</span>
                        @if($pendingCount > 0)
                            <span class="px-2 py-0.5 text-xs font-bold bg-yellow-500 text-white rounded-full">
                                {{ $pendingCount }}
                            </span>
                        @endif
                    </a>

                    <a href="{{ route('admin.orders.approved') }}"
                       class="block px-3 py-2.5 text-sm rounded
                       {{ request()->routeIs('admin.orders.approved') ? 'text-white bg-slate-600' : 'text-gray-300 hover:bg-slate-700' }}">
                        Approved Requests
                    </a>

                    <a href="{{ route('admin.orders.rejected') }}"
                       class="block px-3 py-2.5 text-sm rounded
                       {{ request()->routeIs('admin.orders.rejected') ? 'text-white bg-slate-600' : 'text-gray-300 hover:bg-slate-700' }}">
                        Rejected Requests
                    </a>

                    <a href="{{ route('admin.orders.issuances') }}"
                       class="block px-3 py-2.5 text-sm rounded
                       {{ request()->routeIs('admin.orders.issuances') ? 'text-white bg-slate-600' : 'text-gray-300 hover:bg-slate-700' }}">
                        Issuances
                    </a>

                    <a href="{{ route('admin.orders.returns') }}"
                       class="block px-3 py-2.5 text-sm rounded
                       {{ request()->routeIs('admin.orders.returns') ? 'text-white bg-slate-600' : 'text-gray-300 hover:bg-slate-700' }}">
                        Returns
                    </a>

                    <a href="{{ route('admin.orders.reports') }}"
                       class="block px-3 py-2.5 text-sm rounded
                       {{ request()->routeIs('admin.orders.reports') ? 'text-white bg-slate-600' : 'text-gray-300 hover:bg-slate-700' }}">
                        Reports
                    </a>
                </div>
            </div>
        </div>

        <div class="border-t border-slate-700 my-2"></div>

        <!-- Other Links (unchanged) -->
        <a href="{{ route('admin.inventory') }}" class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-slate-700">Inventory</a>
        <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-slate-700">Users</a>
        <a href="{{ route('admin.categories') }}" class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-slate-700">Categories</a>
        <a href="{{ route('admin.units') }}" class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-slate-700">Units</a>
        <a href="{{ route('admin.addresses') }}" class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:bg-slate-700">Addresses</a>

        <div class="border-t border-slate-700 my-4"></div>

        <!-- Logout -->
        <form method="POST" action="{{ route('logout') }}" id="logoutForm">
            @csrf
            <button type="button"
                    onclick="confirmLogout()"
                    class="w-full flex items-center gap-3 px-4 py-3 text-red-300 hover:text-white hover:bg-red-500/20 rounded-lg">
                Logout
            </button>
        </form>
    </nav>
</aside>

<!-- Mobile Toggle Button -->
<button id="sidebarToggle" 
        class="md:hidden fixed top-4 left-4 z-50 p-2 bg-slate-800 text-white rounded-lg shadow-lg">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
    </svg>
</button>

<!-- Overlay for mobile -->
<div id="sidebarOverlay" class="md:hidden fixed inset-0 bg-black bg-opacity-50 z-40 hidden"></div>

<!-- Logout Confirmation Modal -->
<div id="logoutModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6">
        <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 rounded-full bg-red-100">
            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
        </div>
        
        <h3 class="text-xl font-bold text-gray-900 text-center mb-2">Confirm Logout</h3>
        <p class="text-gray-600 text-center mb-6">
            Are you sure you want to logout from the admin panel?
        </p>
        
        <div class="flex space-x-3">
            <button type="button" 
                    onclick="hideLogoutModal()"
                    class="flex-1 px-4 py-3 bg-gray-200 text-gray-800 font-medium rounded-lg hover:bg-gray-300 transition">
                Cancel
            </button>
            <button type="button" 
                    onclick="performLogout()"
                    class="flex-1 px-4 py-3 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Logout
            </button>
        </div>
    </div>
</div>

<!-- Accordion JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const ordersAccordionHeader = document.getElementById('ordersAccordionHeader');
    const ordersAccordionContent = document.getElementById('ordersAccordionContent');
    const ordersChevron = document.getElementById('ordersChevron');

    // Mobile sidebar toggle
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('-translate-x-full');
            sidebarOverlay.classList.toggle('hidden');
        });

        sidebarOverlay.addEventListener('click', function() {
            sidebar.classList.add('-translate-x-full');
            sidebarOverlay.classList.add('hidden');
        });
    }

    // Orders accordion toggle
    if (ordersAccordionHeader && ordersAccordionContent && ordersChevron) {
        ordersAccordionHeader.addEventListener('click', function(e) {
            e.preventDefault();
            
            const isExpanded = ordersAccordionContent.classList.contains('max-h-96');
            
            // Toggle content height
            if (isExpanded) {
                ordersAccordionContent.classList.remove('max-h-96');
                ordersAccordionContent.classList.add('max-h-0');
            } else {
                ordersAccordionContent.classList.remove('max-h-0');
                ordersAccordionContent.classList.add('max-h-96');
            }
            
            // Rotate chevron
            ordersChevron.classList.toggle('rotate-180');
            
            // If collapsing on mobile, also close the sidebar
            if (window.innerWidth < 768 && !isExpanded) {
                sidebar.classList.remove('-translate-x-full');
                sidebarOverlay.classList.remove('hidden');
            }
        });

        // Auto-expand if on orders page (when page loads)
        if (window.location.pathname.includes('/admin/orders')) {
            ordersAccordionContent.classList.remove('max-h-0');
            ordersAccordionContent.classList.add('max-h-96');
            ordersChevron.classList.add('rotate-180');
        }
    }

    // Close sidebar on link click (mobile)
    document.querySelectorAll('#sidebar a').forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth < 768) {
                sidebar.classList.add('-translate-x-full');
                sidebarOverlay.classList.add('hidden');
            }
        });
    });

    // Close accordion when clicking outside (mobile)
    document.addEventListener('click', function(e) {
        if (window.innerWidth < 768 && 
            !sidebar.contains(e.target) && 
            !sidebarToggle.contains(e.target)) {
            sidebar.classList.add('-translate-x-full');
            sidebarOverlay.classList.add('hidden');
        }
    });
});

// Logout Functions
function confirmLogout() {
    const modal = document.getElementById('logoutModal');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function hideLogoutModal() {
    const modal = document.getElementById('logoutModal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function performLogout() {
    const form = document.getElementById('logoutForm');
    if (form) {
        // Show loading state
        const logoutBtn = document.querySelector('#logoutModal button[onclick="performLogout()"]');
        const originalText = logoutBtn.innerHTML;
        logoutBtn.innerHTML = '<div class="animate-spin rounded-full h-5 w-5 border-b-2 border-white"></div>';
        logoutBtn.disabled = true;
        
        // Submit form after short delay for visual feedback
        setTimeout(() => {
            form.submit();
        }, 500);
    }
}

// Close logout modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        hideLogoutModal();
    }
});

// Close logout modal when clicking outside
document.getElementById('logoutModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        hideLogoutModal();
    }
});

// Update pending count periodically
function updatePendingCount() {
    fetch('{{ route("admin.orders.pending") }}?count_only=true')
        .then(response => response.json())
        .then(data => {
            const countElement = document.getElementById('pendingCount');
            if (countElement && data.count !== undefined) {
                if (data.count > 0) {
                    countElement.textContent = data.count;
                    countElement.classList.remove('hidden');
                } else {
                    countElement.classList.add('hidden');
                }
            }
        })
        .catch(error => console.error('Error updating count:', error));
}

// Update every 30 seconds
setInterval(updatePendingCount, 30000);
</script>

<style>
/* Mobile sidebar styles */
#sidebar {
    z-index: 40;
}

@media (max-width: 768px) {
    #sidebar {
        transform: translateX(-100%);
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        z-index: 50;
    }
    
    #sidebar:not(.translate-x-0) {
        transform: translateX(-100%);
    }
    
    #sidebar.translate-x-0 {
        transform: translateX(0);
    }
}

/* Smooth transitions */
#ordersAccordionContent {
    transition: max-height 0.3s ease-in-out;
}

/* Chevron rotation */
#ordersChevron {
    transition: transform 0.2s ease-in-out;
}

/* Custom scrollbar for sidebar */
#sidebar::-webkit-scrollbar {
    width: 4px;
}

#sidebar::-webkit-scrollbar-track {
    background: #1e293b;
}

#sidebar::-webkit-scrollbar-thumb {
    background: #475569;
    border-radius: 2px;
}

#sidebar::-webkit-scrollbar-thumb:hover {
    background: #64748b;
}

/* Logout button hover effects */
#logoutForm button:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
}

/* Logout modal animations */
#logoutModal {
    animation: fadeIn 0.2s ease-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}
</style>