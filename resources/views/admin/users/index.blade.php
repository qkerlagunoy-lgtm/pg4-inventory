@extends('layouts.admin')

@section('title', 'User Management')
@section('page-title', 'User Management')

@section('content')
<div class="container mx-auto px-6">
    
    {{-- Page Header --}}
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">User Management</h1>
        <p class="text-sm text-gray-600 mt-1">AFPPGMC Logistics Division</p>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 relative">
            <span class="block sm:inline">{{ session('success') }}</span>
            <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none';">
                <span class="text-2xl">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 relative">
            <span class="block sm:inline">{{ session('error') }}</span>
            <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none';">
                <span class="text-2xl">&times;</span>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 relative">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none';">
                <span class="text-2xl">&times;</span>
            </button>
        </div>
    @endif

    {{-- Filters and Search Bar --}}
    <div class="bg-gray-50 rounded-lg border border-gray-200 p-4 mb-4">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap items-end gap-4">
            
            {{-- Filter by Status --}}
            <div class="min-w-[150px]">
                <label for="status" class="block text-xs font-medium text-gray-700 mb-1.5">Filter by Status:</label>
                <select name="status" id="status" class="w-full px-3 py-2 text-sm bg-white border border-gray-300 rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                    <option value="">All Users</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            {{-- Filter by Unit --}}
            <div class="min-w-[150px]">
                <label for="unit" class="block text-xs font-medium text-gray-700 mb-1.5">Filter by Unit:</label>
                <select name="unit" id="unit" class="w-full px-3 py-2 text-sm bg-white border border-gray-300 rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                    <option value="">All Units</option>
                    @foreach($units as $unit)
                        <option value="{{ $unit->unit }}" {{ request('unit') == $unit->unit ? 'selected' : '' }}>
                            {{ $unit->unit }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Search Input --}}
            <div class="flex-1 min-w-[250px]">
                <label for="search" class="block text-xs font-medium text-gray-700 mb-1.5">Search:</label>
                <input type="text" 
                       name="search" 
                       id="search" 
                       placeholder="Search by name, username, or email..."
                       value="{{ request('search') }}"
                       class="w-full px-4 py-2 text-sm bg-white border border-gray-300 rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
            </div>

            {{-- Search Button --}}
            <div>
                <button type="submit" class="px-6 py-2 text-sm font-medium bg-amber-600 text-white rounded shadow-md hover:bg-amber-700 active:bg-amber-800 transition-colors">
                    🔍 Search
                </button>
            </div>

            {{-- Reset Button --}}
            <div>
                <a href="{{ route('admin.users.index') }}" class="inline-block px-6 py-2 text-sm font-medium bg-gray-600 text-white rounded shadow-md hover:bg-gray-700 active:bg-gray-800 transition-colors">
                    🔄 Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Statistics Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
            <div class="text-sm text-gray-600 mb-1">Total Users</div>
            <div class="text-2xl font-bold text-gray-900">{{ $users->total() }}</div>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
            <div class="text-sm text-gray-600 mb-1">Active Users</div>
            <div class="text-2xl font-bold text-green-600">
                {{ App\Models\User::whereNotNull('email_verified_at')->count() }}
            </div>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
            <div class="text-sm text-gray-600 mb-1">Inactive Users</div>
            <div class="text-2xl font-bold text-red-600">
                {{ App\Models\User::whereNull('email_verified_at')->count() }}
            </div>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
            <div class="text-sm text-gray-600 mb-1">Admin Users</div>
            <div class="text-2xl font-bold text-blue-600">
                {{ App\Models\User::where('type', 'admin')->count() }}
            </div>
        </div>
    </div>

    {{-- Users Table --}}
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-white border-b-2 border-gray-300">
                        <th class="px-6 py-3 text-left font-semibold text-gray-800">Name</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-800">Username</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-800">Email</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-800">Unit</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-800">Role</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-800">Status</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-800">Created On</th>
                        <th class="px-6 py-3 text-center font-semibold text-gray-800">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr class="{{ $loop->iteration % 2 == 0 ? 'bg-amber-50/40' : 'bg-white' }} border-b border-gray-200 hover:bg-amber-100/30 transition-colors">
                            <td class="px-6 py-3.5 text-gray-900 font-medium">
                                {{ $user->first_name }} {{ $user->last_name }}
                            </td>
                            <td class="px-6 py-3.5 text-gray-700">{{ $user->username }}</td>
                            <td class="px-6 py-3.5 text-gray-700">{{ $user->email }}</td>
                            <td class="px-6 py-3.5 text-gray-700">{{ $user->unit ?? 'N/A' }}</td>
                            <td class="px-6 py-3.5">
                                @if($user->type == 'admin')
                                    <span class="inline-block px-3 py-1.5 text-xs font-bold rounded-md bg-purple-100 text-purple-800 border border-purple-300">
                                        ADMIN
                                    </span>
                                @else
                                    <span class="inline-block px-3 py-1.5 text-xs font-bold rounded-md bg-blue-100 text-blue-800 border border-blue-300">
                                        USER
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-3.5">
                                @if($user->email_verified_at !== null)
                                    <span class="inline-block px-3 py-1.5 text-xs font-bold rounded-md bg-green-100 text-green-800 border border-green-300">
                                        ACTIVE
                                    </span>
                                @else
                                    <span class="inline-block px-3 py-1.5 text-xs font-bold rounded-md bg-red-100 text-red-800 border border-red-300">
                                        INACTIVE
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-3.5 text-gray-700">{{ $user->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-3.5">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" 
                                       class="px-3 py-1.5 text-xs font-medium bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors"
                                       title="Edit User">
                                        ✏️ Edit
                                    </a>
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Are you sure you want to delete {{ $user->first_name }} {{ $user->last_name }}? This action cannot be undone.');"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="px-3 py-1.5 text-xs font-medium bg-red-600 text-white rounded hover:bg-red-700 transition-colors"
                                                    title="Delete User">
                                                🗑️ Delete
                                            </button>
                                        </form>
                                    @else
                                        <button type="button" 
                                                class="px-3 py-1.5 text-xs font-medium bg-gray-400 text-white rounded cursor-not-allowed"
                                                title="You cannot delete your own account"
                                                disabled>
                                                🗑️ Delete
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-16 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                    <p class="text-lg font-medium">No users found</p>
                                    <p class="text-sm text-gray-400 mt-1">Try adjusting your search or filter criteria</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination Info --}}
    @if($users->total() > 0)
        <div class="mt-4 text-sm text-gray-600">
            Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} users
        </div>
    @endif

    {{-- Spacer for visual separation --}}
    <div class="border-b border-gray-300 my-6"></div>

    {{-- Bottom Action Buttons --}}
    <div class="flex flex-wrap gap-3 mb-8">
        <a href="{{ route('admin.users.create') }}" 
           class="px-6 py-2.5 text-sm font-medium bg-amber-600 text-white rounded shadow-md hover:bg-amber-700 active:bg-amber-800 transition-colors">
            ➕ New User
        </a>
        <a href="{{ route('admin.users.pdf') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}" 
           class="px-6 py-2.5 text-sm font-medium bg-amber-800 text-white rounded shadow-md hover:bg-amber-900 active:bg-amber-950 transition-colors">
            📄 Generate PDF/CSV
        </a>
        <button type="button" 
                onclick="window.print()" 
                class="px-6 py-2.5 text-sm font-medium bg-amber-700 text-white rounded shadow-md hover:bg-amber-800 active:bg-amber-900 transition-colors">
            🖨️ Print
        </button>
    </div>

    {{-- Pagination --}}
    @if($users->hasPages())
        <div class="mt-6">
            {{ $users->links() }}
        </div>
    @endif

</div>

<style>
/* Additional fine-tuning for exact match */
table tbody tr:last-child {
    border-bottom: none;
}

/* Ensure alternating rows have subtle beige/tan color */
.bg-amber-50\/40 {
    background-color: rgba(255, 251, 235, 0.4);
}

/* Hover effect */
.bg-amber-100\/30:hover {
    background-color: rgba(254, 243, 199, 0.5);
}

/* Shadow refinements */
.shadow-md {
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.shadow-sm {
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
}

/* Print styles */
@media print {
    .no-print {
        display: none;
    }
    
    body {
        background: white;
    }
    
    .bg-amber-50\/40,
    .bg-white {
        background: white !important;
    }
}

/* Responsive table */
@media (max-width: 768px) {
    .overflow-x-auto {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
}
</style>

{{-- Optional: Add confirmation dialog with better styling --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide flash messages after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.bg-green-100, .bg-red-100');
        alerts.forEach(function(alert) {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(function() {
                alert.style.display = 'none';
            }, 500);
        });
    }, 5000);
});
</script>
@endsection