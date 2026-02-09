<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - AFPPGMC Logistics</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Additional CSS Libraries (optional) -->
    @stack('head')
    @stack('styles')
    
    <style>
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        
        /* Smooth Transitions */
        .transition-all {
            transition: all 0.3s ease-in-out;
        }
        
        /* Print Styles */
        @media print {
            .no-print {
                display: none !important;
            }
            
            body {
                background: white !important;
                color: black !important;
            }
            
            main {
                padding: 0 !important;
                margin: 0 !important;
            }
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        @include('admin.partials.sidebar')
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            @include('admin.partials.header')
            
            <!-- Page Content -->
            <main class="flex-1 p-4 md:p-6 lg:p-8 overflow-y-auto">
                <!-- Page Title and Breadcrumb -->
                <div class="mb-6">
                    @hasSection('page-title')
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
                            <div>
                                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
                                    @yield('page-title')
                                </h1>
                                @hasSection('page-subtitle')
                                    <p class="text-gray-600 mt-2">
                                        @yield('page-subtitle')
                                    </p>
                                @endif
                            </div>
                            
                            <!-- Action Buttons (if any) -->
                            @hasSection('header-actions')
                                <div class="flex items-center space-x-2">
                                    @yield('header-actions')
                                </div>
                            @endif
                        </div>
                    @endif
                    
                    <!-- Breadcrumb -->
                    @hasSection('breadcrumb')
                        <div class="mb-6">
                            @yield('breadcrumb')
                        </div>
                    @else
                        <!-- Default Breadcrumb -->
                        <nav class="mb-6" aria-label="Breadcrumb">
                            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                                <li>
                                    <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                        </svg>
                                    </a>
                                </li>
                                @hasSection('breadcrumb-items')
                                    @yield('breadcrumb-items')
                                @endif
                                <li class="text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </li>
                                <li class="font-medium text-gray-900">
                                    @yield('title', 'Dashboard')
                                </li>
                            </ol>
                        </nav>
                    @endif
                </div>
                
                <!-- Flash Messages -->
                <div class="mb-6 space-y-3">
                    @if(session('success'))
                        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                                </div>
                                <div class="ml-auto pl-3">
                                    <button type="button" onclick="this.parentElement.parentElement.remove()" class="text-green-500 hover:text-green-700">
                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                                </div>
                                <div class="ml-auto pl-3">
                                    <button type="button" onclick="this.parentElement.parentElement.remove()" class="text-red-500 hover:text-red-700">
                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    @if(session('info'))
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-blue-800">{{ session('info') }}</p>
                                </div>
                                <div class="ml-auto pl-3">
                                    <button type="button" onclick="this.parentElement.parentElement.remove()" class="text-blue-500 hover:text-blue-700">
                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    @if(session('warning'))
                        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-r-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-yellow-800">{{ session('warning') }}</p>
                                </div>
                                <div class="ml-auto pl-3">
                                    <button type="button" onclick="this.parentElement.parentElement.remove()" class="text-yellow-500 hover:text-yellow-700">
                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                
                <!-- Page Content -->
                @yield('content')
            </main>
            
            <!-- Footer -->
            <footer class="bg-white border-t border-gray-200 py-4 px-6 no-print">
                <div class="flex flex-col md:flex-row items-center justify-between text-sm text-gray-600">
                    <div class="mb-2 md:mb-0">
                        <p>&copy; {{ date('Y') }} AFPPGMC Logistics Division. All rights reserved.</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span>v1.0.0</span>
                        <span class="hidden md:inline">•</span>
                        <span>Server Time: {{ now()->format('M d, Y h:i A') }}</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    
    <!-- Confirmation Modal (reusable) -->
    <div id="confirmation-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.73 0L4.342 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 text-center" id="modal-title">
                    Confirm Action
                </h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500 text-center" id="modal-message">
                        Are you sure you want to perform this action?
                    </p>
                </div>
                <div class="items-center px-4 py-3">
                    <div class="flex justify-center space-x-3">
                        <button id="modal-cancel-btn" 
                                class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                            Cancel
                        </button>
                        <button id="modal-confirm-btn" 
                                class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                            Confirm
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Loading Overlay -->
    <div id="loading-overlay" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-lg flex items-center space-x-3">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            <span class="text-gray-700 font-medium">Processing...</span>
        </div>
    </div>
    
    @stack('modals')
    @stack('scripts')
    
    <script>
        // Auto-dismiss flash messages after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const alerts = document.querySelectorAll('.bg-green-50, .bg-red-50, .bg-blue-50, .bg-yellow-50');
                alerts.forEach(alert => {
                    alert.style.transition = 'opacity 0.5s ease';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                });
            }, 5000);
            
            // Initialize tooltips
            initTooltips();
            
            // Initialize confirmation modal
            initConfirmationModal();
        });
        
        // Show loading overlay
        window.showLoading = function() {
            document.getElementById('loading-overlay').classList.remove('hidden');
        };
        
        // Hide loading overlay
        window.hideLoading = function() {
            document.getElementById('loading-overlay').classList.add('hidden');
        };
        
        // Confirmation modal functions
        function initConfirmationModal() {
            const modal = document.getElementById('confirmation-modal');
            const cancelBtn = document.getElementById('modal-cancel-btn');
            const confirmBtn = document.getElementById('modal-confirm-btn');
            
            let confirmCallback = null;
            
            // Cancel button
            cancelBtn.addEventListener('click', function() {
                modal.classList.add('hidden');
                confirmCallback = null;
            });
            
            // Confirm button
            confirmBtn.addEventListener('click', function() {
                if (confirmCallback) {
                    confirmCallback();
                }
                modal.classList.add('hidden');
                confirmCallback = null;
            });
            
            // Close modal on background click
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                    confirmCallback = null;
                }
            });
            
            // Export function to window
            window.showConfirmation = function(message, title = 'Confirm Action', callback) {
                document.getElementById('modal-title').textContent = title;
                document.getElementById('modal-message').textContent = message;
                confirmCallback = callback;
                modal.classList.remove('hidden');
            };
        }
        
        // Tooltip initialization
        function initTooltips() {
            const tooltips = document.querySelectorAll('[data-tooltip]');
            tooltips.forEach(element => {
                element.addEventListener('mouseenter', showTooltip);
                element.addEventListener('mouseleave', hideTooltip);
            });
        }
        
        function showTooltip(e) {
            const tooltipText = this.getAttribute('data-tooltip');
            const tooltip = document.createElement('div');
            tooltip.className = 'absolute z-50 px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm tooltip';
            tooltip.textContent = tooltipText;
            
            document.body.appendChild(tooltip);
            
            const rect = this.getBoundingClientRect();
            tooltip.style.left = rect.left + window.scrollX + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
            tooltip.style.top = rect.top + window.scrollY - tooltip.offsetHeight - 5 + 'px';
            
            this.tooltipElement = tooltip;
        }
        
        function hideTooltip() {
            if (this.tooltipElement) {
                this.tooltipElement.remove();
                this.tooltipElement = null;
            }
        }
        
        // Print function
        window.printPage = function() {
            window.print();
        };
        
        // Export to CSV function (basic)
        window.exportToCSV = function(filename, data) {
            const blob = new Blob([data], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.setAttribute('hidden', '');
            a.setAttribute('href', url);
            a.setAttribute('download', filename);
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        };
        
        // Handle form submissions with loading
        document.addEventListener('submit', function(e) {
            const form = e.target;
            if (form.classList.contains('show-loading')) {
                showLoading();
            }
        });
        
        // Handle AJAX errors globally
        document.addEventListener('ajax:error', function(e) {
            hideLoading();
            alert('An error occurred. Please try again.');
        });
        
        // Toggle sidebar on mobile
        window.toggleSidebar = function() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('-translate-x-full');
        };
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.getElementById('sidebar-toggle');
            
            if (window.innerWidth < 768 && 
                !sidebar.contains(e.target) && 
                !toggleBtn.contains(e.target) && 
                !sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.add('-translate-x-full');
            }
        });
    </script>
</body>
</html>