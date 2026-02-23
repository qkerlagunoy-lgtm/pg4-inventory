@extends('layouts.user')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard')

@section('content')
    <!-- Request Summary Section -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Request Summary</h3>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <!-- Cancelled Requests -->
            <a href="{{ route('requests.my-requests') }}?status=cancelled" class="text-center p-4 bg-gray-50 rounded-lg hover:bg-yellow-50 hover:shadow-lg transition-all duration-200 cursor-pointer border-2 border-transparent hover:border-yellow-500">
                <p class="text-sm text-gray-600 mb-2 font-medium">Cancelled Requests</p>
                <div class="flex items-center justify-center gap-2">
                    <span class="bg-yellow-500 text-white rounded-full w-8 h-8 flex items-center justify-center font-bold">⚠</span>
                    <span class="text-2xl font-bold {{ $stats['cancelled'] > 0 ? 'text-yellow-600' : 'text-gray-400' }}">{{ $stats['cancelled'] }}</span>
                </div>
            </a>

            <!-- Urgent Requests -->
            <a href="{{ route('requests.my-requests') }}?status=urgent" class="text-center p-4 bg-gray-50 rounded-lg hover:bg-red-50 hover:shadow-lg transition-all duration-200 cursor-pointer border-2 border-transparent hover:border-red-500">
                <p class="text-sm text-gray-600 mb-2 font-medium">Urgent Requests</p>
                <div class="flex items-center justify-center gap-2">
                    <span class="bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center font-bold">🚨</span>
                    <span class="text-2xl font-bold {{ $stats['urgent'] > 0 ? 'text-red-600' : 'text-gray-400' }}">{{ $stats['urgent'] }}</span>
                </div>
            </a>

            <!-- Approved Requests -->
            <a href="{{ route('requests.my-requests') }}?status=approved" class="text-center p-4 bg-gray-50 rounded-lg hover:bg-green-50 hover:shadow-lg transition-all duration-200 cursor-pointer border-2 border-transparent hover:border-green-500">
                <p class="text-sm text-gray-600 mb-2 font-medium">Approved Requests</p>
                <div class="flex items-center justify-center gap-2">
                    <span class="text-3xl">👍</span>
                    <span class="text-2xl font-bold {{ $stats['approved'] > 0 ? 'text-green-600' : 'text-gray-400' }}">{{ $stats['approved'] }}</span>
                </div>
            </a>

            <!-- Rejected Requests -->
            <a href="{{ route('requests.my-requests') }}?status=rejected" class="text-center p-4 bg-gray-50 rounded-lg hover:bg-red-50 hover:shadow-lg transition-all duration-200 cursor-pointer border-2 border-transparent hover:border-red-500">
                <p class="text-sm text-gray-600 mb-2 font-medium">Rejected Requests</p>
                <div class="flex items-center justify-center gap-2">
                    <span class="text-3xl">👎</span>
                    <span class="text-2xl font-bold {{ $stats['rejected'] > 0 ? 'text-red-600' : 'text-gray-400' }}">{{ $stats['rejected'] }}</span>
                </div>
            </a>
        </div>
    </div>

    <!-- Critical Requests Section -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Critical Requests</h3>
            <span class="text-sm text-gray-500">Urgent & Pending</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b">
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Purpose</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Created</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Status</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Priority</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($criticalRequests as $request)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-4 text-sm text-gray-800">{{ Str::limit($request->purpose, 50) }}</td>
                            <td class="py-3 px-4 text-sm text-gray-600">{{ $request->created_at->format('M d, Y') }}</td>
                            <td class="py-3 px-4">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    @if($request->status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($request->status == 'approved') bg-green-100 text-green-800
                                    @elseif($request->status == 'rejected') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                @if($request->priority == 'urgent')
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                        🚨 Urgent
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                        Normal
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-8 text-gray-400 italic">
                                No critical requests at this time
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection