@extends('layouts.admin')

@section('title', 'Add New Inventory Item')

@section('page-title', 'Add New Inventory Item')

@section('breadcrumb')
    <nav class="mb-4">
        <ol class="flex items-center space-x-2 text-sm">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-gray-700">Dashboard</a>
            </li>
            <li>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </li>
            <li>
                <a href="{{ route('admin.inventory.index') }}" class="text-gray-500 hover:text-gray-700">Inventory</a>
            </li>
            <li>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </li>
            <li class="text-blue-600 font-medium">Add New Item</li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="max-w-4xl mx-auto">

        {{-- Validation Errors --}}
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

        <!-- Form Card -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-6">Item Information</h3>

            <form action="{{ route('admin.inventory.store') }}" method="POST" id="inventoryForm">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-6">

                        <!-- Item Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Item Name *
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                                   placeholder="e.g., Printer Paper, Stapler, Laptop"
                                   required autofocus>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Category *
                            </label>
                            <select id="category_id" name="category_id"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('category_id') border-red-500 @enderror"
                                    required>
                                <option value="">Select a category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Quantity + Unit -->
                        <div>
                            <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                Initial Quantity *
                            </label>
                            <div class="flex items-center space-x-3">
                                <input type="number" id="quantity" name="quantity"
                                       value="{{ old('quantity', 0) }}" min="0"
                                       class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('quantity') border-red-500 @enderror"
                                       required>

                                {{-- FIX: was name="unit" — must be unit_of_measure to match DB column and controller validation --}}
                                <div class="w-32">
                                    <select id="unit_of_measure" name="unit_of_measure"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('unit_of_measure') border-red-500 @enderror"
                                            required>
                                        <option value="">Unit</option>
                                        @foreach($units as $unitOption)
                                            <option value="{{ $unitOption }}" {{ old('unit_of_measure') == $unitOption ? 'selected' : '' }}>
                                                {{ $unitOption }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="flex justify-between mt-1">
                                @error('quantity')
                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                @error('unit_of_measure')
                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Minimum Quantity -->
                        <div>
                            <label for="minimum_quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                Minimum Quantity *
                            </label>
                            <div class="flex items-center space-x-3">
                                <input type="number" id="minimum_quantity" name="minimum_quantity"
                                       value="{{ old('minimum_quantity', 5) }}" min="1"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('minimum_quantity') border-red-500 @enderror"
                                       required>
                                <div class="w-20 text-center">
                                    <span class="text-sm text-gray-500" id="unitDisplay">
                                        {{ old('unit_of_measure', 'pcs') }}
                                    </span>
                                </div>
                            </div>
                            @error('minimum_quantity')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">
                                System will alert when stock reaches or falls below this level
                            </p>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">

                        {{-- FIX: Removed storage_location field — column does not exist in your items table --}}

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Description
                            </label>
                            <textarea id="description" name="description" rows="6"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                                      placeholder="Describe this item, include specifications, brand, model, or any important details...">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <div class="mt-1 flex justify-between text-xs text-gray-500">
                                <span>Max 1000 characters</span>
                                <span id="charCount">0/1000</span>
                            </div>
                        </div>

                        <!-- Preview Card -->
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Preview</h4>
                            <div class="text-sm text-gray-600 space-y-1">
                                <div class="flex justify-between">
                                    <span>Stock Status:</span>
                                    <span id="previewStatus" class="font-medium">-</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Re-order Point:</span>
                                    <span id="previewReorder" class="font-medium">-</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Category:</span>
                                    <span id="previewCategory" class="font-medium">-</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-8 pt-6 border-t border-gray-200 flex justify-between items-center">
                    <a href="{{ route('admin.inventory.index') }}"
                       class="text-sm text-gray-600 hover:text-gray-900 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to Inventory
                    </a>

                    <div class="flex space-x-3">
                        <button type="button"
                                onclick="resetForm()"
                                class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                            Reset
                        </button>
                        <button type="submit"
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Add to Inventory
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Help & Guidelines -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">📝 Inventory Item Guidelines</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-0.5">
                            <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center">
                                <span class="text-xs font-medium text-blue-600">1</span>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-gray-900">Item Naming</h4>
                            <p class="text-sm text-gray-600 mt-1">
                                Use clear, descriptive names. Include brand and model when applicable.
                                Example: "HP LaserJet Pro Printer" instead of just "Printer".
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-0.5">
                            <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center">
                                <span class="text-xs font-medium text-green-600">2</span>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-gray-900">Stock Levels</h4>
                            <p class="text-sm text-gray-600 mt-1">
                                Set realistic minimum quantities. The system will alert you when stock reaches this level.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-0.5">
                            <div class="w-6 h-6 bg-purple-100 rounded-full flex items-center justify-center">
                                <span class="text-xs font-medium text-purple-600">3</span>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-gray-900">Units of Measurement</h4>
                            <p class="text-sm text-gray-600 mt-1">
                                Be consistent with units. Common units: pcs, boxes, sets, reams, bottles.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-0.5">
                            <div class="w-6 h-6 bg-yellow-100 rounded-full flex items-center justify-center">
                                <span class="text-xs font-medium text-yellow-600">4</span>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-gray-900">Category Selection</h4>
                            <p class="text-sm text-gray-600 mt-1">
                                Choose the most appropriate category. Create a new category first if needed.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-0.5">
                            <div class="w-6 h-6 bg-red-100 rounded-full flex items-center justify-center">
                                <span class="text-xs font-medium text-red-600">5</span>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-gray-900">Description Details</h4>
                            <p class="text-sm text-gray-600 mt-1">
                                Include specifications, serial numbers, warranty info, or special handling instructions.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-0.5">
                            <div class="w-6 h-6 bg-indigo-100 rounded-full flex items-center justify-center">
                                <span class="text-xs font-medium text-indigo-600">6</span>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-gray-900">Minimum Quantity</h4>
                            <p class="text-sm text-gray-600 mt-1">
                                Must be at least 1. This threshold triggers low stock alerts.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Category Creation -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-sm font-medium text-gray-900">Missing a category?</h4>
                        <p class="text-sm text-gray-600 mt-1">Create a new category first</p>
                    </div>
                    <a href="{{ route('admin.categories.create') }}"
                       class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        New Category
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const description     = document.getElementById('description');
        const charCount       = document.getElementById('charCount');
        // FIX: was 'unit' — must match the corrected select id
        const unitSelect      = document.getElementById('unit_of_measure');
        const unitDisplay     = document.getElementById('unitDisplay');
        const quantityInput   = document.getElementById('quantity');
        const minQtyInput     = document.getElementById('minimum_quantity');
        const categorySelect  = document.getElementById('category_id');
        const nameInput       = document.getElementById('name');

        // Character counter
        if (description && charCount) {
            updateCharCount();
            description.addEventListener('input', updateCharCount);
            function updateCharCount() {
                const length = description.value.length;
                charCount.textContent = `${length}/1000`;
                charCount.classList.toggle('text-red-600', length > 1000);
                charCount.classList.toggle('font-medium', length > 1000);
            }
        }

        // Sync unit display beside minimum quantity
        if (unitSelect && unitDisplay) {
            unitDisplay.textContent = unitSelect.value || '-';
            unitSelect.addEventListener('change', function() {
                unitDisplay.textContent = this.value || '-';
            });
        }

        // Live preview
        function updatePreview() {
            const quantity   = parseInt(quantityInput?.value) || 0;
            const minQty     = parseInt(minQtyInput?.value) || 0;
            const unit       = unitSelect?.value || '-';
            const category   = categorySelect?.options[categorySelect.selectedIndex]?.text || '-';

            const previewStatus   = document.getElementById('previewStatus');
            const previewReorder  = document.getElementById('previewReorder');
            const previewCategory = document.getElementById('previewCategory');

            if (previewStatus) {
                if (quantity === 0) {
                    previewStatus.textContent = 'Out of Stock';
                    previewStatus.className   = 'font-medium text-red-600';
                } else if (quantity <= minQty) {
                    previewStatus.textContent = 'Low Stock';
                    previewStatus.className   = 'font-medium text-yellow-600';
                } else {
                    previewStatus.textContent = 'In Stock';
                    previewStatus.className   = 'font-medium text-green-600';
                }
            }
            if (previewReorder)  previewReorder.textContent  = `${minQty} ${unit}`;
            if (previewCategory) previewCategory.textContent = category === 'Select a category' ? '-' : category;
        }

        [quantityInput, minQtyInput, unitSelect, categorySelect].forEach(el => {
            if (el) {
                el.addEventListener('input', updatePreview);
                el.addEventListener('change', updatePreview);
            }
        });

        updatePreview();

        // Auto-capitalise first letter
        if (nameInput) {
            nameInput.addEventListener('input', function() {
                if (this.value.length === 1) this.value = this.value.toUpperCase();
            });
        }

        // Warn if initial qty is below minimum
        if (minQtyInput) {
            minQtyInput.addEventListener('blur', function() {
                const existing = this.parentElement.querySelector('.warn-msg');
                if (existing) existing.remove();

                if ((parseInt(quantityInput?.value) || 0) < (parseInt(this.value) || 0)) {
                    const warning = document.createElement('p');
                    warning.className = 'mt-1 text-sm text-yellow-600 warn-msg';
                    warning.textContent = 'Note: Initial quantity is below minimum quantity.';
                    this.parentElement.appendChild(warning);
                }
            });
        }
    });

    function resetForm() {
        if (confirm('Are you sure you want to reset the form? All entered data will be lost.')) {
            document.getElementById('inventoryForm').reset();
            document.getElementById('unitDisplay').textContent  = '-';
            document.getElementById('charCount').textContent    = '0/1000';
            // re-run preview after reset
            setTimeout(() => {
                const event = new Event('change');
                document.getElementById('quantity')?.dispatchEvent(event);
            }, 0);
        }
    }
</script>
@endpush