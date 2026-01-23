@extends('layouts.app')

@section('content')
    <h1>My Purchase Requests</h1>
@endsection

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Purchase Requests</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-80 bg-slate-800 text-white">
            <div class="p-6">
                <div class="flex items-center justify-center mb-8">
                    <img src="/path-to-your-logo.png" alt="Logo" class="w-32 h-32 rounded-full bg-white p-2">
                </div>
            </div>

            <nav class="space-y-2 px-4">
                <a href="/dashboard" class="flex items-center gap-3 px-4 py-3 rounded hover:bg-slate-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Dashboard</span>
                </a>

                <a href="/request-items" class="flex items-center gap-3 px-4 py-3 rounded hover:bg-slate-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <span>Request Items</span>
                </a>

                <a href="/my-requests" class="flex items-center gap-3 px-4 py-3 rounded bg-slate-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <span>Ordered Items</span>
                </a>
            </nav>

            <div class="absolute bottom-8 left-4 right-4">
                <a href="/logout" class="flex items-center gap-3 px-4 py-3 rounded hover:bg-slate-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span>Logout</span>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1">
            <!-- Header -->
            <header class="bg-slate-800 text-white p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold">My Purchase Requests</h1>
                        <p class="text-slate-300 mt-1">AFPPGMC Logistics Division</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <span>User</span>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <div class="p-8">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <!-- Search and Filter -->
                    <div class="mb-6 flex gap-4 items-center">
                        <input 
                            type="text" 
                            id="searchInput"
                            placeholder="Search items..." 
                            class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500"
                        >
                        <button 
                            onclick="resetSearch()"
                            class="px-6 py-3 bg-slate-700 text-white rounded-lg hover:bg-slate-600 transition"
                        >
                            Reset
                        </button>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b-2 border-gray-200">
                                <tr>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Item Name</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Category</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Quantity</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Total Price</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Request Date</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Status</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Action</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                @forelse($purchaseRequests as $request)
                                <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $request->item_name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $request->category }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $request->quantity }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        ₱{{ number_format($request->total_price, 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $request->request_date->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold text-white {{ $request->getStatusBadgeClass() }}">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <button 
                                            onclick="viewDetails({{ $request->id }})"
                                            class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition text-sm"
                                        >
                                            View Details
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-gray-400 italic">
                                        No purchase requests available
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal for View Details -->
    <div id="detailsModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-8 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Request Details</h2>
                <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
            </div>
            <div id="modalContent" class="space-y-4">
                <!-- Details will be loaded here -->
            </div>
        </div>
    </div>

    <script>
        // Sample data structure for modal details
        const requestsData = @json($purchaseRequests);

        function viewDetails(id) {
            const request = requestsData.find(r => r.id === id);
            if (!request) return;

            const modalContent = document.getElementById('modalContent');
            modalContent.innerHTML = `
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-semibold text-gray-600">Item Name</label>
                        <p class="text-gray-900">${request.item_name}</p>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-600">Category</label>
                        <p class="text-gray-900">${request.category}</p>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-600">Quantity</label>
                        <p class="text-gray-900">${request.quantity}</p>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-600">Unit Price</label>
                        <p class="text-gray-900">₱${parseFloat(request.unit_price).toFixed(2)}</p>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-600">Total Price</label>
                        <p class="text-gray-900 font-bold">₱${parseFloat(request.total_price).toFixed(2)}</p>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-600">Status</label>
                        <p class="text-gray-900">${request.status.charAt(0).toUpperCase() + request.status.slice(1)}</p>
                    </div>
                    <div class="col-span-2">
                        <label class="text-sm font-semibold text-gray-600">Description</label>
                        <p class="text-gray-900">${request.description || 'N/A'}</p>
                    </div>
                    <div class="col-span-2">
                        <label class="text-sm font-semibold text-gray-600">Remarks</label>
                        <p class="text-gray-900">${request.remarks || 'No remarks'}</p>
                    </div>
                </div>
            `;
            document.getElementById('detailsModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('detailsModal').classList.add('hidden');
        }

        function resetSearch() {
            document.getElementById('searchInput').value = '';
            filterTable();
        }

        document.getElementById('searchInput').addEventListener('input', filterTable);

        function filterTable() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const rows = document.querySelectorAll('#tableBody tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        }
    </script>
</body>
</html>