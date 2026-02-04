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

                <a href="{{ route('requests.my-requests') }}"
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

                @if(empty($cartItems))
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
                                    <th class="px-4 py-3 text-left">Item</th>
                                    <th class="px-4 py-3 text-left">Category</th>
                                    <th class="px-4 py-3 text-left">Available</th>
                                    <th class="px-4 py-3 text-left">Qty</th>
                                    <th class="px-4 py-3 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cartItems as $itemId => $cartItem)
                                    @php 
                                        $item = $cartItem['item'] ?? null; 
                                        $quantity = $cartItem['quantity'] ?? 0;
                                        $notes = $cartItem['notes'] ?? '';
                                    @endphp
                                    @if($item)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-4 py-3">
                                            <div class="font-medium">{{ $item->name }}</div>
                                            @if($notes)
                                            <div class="text-sm text-gray-500 mt-1">{{ $notes }}</div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">{{ $item->category->name ?? 'Uncategorized' }}</td>
                                        <td class="px-4 py-3">{{ $item->quantity }}</td>
                                        <td class="px-4 py-3">
                                            <form method="POST" action="{{ route('requests.cart.update', $itemId) }}" class="flex items-center gap-2">
                                                @csrf
                                                @method('POST')
                                                <input type="number" name="quantity" 
                                                    value="{{ $quantity }}" 
                                                    min="1" max="{{ $item->quantity }}"
                                                    class="w-20 px-2 py-1 border rounded">
                                                <input type="text" name="notes" 
                                                    value="{{ $notes }}"
                                                    placeholder="Notes"
                                                    class="flex-1 px-2 py-1 border rounded text-sm">
                                                <button type="submit" class="text-blue-600 hover:text-blue-800" title="Update">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </td>
                                        <td class="px-4 py-3">
                                            <form method="POST" action="{{ route('requests.cart.remove', $itemId) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        onclick="return confirm('Are you sure you want to remove this item?')"
                                                        class="text-red-600 hover:text-red-800 px-3 py-1 rounded">
                                                    Remove
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Cart Summary --}}
                    <div class="bg-white p-6 rounded-lg shadow">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h3 class="text-lg font-semibold">Cart Summary</h3>
                                <p class="text-gray-600">{{ count($cartItems) }} item(s) in cart</p>
                            </div>
                            <form method="POST" action="{{ route('requests.cart.clear') }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        onclick="return confirm('Are you sure you want to clear all items from your cart?')"
                                        class="text-gray-600 hover:text-gray-800 px-4 py-2 border rounded">
                                    Clear Cart
                                </button>
                            </form>
                        </div>

                        {{-- Submit Form --}}
                        <form method="POST" action="{{ route('requests.submit') }}">
                            @csrf
                            <div class="space-y-4">
                                <div>
                                    <label class="block mb-2 font-medium">Purpose of Request *</label>
                                    <textarea name="purpose" rows="3" required
                                        class="w-full border rounded p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="Explain why you need these items..."></textarea>
                                </div>

                                <div>
                                    <label class="block mb-2 font-medium">Additional Notes</label>
                                    <textarea name="notes" rows="2"
                                        class="w-full border rounded p-3"
                                        placeholder="Any additional notes about your request..."></textarea>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block mb-2 font-medium">Priority</label>
                                        <select name="priority" class="w-full border rounded p-2">
                                            <option value="medium">Medium</option>
                                            <option value="low">Low</option>
                                            <option value="high">High</option>
                                            <option value="urgent">Urgent</option>
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label class="block mb-2 font-medium">Required By (Optional)</label>
                                        <input type="date" name="required_date" 
                                            min="{{ date('Y-m-d') }}"
                                            class="w-full border rounded p-2">
                                    </div>
                                </div>

                                <div>
                                    <label class="block mb-2 font-medium">Additional Remarks (Optional)</label>
                                    <textarea name="remarks" rows="2"
                                        class="w-full border rounded p-3"
                                        placeholder="Any additional notes..."></textarea>
                                </div>

                                <button type="submit" 
                                    class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-lg transition">
                                    Submit Request
                                </button>
                            </div>
                        </form>
                    </div>
                @endif

            </div>
        </main>
    </div>
</x-app-layout>