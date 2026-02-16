<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - AFPPGMC Logistics</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        @include('admin.partials.sidebar')
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            @include('admin.partials.header')
            
            <!-- Page Content -->
            <main class="flex-1 p-6 overflow-y-auto">
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        {{ session('error') }}
                    </div>
                @endif
                
                @if(session('info'))
                    <div class="mb-4 bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded">
                        {{ session('info') }}
                    </div>
                @endif
                
                @if(session('warning'))
                    <div class="mb-4 bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
                        {{ session('warning') }}
                    </div>
                @endif
                
                <!-- Page Content -->
                @yield('content')
            </main>
        </div>
    </div>

    {{-- FIX: @stack('modals') was missing — modal HTML from @push('modals') 
         in index, show, edit, and low-stock blades was never rendered,
         causing all restock/delete modal buttons to silently do nothing. --}}
    @stack('modals')
    @stack('scripts')

    <script>
        // Auto-dismiss flash messages after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const alerts = document.querySelectorAll('.bg-green-100, .bg-red-100, .bg-blue-100, .bg-yellow-100');
                alerts.forEach(alert => {
                    alert.style.transition = 'opacity 0.5s ease';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                });
            }, 5000);
        });
    </script>
</body>
</html>