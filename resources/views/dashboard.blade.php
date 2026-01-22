<x-app-layout>
    <div class="flex min-h-screen bg-gray-100 dark:bg-gray-900">

        <!-- Sidebar -->
        <aside class="w-64 bg-white dark:bg-gray-800 shadow-md flex flex-col">

            <!-- Logo (Centered & Bigger) -->
            <div class="flex items-center justify-center py-6 border-b dark:border-gray-700">
                <a href="{{ route('dashboard') }}">
                    <img
                        src="{{ asset('images/logo.png') }}"
                        alt="App Logo"
                        class="h-14 w-auto"
                    >
                </a>
            </div>

            <!-- Navigation -->
            <nav class="mt-4 flex-1">
                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-3 px-6 py-3 text-white hover:bg-slate-700 border-l-4 border-blue-500">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                    </svg>
                    Dashboard
                </a>

                <a href="{{ route('requests.index') }}"
                   class="flex items-center gap-3 px-6 py-3 text-gray-300 hover:bg-slate-700 border-l-4 border-transparent hover:border-blue-500">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                    </svg>
                    Request Items
                </a>

                <a href="{{ route('requests.myRequests') }}"
                   class="flex items-center gap-3 px-6 py-3 text-gray-300 hover:bg-slate-700 border-l-4 border-transparent hover:border-blue-500">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                    </svg>
                    Ordered Items
                </a>
            </nav>

        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col">

            <!-- Header -->
            <header class="bg-slate-800 shadow-lg">
                <div class="px-8 py-4 flex items-center justify-between">
                    <div>
                        <h2 class="font-bold text-2xl text-white">
                            Dashboard
                        </h2>
                        <p class="text-sm text-gray-400 mt-1">AFPPGMC Logistics Division</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <!-- User Info - Clickable to Edit Profile -->
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 hover:bg-slate-700 px-4 py-2 rounded-lg transition">
                            <div class="text-right">
                                <span class="text-white font-medium block">{{ Auth::user()->name }}</span>
                                <span class="text-gray-400 text-sm">User</span>
                            </div>
                            <button class="p-2 hover:bg-slate-600 rounded-full">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </a>
                        
                        <!-- Notification Bell Dropdown -->
                        <div class="relative">
                            <button id="notificationButton" class="p-2 hover:bg-slate-700 rounded-full relative">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                                </svg>
                                <!-- Notification Badge -->
                                <span class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold">0</span>
                            </button>

                            <!-- Notification Dropdown -->
                            <div id="notificationDropdown" class="hidden absolute right-0 mt-2 w-96 bg-white rounded-lg shadow-2xl z-50">
                                <!-- Dropdown Header -->
                                <div class="flex items-center justify-between p-4 border-b">
                                    <h3 class="text-lg font-bold text-gray-800">Notifications</h3>
                                    <div class="flex gap-3">
                                        <button class="text-sm text-blue-500 hover:text-blue-700 font-medium">Mark All as Read</button>
                                        <button class="text-sm text-blue-500 hover:text-blue-700 font-medium">Show All</button>
                                    </div>
                                </div>

                                <!-- Notification Content -->
                                <div class="p-6">
                                    <p class="text-center text-gray-400 italic">No new notifications</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="flex-1 p-8 bg-gray-50">
                
                <!-- Request Summary Section -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Request Summary</h3>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <!-- Cancelled Requests -->
                        <a href="{{ route('requests.myRequests') }}?status=cancelled" class="text-center p-4 bg-gray-50 rounded-lg hover:bg-yellow-50 hover:shadow-lg transition-all duration-200 cursor-pointer border-2 border-transparent hover:border-yellow-500">
                            <p class="text-sm text-gray-600 mb-2 font-medium">Cancelled Requests</p>
                            <div class="flex items-center justify-center gap-2">
                                <span class="bg-yellow-500 text-white rounded-full w-8 h-8 flex items-center justify-center font-bold">0</span>
                                <span class="text-2xl font-bold text-gray-400">0</span>
                            </div>
                        </a>

                        <!-- Urgent Requests -->
                        <a href="{{ route('requests.myRequests') }}?status=urgent" class="text-center p-4 bg-gray-50 rounded-lg hover:bg-red-50 hover:shadow-lg transition-all duration-200 cursor-pointer border-2 border-transparent hover:border-red-500">
                            <p class="text-sm text-gray-600 mb-2 font-medium">Urgent Requests</p>
                            <div class="flex items-center justify-center gap-2">
                                <span class="bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center font-bold">0</span>
                                <span class="text-2xl font-bold text-gray-400">0</span>
                            </div>
                        </a>

                        <!-- Approved Requests -->
                        <a href="{{ route('requests.myRequests') }}?status=approved" class="text-center p-4 bg-gray-50 rounded-lg hover:bg-green-50 hover:shadow-lg transition-all duration-200 cursor-pointer border-2 border-transparent hover:border-green-500">
                            <p class="text-sm text-gray-600 mb-2 font-medium">Approved Requests</p>
                            <div class="flex items-center justify-center gap-2">
                                <span class="text-3xl">👍</span>
                                <span class="text-2xl font-bold text-gray-400">0</span>
                            </div>
                        </a>

                        <!-- Rejected Requests -->
                        <a href="{{ route('requests.myRequests') }}?status=rejected" class="text-center p-4 bg-gray-50 rounded-lg hover:bg-red-50 hover:shadow-lg transition-all duration-200 cursor-pointer border-2 border-transparent hover:border-red-500">
                            <p class="text-sm text-gray-600 mb-2 font-medium">Rejected Requests</p>
                            <div class="flex items-center justify-center gap-2">
                                <span class="text-3xl">👎</span>
                                <span class="text-2xl font-bold text-gray-400">0</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

        </main>

    </div>
</x-app-layout>
