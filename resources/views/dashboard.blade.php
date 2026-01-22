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
                   class="block px-6 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700">
                    Dashboard
                </a>

                <a href="#"
                   class="block px-6 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700">
                    Profile
                </a>

                <a href="#"
                   class="block px-6 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700">
                    Settings
                </a>
            </nav>

        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col">

            <!-- Header -->
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="px-6 py-4">
                    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
                        {{ __('Dashboard') }}
                    </h2>
                </div>
            </header>

            <!-- Page Content -->
            <div class="flex-1 py-12 px-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        {{ __("You're logged in!") }}
                    </div>
                </div>
            </div>

        </main>

    </div>
</x-app-layout>
