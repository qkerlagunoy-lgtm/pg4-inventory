<x-app-layout>
    <div class="flex min-h-screen bg-gray-100 dark:bg-gray-900">

        <!-- Sidebar -->
        <aside class="w-64 bg-slate-800 shadow-md flex flex-col">
            <div class="flex items-center justify-center py-8 border-b border-gray-700">
                <a href="{{ route('dashboard') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="App Logo" class="h-32 w-auto">
                </a>
            </div>

            <nav class="mt-6 flex-1">
                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-3 px-6 py-3 text-gray-300 hover:bg-slate-700 border-l-4 border-transparent hover:border-blue-500">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                    </svg>
                    Dashboard
                </a>

                <a href="{{ route('requests.index') }}"
                   class="flex items-center gap-3 px-6 py-3 text-white hover:bg-slate-700 border-l-4 border-blue-500">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5z" clip-rule="evenodd"/>
                    </svg>
                    Request Items
                </a>

                <a href="{{ route('requests.myRequests') }}"
                   class="flex items-center gap-3 px-6 py-3 text-gray-300 hover:bg-slate-700 border-l-4 border-transparent hover:border-blue-500">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222 1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14z"/>
                    </svg>
                    Ordered Items
                </a>
            </nav>

            <div class="p-4 border-t border-gray-700">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex items-center gap-3 w-full px-6 py-3 text-gray-300 hover:bg-slate-700 rounded-lg">
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col">

            <!-- Header -->
            <header class="bg-slate-800 shadow-lg px-8 py-4">
                <h2 class="text-2xl font-bold text-white">Shopping Cart</h2>
                <p class="text-sm text-gray-400">Review your items before submitting</p>
            </header>

            <!-- Page Content -->
            <div class="flex-1 p-8 bg-gray-50">

                {{-- Flash Messages --}}
                @if(session('success'))
                    <div class="bg-green-100 text-green-700 p-4 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <a href="{{ route('requests.index') }}"
                   class="inline-block mb-6 bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg">
                    ← Back to Items
                </a>

                {{-- Empty Cart --}}
                @if(empty($cart))
                    <div class="bg-white p-12 rounded-lg shadow text-center">
                        <h3 class="text-xl font-semibold text-gray-600 mb-2">
                            Your cart is empty
                        </h3>
                        <p class="text-gray-500 mb-6">
                            Add items to your cart to submit a request
                        </p>
                        <a href="{{ route('requests.index') }}"
                           class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                            Browse Items
                        </a>
                    </div>

                {{-- Cart Items --}}
                @else
                    <div class="bg-white rounded-lg shadow mb-6 overflow-hidden">
                        <table class="w-full">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-3 text-left">Category</th>
                                    <th class="px-4 py-3 text-left">Item</th>
                                    <th class="px-4 py-3 text-left">Qty</th>
                                    <th class="px-4 py-3 text-left">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cart as $id => $item)
                                    <tr class="border-b">
                                        <td class="px-4 py-3">{{ $item['category'] }}</td>
                                        <td class="px-4 py-3">{{ $item['item_name'] }}</td>
                                        <td class="px-4 py-3">{{ $item['quantity'] }}</td>
                                        <td class="px-4 py-3">
                                            <form method="POST" action="{{ route('requests.removeFromCart', $id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button class="bg-red-600 text-white px-3 py-1 rounded">
                                                    Remove
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Submit --}}
                    <div class="bg-white p-6 rounded-lg shadow">
                        <form method="POST" action="{{ route('requests.submit') }}">
                            @csrf
                            <label class="block mb-2 font-medium">Purpose</label>
                            <textarea name="purpose" rows="3" required
                                class="w-full border rounded p-2 mb-4"></textarea>

                            <button class="bg-green-600 text-white px-6 py-2 rounded">
                                Submit Request
                            </button>
                        </form>
                    </div>
                @endif

            </div>
        </main>
    </div>
</x-app-layout>
