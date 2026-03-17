@extends('layouts.admin')

@section('title', 'Edit Inventory Item: ' . $item->name)

@section('page-title', 'Edit Item: ' . $item->name)

@section('breadcrumb')
    <nav class="mb-4">
        <ol class="flex items-center space-x-2 text-sm">
            <li><a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-gray-700">Dashboard</a></li>
            <li><svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></li>
            <li><a href="{{ route('admin.inventory.index') }}" class="text-gray-500 hover:text-gray-700">Inventory</a></li>
            <li><svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></li>
            <li><a href="{{ route('admin.inventory.show', $item) }}" class="text-gray-500 hover:text-gray-700">
                {{ Str::limit($item->name, 20) }}</a></li>
            <li><svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></li>
            <li class="text-blue-600 font-medium">Edit</li>
        </ol>
    </nav>
@endsection

@section('content')

<style>
:root {
    --cream:    #FAF7F0;
    --sand:     #D8D2C2;
    --sienna:   #B17457;
    --charcoal: #4A4947;
}

.edit-page {
    background: var(--cream);
    padding: 2rem;
    font-family: 'Georgia', serif;
    min-height: 100vh;
}
.edit-container {
    max-width: 62rem;
    margin: 0 auto;
}

/* ── FLASH ── */
.flash {
    display: flex;
    align-items: flex-start;
    gap: .75rem;
    padding: .85rem 1rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    border-left: 4px solid;
}
.flash-error {
    background: #fff0f0;
    border-color: #c0392b;
}
.flash-error svg { color: #c0392b; width: 1.15rem; height: 1.15rem; flex-shrink: 0; }
.flash-error p { color: #8b2020; font-size: .875rem; font-weight: 600; margin-bottom: .4rem; }
.flash-error ul {
    list-style: disc;
    margin-left: 1.5rem;
    margin-top: .25rem;
}
.flash-error li { font-size: .85rem; color: #a02f23; margin-bottom: .2rem; }
.flash-success {
    background: #f0faf0;
    border-color: #4a8c4a;
}
.flash-success svg { color: #4a8c4a; }
.flash-success p { color: #2e6b2e; font-size: .875rem; font-weight: 600; }

/* ── SUMMARY CARD ── */
.summary-card {
    background: #fff;
    border: 1px solid var(--sand);
    border-radius: 10px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}
.summary-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.5rem;
}
.summary-left {
    display: flex;
    align-items: center;
    gap: 1rem;
}
.item-img {
    width: 4rem;
    height: 4rem;
    object-fit: cover;
    border-radius: 10px;
    border: 1px solid var(--sand);
    flex-shrink: 0;
}
.item-placeholder {
    width: 4rem;
    height: 4rem;
    background: #d9ebf7;
    border-radius: 10px;
    border: 1px solid #b3d4ec;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.item-placeholder svg { width: 2rem; height: 2rem; color: #2d5f8a; }
.summary-title h2 {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--charcoal);
}
.summary-meta {
    display: flex;
    align-items: center;
    gap: .75rem;
    margin-top: .4rem;
}
.category-badge {
    display: inline-flex;
    padding: .2rem .6rem;
    background: #d9ebf7;
    color: #2d5f8a;
    border-radius: 20px;
    font-size: .75rem;
    font-weight: 700;
}
.item-id {
    font-size: .85rem;
    color: #6b6966;
}
.summary-right {
    text-align: right;
}
.summary-right .label {
    font-size: .8rem;
    color: #6b6966;
}
.summary-right .value {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--charcoal);
}
.summary-right .min {
    font-size: .75rem;
    color: #9a9591;
    margin-top: .2rem;
}
.summary-right .min.low { color: #c0392b; font-weight: 600; }
.summary-footer {
    padding-top: 1.5rem;
    border-top: 1px solid var(--sand);
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.summary-dates {
    font-size: .85rem;
    color: #6b6966;
}
.summary-dates span { font-weight: 600; }
.btn-back {
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    padding: .5rem 1rem;
    background: #f5f1e8;
    color: var(--charcoal);
    border-radius: 8px;
    text-decoration: none;
    font-size: .875rem;
    font-weight: 600;
    transition: opacity .15s;
}
.btn-back:hover { opacity: .88; }
.btn-back svg { width: 1.15rem; height: 1.15rem; }

/* ── FORM CARD ── */
.form-card {
    background: #fff;
    border: 1px solid var(--sand);
    border-radius: 10px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}
.form-card h3 {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--charcoal);
    margin-bottom: 1.5rem;
}
.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
}
@media (max-width: 768px) {
    .form-grid { grid-template-columns: 1fr; }
}
.form-col {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}
.field {
    display: flex;
    flex-direction: column;
}
.field label {
    font-size: .8rem;
    font-weight: 700;
    color: var(--charcoal);
    margin-bottom: .5rem;
}
.field input, .field select, .field textarea {
    width: 100%;
    padding: .5rem .75rem;
    border: 1px solid var(--sand);
    border-radius: 7px;
    background: var(--cream);
    color: var(--charcoal);
    font-size: .875rem;
    font-family: inherit;
    outline: none;
}
.field input:focus, .field select:focus, .field textarea:focus {
    border-color: var(--sienna);
}
.field textarea { resize: vertical; }
.field .error-input { border-color: #c0392b; }
.field .error-msg {
    font-size: .75rem;
    color: #c0392b;
    margin-top: .3rem;
}
.field .help-text {
    font-size: .75rem;
    color: #9a9591;
    margin-top: .3rem;
}
.field .char-count {
    display: flex;
    justify-content: space-between;
    font-size: .7rem;
    color: #9a9591;
    margin-top: .3rem;
}
.field .char-count.over { color: #c0392b; font-weight: 600; }
.unit-input {
    display: flex;
    align-items: center;
    gap: .75rem;
}
.unit-input input { flex: 1; }
.unit-display {
    width: 4rem;
    text-align: center;
    font-size: .875rem;
    color: #6b6966;
}

/* ── IMAGE UPLOAD ── */
.img-current {
    display: flex;
    align-items: flex-start;
    gap: .75rem;
    margin-bottom: .75rem;
}
.img-preview {
    width: 7rem;
    height: 7rem;
    object-fit: cover;
    border-radius: 10px;
    border: 1px solid var(--sand);
    flex-shrink: 0;
}
.img-info {
    padding-top: .25rem;
}
.img-info p {
    font-size: .75rem;
    color: #9a9591;
    margin-bottom: .5rem;
}
.img-remove {
    display: flex;
    align-items: center;
    gap: .4rem;
    font-size: .75rem;
    color: #c0392b;
    font-weight: 700;
    cursor: pointer;
    user-select: none;
}
.img-remove:hover { text-decoration: underline; }
.img-remove input { width: .85rem; height: .85rem; accent-color: #c0392b; cursor: pointer; }
.upload-btn {
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    padding: .6rem 1rem;
    background: #fff;
    border: 2px dashed #b3d4ec;
    border-radius: 10px;
    color: #2d5f8a;
    font-size: .85rem;
    font-weight: 700;
    cursor: pointer;
    transition: all .15s;
}
.upload-btn:hover {
    background: #f0f7fc;
    border-color: #6ba3d4;
}
.upload-btn svg { width: 1rem; height: 1rem; }

/* ── STOCK INFO BOX ── */
.stock-box {
    background: #f5f1e8;
    border: 1px solid var(--sand);
    border-radius: 8px;
    padding: 1rem;
}
.stock-box h4 {
    font-size: .9rem;
    font-weight: 700;
    color: var(--charcoal);
    margin-bottom: .75rem;
}
.stock-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}
@media (max-width: 768px) {
    .stock-grid { grid-template-columns: 1fr; }
}
.stock-item label {
    display: block;
    font-size: .7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .04em;
    color: #9a9591;
    margin-bottom: .3rem;
}
.stock-value {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--charcoal);
}
.stock-note {
    font-size: .7rem;
    color: #9a9591;
    margin-top: .3rem;
}
.status-badge {
    display: inline-block;
    padding: .25rem .6rem;
    border-radius: 20px;
    font-size: .7rem;
    font-weight: 700;
    text-transform: uppercase;
}
.status-out { background: #ffe6e6; color: #c0392b; }
.status-low { background: #fff4e6; color: #c77d11; }
.status-ok { background: #eef6ee; color: #2e7d32; }

/* ── FORM ACTIONS ── */
.form-actions {
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--sand);
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.btn-delete {
    display: flex;
    align-items: center;
    gap: .4rem;
    font-size: .85rem;
    color: #c0392b;
    font-weight: 600;
    background: none;
    border: none;
    cursor: pointer;
    padding: 0;
}
.btn-delete:hover { text-decoration: underline; }
.btn-delete svg { width: 1rem; height: 1rem; }
.actions-right {
    display: flex;
    gap: .75rem;
}
.btn {
    padding: .5rem 1.25rem;
    font-size: .875rem;
    font-weight: 600;
    border-radius: 7px;
    border: none;
    cursor: pointer;
    transition: opacity .15s;
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    text-decoration: none;
}
.btn:hover { opacity: .88; }
.btn-muted { background: #f5f1e8; color: var(--charcoal); border: 1px solid var(--sand); }
.btn-primary { background: var(--sienna); color: #fff; }
.btn svg { width: 1.15rem; height: 1.15rem; }

/* ── QUICK ACTIONS ── */
.quick-card {
    background: #fff;
    border: 1px solid var(--sand);
    border-radius: 10px;
    padding: 1.5rem;
}
.quick-card h3 {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--charcoal);
    margin-bottom: 1rem;
}
.quick-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}
@media (max-width: 768px) {
    .quick-grid { grid-template-columns: 1fr; }
}
.quick-item {
    padding: 1rem;
    border: 1px solid var(--border);
    background: var(--bg);
    border-radius: 8px;
    cursor: pointer;
    transition: opacity .15s;
    text-decoration: none;
    display: block;
}
.quick-item:hover { opacity: .88; }
.quick-yellow { --bg: #fff4e6; --border: #e6ccb3; }
.quick-blue { --bg: #d9ebf7; --border: #b3d4ec; }
.quick-content {
    display: flex;
    align-items: center;
    gap: .75rem;
}
.quick-icon {
    width: 2.75rem;
    height: 2.75rem;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.quick-icon.yellow { background: #ffe6cc; }
.quick-icon.yellow svg { color: #c77d11; width: 1.5rem; height: 1.5rem; }
.quick-icon.blue { background: #cce5f6; }
.quick-icon.blue svg { color: #2d5f8a; width: 1.5rem; height: 1.5rem; }
.quick-text h4 {
    font-size: .9rem;
    font-weight: 700;
    color: var(--charcoal);
}
.quick-text p {
    font-size: .75rem;
    color: #6b6966;
    margin-top: .2rem;
}

/* ── MODAL ── */
.modal {
    position: fixed;
    inset: 0;
    background: rgba(74,73,71,.5);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 999;
    overflow-y: auto;
}
.modal.show { display: flex; }
.modal-content {
    background: #fff;
    border: 1px solid var(--sand);
    border-radius: 10px;
    max-width: 28rem;
    width: calc(100% - 2rem);
    padding: 1.5rem;
    margin: 1rem;
}
.modal-icon-wrap {
    width: 3rem;
    height: 3rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
}
.modal-icon-wrap.red { background: #ffe6e6; }
.modal-icon-wrap.red svg { color: #c0392b; width: 1.5rem; height: 1.5rem; }
.modal-icon-wrap.yellow { background: #fff4e6; }
.modal-icon-wrap.yellow svg { color: #c77d11; width: 1.5rem; height: 1.5rem; }
.modal-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--charcoal);
    text-align: center;
    margin-bottom: 1rem;
}
.modal-text {
    text-align: center;
    font-size: .875rem;
    color: #6b6966;
    margin-bottom: .5rem;
}
.modal-text.warning {
    color: #c0392b;
    font-weight: 600;
}
.modal-text span { font-weight: 700; color: var(--charcoal); }
.warning-box {
    padding: .75rem;
    background: #ffe6e6;
    border: 1px solid #f4b8b8;
    border-radius: 7px;
    margin-top: 1rem;
}
.warning-box p {
    font-size: .75rem;
    font-weight: 700;
    color: #c0392b;
    margin-bottom: .4rem;
}
.warning-box ul {
    list-style: none;
    padding: 0;
}
.warning-box li {
    font-size: .75rem;
    color: #a02f23;
    margin-bottom: .2rem;
    display: flex;
    align-items: flex-start;
    gap: .4rem;
}
.warning-box li svg {
    width: .75rem;
    height: .75rem;
    margin-top: .1rem;
    flex-shrink: 0;
}
.modal-actions {
    display: flex;
    justify-content: center;
    gap: .75rem;
    margin-top: 1.5rem;
}
.btn-red { background: #c0392b; color: #fff; }
.btn-yellow { background: #e6a23c; color: #fff; }
.btn-disabled {
    background: #d8d2c2;
    color: #9a9591;
    cursor: not-allowed;
}
.btn-disabled:hover { opacity: 1; }

/* ── RESTOCK FORM ── */
.restock-form {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}
.stock-info {
    padding: .75rem;
    background: #f5f1e8;
    border: 1px solid var(--sand);
    border-radius: 7px;
}
.stock-row {
    display: flex;
    justify-content: space-between;
    font-size: .85rem;
    color: #6b6966;
    margin-bottom: .25rem;
}
.stock-row:last-child { margin-bottom: 0; }
.stock-row span:last-child { font-weight: 600; color: var(--charcoal); }
.stock-row.green span:last-child { color: #4a8c4a; }
</style>

<div class="edit-page">
<div class="edit-container">

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="flash flash-error">
            <svg fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <div>
                <p>Please fix the following errors:</p>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    @if(session('success'))
        <div class="flash flash-success">
            <svg fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <!-- Item Summary Card -->
    <div class="summary-card">
        <div class="summary-header">
            <div class="summary-left">
                @if($item->image)
                    <img src="{{ asset('storage/'.$item->image) }}" class="item-img">
                @else
                    <div class="item-placeholder">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"/>
                            <path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                @endif

                <div class="summary-title">
                    <h2>{{ $item->name }}</h2>
                    <div class="summary-meta">
                        @if($item->category)
                            <span class="category-badge">{{ $item->category->name }}</span>
                        @endif
                        <span class="item-id">ID: #{{ $item->id }}</span>
                    </div>
                </div>
            </div>

            <div class="summary-right">
                <div class="label">Current Stock</div>
                <div class="value">{{ $item->quantity }} {{ $item->unit_of_measure }}</div>
                <div class="min {{ $item->quantity <= $item->minimum_quantity ? 'low' : '' }}">
                    Min: {{ $item->minimum_quantity }} {{ $item->unit_of_measure }}
                </div>
            </div>
        </div>

        <div class="summary-footer">
            <div class="summary-dates">
                <span>Created:</span> {{ $item->created_at->format('M d, Y') }} • 
                <span>Last Updated:</span> {{ $item->updated_at->format('M d, Y') }}
            </div>
            <a href="{{ route('admin.inventory.show', $item) }}" class="btn-back">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Item
            </a>
        </div>
    </div>

    <!-- Edit Form Card -->
    <div class="form-card">
        <h3>Edit Item Information</h3>

        <form action="{{ route('admin.inventory.update', $item) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <!-- Left Column -->
                <div class="form-col">
                    <!-- Item Name -->
                    <div class="field">
                        <label for="name">Item Name *</label>
                        <input type="text" id="name" name="name" 
                               value="{{ old('name', $item->name) }}"
                               class="{{ $errors->has('name') ? 'error-input' : '' }}"
                               placeholder="e.g., Printer Paper, Stapler" required>
                        @error('name')
                            <span class="error-msg">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div class="field">
                        <label for="category_id">Category *</label>
                        <select id="category_id" name="category_id" 
                                class="{{ $errors->has('category_id') ? 'error-input' : '' }}" required>
                            <option value="">Select a category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" 
                                    {{ old('category_id', $item->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <span class="error-msg">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Unit of Measurement -->
                    <div class="field">
                        <label for="unit_of_measure">Unit of Measurement *</label>
                        <select id="unit_of_measure" name="unit_of_measure" 
                                class="{{ $errors->has('unit_of_measure') ? 'error-input' : '' }}" required>
                            <option value="">Select unit</option>
                            @foreach($units as $unitOption)
                                <option value="{{ $unitOption }}" 
                                    {{ old('unit_of_measure', $item->unit_of_measure) == $unitOption ? 'selected' : '' }}>
                                    {{ $unitOption }}
                                </option>
                            @endforeach
                        </select>
                        @error('unit_of_measure')
                            <span class="error-msg">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Minimum Quantity -->
                    <div class="field">
                        <label for="minimum_quantity">Minimum Quantity *</label>
                        <div class="unit-input">
                            <input type="number" id="minimum_quantity" name="minimum_quantity" 
                                   value="{{ old('minimum_quantity', $item->minimum_quantity) }}"
                                   min="1" class="{{ $errors->has('minimum_quantity') ? 'error-input' : '' }}" required>
                            <div class="unit-display" id="unitDisplay">
                                {{ old('unit_of_measure', $item->unit_of_measure) }}
                            </div>
                        </div>
                        @error('minimum_quantity')
                            <span class="error-msg">{{ $message }}</span>
                        @enderror
                        <span class="help-text">System will alert when stock reaches or falls below this level</span>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="form-col">
                    <!-- Image Upload -->
                    <div class="field">
                        <label>Item Image</label>

                        @if($item->image)
                            <div id="currentImgWrap" class="img-current">
                                <img id="imgPreview" src="{{ asset('storage/'.$item->image) }}" class="img-preview">
                                <div class="img-info">
                                    <p>Current image</p>
                                    <label class="img-remove">
                                        <input type="checkbox" name="remove_image" value="1" 
                                               id="removeImg" onchange="toggleRemove(this)">
                                        Remove image
                                    </label>
                                </div>
                            </div>
                        @else
                            <div id="imgPreviewWrap" class="img-current" style="display:none;">
                                <img id="imgPreview" src="" class="img-preview">
                            </div>
                        @endif

                        <label for="imageInput" id="uploadLabel" class="upload-btn">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span id="uploadLabelText">{{ $item->image ? 'Change Image' : 'Upload Image' }}</span>
                        </label>
                        <input type="file" id="imageInput" name="image" 
                               accept="image/jpg,image/jpeg,image/png,image/webp" 
                               style="display:none;" onchange="previewImg(this)">

                        <span class="help-text">JPG, PNG, WEBP · Max 2MB</span>

                        @error('image')
                            <span class="error-msg">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="field">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" rows="4" 
                                  class="{{ $errors->has('description') ? 'error-input' : '' }}"
                                  placeholder="Describe this item...">{{ old('description', $item->description) }}</textarea>
                        @error('description')
                            <span class="error-msg">{{ $message }}</span>
                        @enderror
                        <div class="char-count" id="charCountWrap">
                            <span>Max 1000 characters</span>
                            <span id="charCount">{{ strlen(old('description', $item->description ?? '')) }}/1000</span>
                        </div>
                    </div>

                    <!-- Current Stock Info -->
                    <div class="stock-box">
                        <h4>Current Stock Information</h4>
                        <div class="stock-grid">
                            <div class="stock-item">
                                <label>Current Quantity</label>
                                <div class="stock-value">{{ $item->quantity }} {{ $item->unit_of_measure }}</div>
                                <p class="stock-note">To update stock quantity, use the Restock feature</p>
                            </div>
                            <div class="stock-item">
                                <label>Stock Status</label>
                                <div>
                                    @if($item->quantity == 0)
                                        <span class="status-badge status-out">Out of Stock</span>
                                    @elseif($item->quantity <= $item->minimum_quantity)
                                        <span class="status-badge status-low">Low Stock</span>
                                    @else
                                        <span class="status-badge status-ok">In Stock</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="button" onclick="showDeleteModal()" class="btn-delete">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Delete Item
                </button>

                <div class="actions-right">
                    <a href="{{ route('admin.inventory.show', $item) }}" class="btn btn-muted">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Update Item
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Quick Actions Card -->
    <div class="quick-card">
        <h3>⚡ Quick Actions</h3>
        <div class="quick-grid">
            <button type="button" onclick="showRestockModal()" class="quick-item quick-yellow">
                <div class="quick-content">
                    <div class="quick-icon yellow">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </div>
                    <div class="quick-text">
                        <h4>Restock Item</h4>
                        <p>Add more quantity to current stock</p>
                    </div>
                </div>
            </button>

            <a href="{{ route('admin.inventory.show', $item) }}#transactions" class="quick-item quick-blue">
                <div class="quick-content">
                    <div class="quick-icon blue">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div class="quick-text">
                        <h4>View History</h4>
                        <p>See all requests for this item</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

</div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <div class="modal-icon-wrap red">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
        </div>
        <h3 class="modal-title">Delete Item</h3>
        
        <p class="modal-text">
            Are you sure you want to delete "<span>{{ $item->name }}</span>"?
        </p>
        <p class="modal-text warning">Warning: This action cannot be undone!</p>

        <div class="warning-box">
            <p>Check before deleting:</p>
            <ul>
                <li>
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <span>Item has {{ $item->requestItems()->count() }} request history record(s)</span>
                </li>
                @if($item->requestItems()->exists())
                    <li>
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <span>Items with transaction history cannot be deleted</span>
                    </li>
                @endif
            </ul>
        </div>

        <div class="modal-actions">
            <button type="button" onclick="hideDeleteModal()" class="btn btn-muted">Cancel</button>
            
            @if($item->requestItems()->doesntExist())
                <form action="{{ route('admin.inventory.destroy', $item) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-red">Delete Item</button>
                </form>
            @else
                <button disabled class="btn btn-disabled" title="Cannot delete items with transaction history">
                    Delete Disabled
                </button>
            @endif
        </div>
    </div>
</div>

<!-- Restock Modal -->
<div id="restockModal" class="modal">
    <div class="modal-content">
        <div class="modal-icon-wrap yellow">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
            </svg>
        </div>
        <h3 class="modal-title">Restock: {{ $item->name }}</h3>
        
        <form action="{{ route('admin.inventory.restock', $item) }}" method="POST" class="restock-form">
            @csrf
            
            <div class="field">
                <label for="restockQuantity">Quantity to Add *</label>
                <input type="number" id="restockQuantity" name="quantity" min="1" 
                       placeholder="Enter quantity" required>
            </div>
            
            <div class="field">
                <label for="restockNotes">Notes (Optional)</label>
                <textarea id="restockNotes" name="notes" rows="3" 
                          placeholder="Add any notes about this restock..."></textarea>
            </div>
            
            <div class="stock-info">
                <div class="stock-row">
                    <span>Current Stock:</span>
                    <span>{{ $item->quantity }} {{ $item->unit_of_measure }}</span>
                </div>
                <div class="stock-row green">
                    <span>After Restock:</span>
                    <span id="afterRestock">—</span>
                </div>
            </div>
            
            <div class="modal-actions">
                <button type="button" onclick="hideRestockModal()" class="btn btn-muted">Cancel</button>
                <button type="submit" class="btn btn-yellow">Restock</button>
            </div>
        </form>
    </div>
</div>

<script>
// ── IMAGE PREVIEW ──
function previewImg(input) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
        const preview = document.getElementById('imgPreview');
        const wrap = document.getElementById('imgPreviewWrap');
        const lbl = document.getElementById('uploadLabelText');
        if (preview) preview.src = e.target.result;
        if (wrap) wrap.style.display = '';
        if (lbl) lbl.textContent = 'Change Image';
    };
    reader.readAsDataURL(input.files[0]);
}

// ── REMOVE IMAGE TOGGLE ──
function toggleRemove(cb) {
    const wrap = document.getElementById('currentImgWrap');
    const lbl = document.getElementById('uploadLabel');
    if (cb.checked) {
        if (wrap) { wrap.style.opacity = '.35'; wrap.style.pointerEvents = 'none'; }
        if (lbl) lbl.style.display = 'none';
    } else {
        if (wrap) { wrap.style.opacity = '1'; wrap.style.pointerEvents = ''; }
        if (lbl) lbl.style.display = '';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const description = document.getElementById('description');
    const charCount = document.getElementById('charCount');
    const charCountWrap = document.getElementById('charCountWrap');
    const unitSelect = document.getElementById('unit_of_measure');
    const unitDisplay = document.getElementById('unitDisplay');
    const restockQty = document.getElementById('restockQuantity');
    const afterRestock = document.getElementById('afterRestock');

    // Character counter
    if (description && charCount) {
        updateCharCount();
        description.addEventListener('input', updateCharCount);
        function updateCharCount() {
            const length = description.value.length;
            charCount.textContent = `${length}/1000`;
            if (length > 1000) {
                charCountWrap.classList.add('over');
            } else {
                charCountWrap.classList.remove('over');
            }
        }
    }

    // Sync unit display
    if (unitSelect && unitDisplay) {
        unitSelect.addEventListener('change', function() {
            unitDisplay.textContent = this.value || '-';
        });
    }

    // Restock preview
    if (restockQty && afterRestock) {
        restockQty.addEventListener('input', function() {
            const currentQty = {{ $item->quantity }};
            const addQty = parseInt(this.value) || 0;
            const unit = '{{ $item->unit_of_measure }}';
            afterRestock.textContent = `${currentQty + addQty} ${unit}`;
        });
    }

    // Auto-capitalize first letter
    const nameInput = document.getElementById('name');
    if (nameInput) {
        nameInput.addEventListener('input', function() {
            if (this.value.length === 1) this.value = this.value.toUpperCase();
        });
    }

    // Warn if new minimum qty exceeds current stock
    const minQtyInput = document.getElementById('minimum_quantity');
    if (minQtyInput) {
        minQtyInput.addEventListener('blur', function() {
            const currentQty = {{ $item->quantity }};
            const newMinQty = parseInt(this.value) || 0;
            const existing = this.parentElement.querySelector('.help-text.warning');
            if (existing) existing.remove();
            if (currentQty < newMinQty) {
                const warning = document.createElement('span');
                warning.className = 'help-text warning';
                warning.style.color = '#c77d11';
                warning.style.fontWeight = '600';
                warning.textContent = 'Note: Current stock is below the new minimum quantity.';
                this.parentElement.appendChild(warning);
            }
        });
    }
});

// ── MODALS ──
function showDeleteModal() {
    const modal = document.getElementById('deleteModal');
    modal.classList.add('show');
}
function hideDeleteModal() {
    document.getElementById('deleteModal').classList.remove('show');
}

function showRestockModal() {
    const modal = document.getElementById('restockModal');
    modal.classList.add('show');
    document.getElementById('restockQuantity').value = '';
    document.getElementById('restockNotes').value = '';
    document.getElementById('afterRestock').textContent = '—';
}
function hideRestockModal() {
    document.getElementById('restockModal').classList.remove('show');
}

document.getElementById('deleteModal').addEventListener('click', e => {
    if (e.target === e.currentTarget) hideDeleteModal();
});
document.getElementById('restockModal').addEventListener('click', e => {
    if (e.target === e.currentTarget) hideRestockModal();
});

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') { 
        hideDeleteModal(); 
        hideRestockModal(); 
    }
});
</script>

@endsection