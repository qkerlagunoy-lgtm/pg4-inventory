@extends('layouts.admin')

@section('title', 'Edit Category')

@section('content')

<div class="min-h-screen p-8 bg-gray-50">

    <!-- Header -->
    <div class="mb-6">
        <h2 class="font-bold text-2xl text-gray-900">Edit Category</h2>
        <p class="text-sm text-gray-500 mt-1">
            Update the category information below
        </p>
    </div>


    <!-- FORM CARD -->
    <div class="max-w-3xl bg-white rounded-lg shadow-md p-6">

        <form action="{{ route('admin.categories.update', $category) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- CODE -->
            <div class="mb-5">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Code <span class="text-red-500">*</span>
                </label>

                <input type="text"
                       name="code"
                       value="{{ old('code', $category->code) }}"
                       required
                       maxlength="50"
                       placeholder="e.g. 001, ELEC, HARD"
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-amber-500
                       @error('code') border-red-500 @enderror">

                @error('code')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>


            <!-- DESCRIPTION -->
            <div class="mb-5">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Description <span class="text-red-500">*</span>
                </label>

                <textarea name="description"
                          rows="3"
                          required
                          placeholder="Enter category description"
                          class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-amber-500
                          @error('description') border-red-500 @enderror">{{ old('description', $category->description) }}</textarea>

                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>


            <!-- STATUS -->
            <div class="mb-6">
                <label class="flex items-center gap-3 cursor-pointer">

                    <input type="checkbox"
                           name="is_active"
                           value="1"
                           {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                           class="w-5 h-5 text-amber-600 border-gray-300 rounded focus:ring-amber-500">

                    <span class="text-sm font-medium text-gray-700">
                        Active
                    </span>

                </label>
            </div>


            <!-- BUTTONS -->
            <div class="flex gap-3">
                <button type="submit"
                        class="px-6 py-2 bg-amber-700 text-white rounded-lg hover:bg-amber-800 transition shadow">
                    Update Category
                </button>

                <a href="{{ route('admin.categories.index') }}"
                   class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition shadow">
                    Cancel
                </a>
            </div>

        </form>

    </div>

</div>

@endsection
