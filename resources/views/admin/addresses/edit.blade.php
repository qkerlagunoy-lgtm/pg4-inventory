@extends('layouts.admin')

@section('title', 'Edit IP Range: ' . $ipRange->name)
@section('page-title', 'Edit IP Range')

@section('breadcrumb')
<nav class="mb-4">
    <ol class="flex items-center space-x-2 text-sm">
        <li><a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-gray-700">Dashboard</a></li>
        <li><svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></li>
        <li><a href="{{ route('admin.addresses.index') }}" class="text-gray-500 hover:text-gray-700">IP Address Ranges</a></li>
        <li><svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></li>
        <li class="text-blue-600 font-medium">Edit: {{ $ipRange->name }}</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="max-w-2xl mx-auto">

    @if($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
            <p class="text-sm font-medium text-red-800 mb-1">Please fix the following errors:</p>
            <ul class="text-sm text-red-700 list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-6">Edit IP Address Range</h3>

        <form method="POST" action="{{ route('admin.addresses.update', $ipRange) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- Name --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Range Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" required
                           value="{{ old('name', $ipRange->name) }}"
                           placeholder="e.g. PG4 Admin LAN"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                  @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Range Start --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Range Start <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="range_start" required
                           value="{{ old('range_start', $ipRange->range_start) }}"
                           placeholder="e.g. 192.168.1.1"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-mono
                                  @error('range_start') border-red-500 @enderror">
                    @error('range_start')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Range End --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Range End <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="range_end" required
                           value="{{ old('range_end', $ipRange->range_end) }}"
                           placeholder="e.g. 192.168.1.254"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-mono
                                  @error('range_end') border-red-500 @enderror">
                    @error('range_end')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Subnet Mask --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Subnet Mask</label>
                    <input type="text" name="subnet_mask"
                           value="{{ old('subnet_mask', $ipRange->subnet_mask) }}"
                           placeholder="e.g. 255.255.255.0"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-mono">
                </div>

                {{-- Gateway --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Gateway</label>
                    <input type="text" name="gateway"
                           value="{{ old('gateway', $ipRange->gateway) }}"
                           placeholder="e.g. 192.168.1.1"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-mono">
                    @error('gateway')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="3"
                              placeholder="Purpose or notes about this IP range..."
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('description', $ipRange->description) }}</textarea>
                </div>

                {{-- Active Toggle --}}
                <div class="md:col-span-2">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1"
                               {{ old('is_active', $ipRange->is_active) ? 'checked' : '' }}
                               class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <div>
                            <span class="text-sm font-medium text-gray-700">Active</span>
                            <p class="text-xs text-gray-400">Uncheck to deactivate this range without deleting it</p>
                        </div>
                    </label>
                </div>

            </div>

            <div class="mt-6 pt-5 border-t border-gray-200 flex justify-between items-center">
                <a href="{{ route('admin.addresses.index') }}"
                   class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Ranges
                </a>
                <div class="flex gap-3">
                    <a href="{{ route('admin.addresses.index') }}"
                       class="px-5 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Update Range
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection