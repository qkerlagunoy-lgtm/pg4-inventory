@extends('layouts.admin')

@section('title', 'Edit Inventory Item: ' . $item->name)

@section('page-title', 'Edit Item: ' . $item->name)

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
            <li>
                <a href="{{ route('admin.inventory.show', $item) }}" class="text-gray-500 hover:text-gray-700">
                    {{ Str::limit($item->name, 20) }}
                </a>
            </li>
            <li>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </li>
            <li class="text-blue-600 font-medium">Edit</li>
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

        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Item Summary Card -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"/>
                            <path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">{{ $item->name }}</h2>
                        <div class="flex items-center gap-3 mt-1">
                            @if($item->category)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $item->category->name }}
                                </span>
                            @endif
                            <span class="text-sm text-gray-600">ID: #{{ $item->id }}</span>
                        </div>
                    </div>
                </div>

                <!-- Current Stock Status -->
                <div class="text-right">
                    <div class="text-sm text-gray-600">Current Stock</div>
                    {{-- FIX: was $item->unit --}}
                    <div class="text-2xl font-bold text-gray-800">{{ $item->quantity }} {{ $item->unit_of_measure }}</div>
                    <div class="text-xs {{ $item->quantity <= $item->minimum_quantity ? 'text-red-600 font-medium' : 'text-gray-500' }}">
                        Min: {{ $item->minimum_quantity }} {{ $item->unit_of_measure }}
                    </div>
                </div>
            </div>

            <div class="mt-6 pt-6 border-t border-gray-200 flex items-center justify-between">
                <div class="text-sm text-gray-600">
                    <span class="font-medium">Created:</span> {{ $item->created_at->format('M d, Y') }}
                    <span class="mx-2">•</span>
                    <span class="font-medium">Last Updated:</span> {{ $item->updated_at->format('M d, Y') }}
                </div>
                <a href="{{ route('admin.inventory.show', $item) }}"
                   class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Item
                </a>
            </div>
        </div>

        <!-- Edit Form Card -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-6">Edit Item Information</h3>

            <form action="{{ route('admin.inventory.update', $item) }}" method="POST" id="editInventoryForm">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <!-- Item Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Item Name *
                            </label>
                            <input type="text" id="name" name="name"
                                   value="{{ old('name', $item->name) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                                   placeholder="e.g., Printer Paper, Stapler" required>
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
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id', $item->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{--
                            FIX: was name="unit" — field name must match controller validation key
                            and the actual DB column name: unit_of_measure
                        --}}
                        <div>
                            <label for="unit_of_measure" class="block text-sm font-medium text-gray-700 mb-2">
                                Unit of Measurement *
                            </label>
                            <select id="unit_of_measure" name="unit_of_measure"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('unit_of_measure') border-red-500 @enderror"
                                    required>
                                <option value="">Select unit</option>
                                @foreach($units as $unitOption)
                                    <option value="{{ $unitOption }}"
                                        {{ old('unit_of_measure', $item->unit_of_measure) == $unitOption ? 'selected' : '' }}>
                                        {{ $unitOption }}
                                    </option>
                                @endforeach
                            </select>
                            @error('unit_of_measure')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Minimum Quantity -->
                        <div>
                            <label for="minimum_quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                Minimum Quantity *
                            </label>
                            <div class="flex items-center space-x-3">
                                <input type="number" id="minimum_quantity" name="minimum_quantity"
                                       value="{{ old('minimum_quantity', $item->minimum_quantity) }}"
                                       min="1"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('minimum_quantity') border-red-500 @enderror"
                                       required>
                                <div class="w-20 text-center">
                                    {{-- FIX: was $item->unit --}}
                                    <span class="text-sm text-gray-500" id="unitDisplay">
                                        {{ old('unit_of_measure', $item->unit_of_measure) }}
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
                </div>

                <!-- Description (Full Width) -->
                <div class="mt-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description
                    </label>
                    <textarea id="description" name="description" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                              placeholder="Describe this item, include specifications, brand, model, or any important details...">{{ old('description', $item->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <div class="mt-1 flex justify-between text-xs text-gray-500">
                        <span>Max 1000 characters</span>
                        <span id="charCount">{{ strlen(old('description', $item->description ?? '')) }}/1000</span>
                    </div>
                </div>

                <!-- Current Stock Information (Read-only) -->
                <div class="mt-6 bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-900 mb-3">Current Stock Information</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Current Quantity</label>
                            {{-- FIX: was $item->unit --}}
                            <div class="text-lg font-semibold text-gray-800">
                                {{ $item->quantity }} {{ $item->unit_of_measure }}
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                To update stock quantity, use the <span class="font-medium">Restock</span> feature
                            </p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Stock Status</label>
                            @if($item->quantity == 0)
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Out of Stock
                                </span>
                            @elseif($item->quantity <= $item->minimum_quantity)
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Low Stock
                                </span>
                            @else
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    In Stock
                                </span>
                            @endif
                            <p class="text-xs text-gray-500 mt-1">Based on current quantity vs minimum quantity</p>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-8 pt-6 border-t border-gray-200 flex justify-between items-center">
                    <button type="button"
                            onclick="showDeleteModal()"
                            class="text-sm text-red-600 hover:text-red-900 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Delete Item
                    </button>

                    <div class="flex space-x-3">
                        <a href="{{ route('admin.inventory.show', $item) }}"
                           class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                            Cancel
                        </a>
                        <button type="submit"
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Update Item
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Quick Actions Card -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">⚡ Quick Actions</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <button type="button"
                        onclick="showRestockModal()"
                        class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg hover:bg-yellow-100 transition text-left">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-yellow-100 rounded-lg">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900">Restock Item</h4>
                            <p class="text-sm text-gray-600 mt-1">Add more quantity to current stock</p>
                        </div>
                    </div>
                </button>

                <a href="{{ route('admin.inventory.show', $item) }}#transactions"
                   class="p-4 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900">View History</h4>
                            <p class="text-sm text-gray-600 mt-1">See all requests for this item</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection

@push('modals')
<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 text-center mb-2">Delete Item</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500 text-center">
                    Are you sure you want to delete "<span class="font-medium text-gray-900">{{ $item->name }}</span>"?
                </p>
                <p class="text-sm text-red-600 text-center mt-2">Warning: This action cannot be undone!</p>

                {{-- FIX: Removed $item->issuanceItems() — belongs to order module, not inventory --}}
                <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-xs text-red-700 font-medium mb-1">Check before deleting:</p>
                    <ul class="text-xs text-red-600 space-y-1">
                        <li class="flex items-start">
                            <svg class="w-3 h-3 mt-0.5 mr-1.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <span>Item has {{ $item->requestItems()->count() }} request history record(s)</span>
                        </li>
                        @if($item->requestItems()->exists())
                            <li class="flex items-start">
                                <svg class="w-3 h-3 mt-0.5 mr-1.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                <span>Items with transaction history cannot be deleted</span>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
            <div class="items-center px-4 py-3">
                <div class="flex justify-center space-x-3">
                    <button id="deleteModalCancelBtn"
                            class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-400">
                        Cancel
                    </button>

                    {{-- FIX: Only check requestItems for delete eligibility --}}
                    @if($item->requestItems()->doesntExist())
                        <form action="{{ route('admin.inventory.destroy', $item) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700">
                                Delete Item
                            </button>
                        </form>
                    @else
                        <button disabled
                                class="px-4 py-2 bg-gray-400 text-white text-base font-medium rounded-md shadow-sm cursor-not-allowed"
                                title="Cannot delete items with transaction history">
                            Delete Disabled
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Restock Modal -->
<div id="restockModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 mb-4">
                <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 text-center">Restock: {{ $item->name }}</h3>
            <div class="mt-2 px-7 py-3">
                <form action="{{ route('admin.inventory.restock', $item) }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label for="restockQuantity" class="block text-sm font-medium text-gray-700 mb-1">
                            Quantity to Add *
                        </label>
                        <input type="number" id="restockQuantity" name="quantity" min="1"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                               placeholder="Enter quantity" required>
                    </div>

                    <div>
                        <label for="restockNotes" class="block text-sm font-medium text-gray-700 mb-1">
                            Notes (Optional)
                        </label>
                        <textarea id="restockNotes" name="notes" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                                  placeholder="Add any notes about this restock..."></textarea>
                    </div>

                    <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg">
                        <div class="text-sm text-gray-600">
                            <div class="flex justify-between mb-1">
                                <span>Current Stock:</span>
                                {{-- FIX: was $item->unit --}}
                                <span class="font-medium">{{ $item->quantity }} {{ $item->unit_of_measure }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>After Restock:</span>
                                <span class="font-medium text-green-600" id="afterRestock">—</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-center space-x-3 pt-2">
                        <button type="button"
                                onclick="hideRestockModal()"
                                class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-400">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-yellow-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-yellow-700">
                            Restock
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const description  = document.getElementById('description');
        const charCount    = document.getElementById('charCount');
        // FIX: was 'unit' — must match the updated select id
        const unitSelect   = document.getElementById('unit_of_measure');
        const unitDisplay  = document.getElementById('unitDisplay');
        const restockQty   = document.getElementById('restockQuantity');
        const afterRestock = document.getElementById('afterRestock');

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

        // Update unit display beside minimum quantity when unit changes
        if (unitSelect && unitDisplay) {
            unitSelect.addEventListener('change', function() {
                unitDisplay.textContent = this.value || '-';
            });
        }

        // Restock live preview
        if (restockQty && afterRestock) {
            restockQty.addEventListener('input', function() {
                const currentQty = {{ $item->quantity }};
                const addQty     = parseInt(this.value) || 0;
                // FIX: was $item->unit
                const unit       = '{{ $item->unit_of_measure }}';
                afterRestock.textContent = `${currentQty + addQty} ${unit}`;
            });
        }

        // Auto-capitalise first letter of item name
        const nameInput = document.getElementById('name');
        if (nameInput) {
            nameInput.addEventListener('input', function() {
                if (this.value.length === 1) {
                    this.value = this.value.toUpperCase();
                }
            });
        }

        // Warn if new minimum quantity exceeds current stock
        const minQtyInput = document.getElementById('minimum_quantity');
        if (minQtyInput) {
            minQtyInput.addEventListener('blur', function() {
                const currentQty = {{ $item->quantity }};
                const newMinQty  = parseInt(this.value) || 0;
                const existing   = this.parentElement.querySelector('.text-yellow-600');
                if (existing) existing.remove();

                if (currentQty < newMinQty) {
                    const warning = document.createElement('p');
                    warning.className = 'mt-1 text-sm text-yellow-600';
                    warning.textContent = 'Note: Current stock is below the new minimum quantity.';
                    this.parentElement.appendChild(warning);
                }
            });
        }
    });

    function showDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.remove('hidden');
        document.getElementById('deleteModalCancelBtn').onclick = hideDeleteModal;
        modal.addEventListener('click', e => { if (e.target === modal) hideDeleteModal(); });
    }
    function hideDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }

    function showRestockModal() {
        const modal = document.getElementById('restockModal');
        modal.classList.remove('hidden');
        document.getElementById('restockQuantity').value      = '';
        document.getElementById('restockNotes').value         = '';
        document.getElementById('afterRestock').textContent   = '—';
        modal.addEventListener('click', e => { if (e.target === modal) hideRestockModal(); });
    }
    function hideRestockModal() {
        document.getElementById('restockModal').classList.add('hidden');
    }

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') { hideDeleteModal(); hideRestockModal(); }
    });
</script>
@endpush