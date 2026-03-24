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

<style>
:root {
    --cream:    #FAF7F0;
    --sand:     #D8D2C2;
    --sienna:   #B17457;
    --charcoal: #4A4947;
}

.edit-page {
    font-family: 'Georgia', serif;
    max-width: 42rem;
    margin: 0 auto;
}

/* Error Alert */
.error-alert {
    background: #fef2f2;
    border-left: 4px solid #dc2626;
    padding: 1rem;
    border-radius: 0 8px 8px 0;
    margin-bottom: 1.5rem;
}
.error-alert-title {
    font-size: .875rem;
    font-weight: 600;
    color: #991b1b;
    margin-bottom: .25rem;
}
.error-list {
    font-size: .875rem;
    color: #dc2626;
    list-style: disc;
    list-style-position: inside;
    display: flex;
    flex-direction: column;
    gap: .25rem;
}

/* Form Card */
.form-card {
    background: #fff;
    border: 1px solid var(--sand);
    border-radius: 10px;
    padding: 1.75rem;
    box-shadow: 0 1px 3px rgba(0,0,0,.08);
}
.form-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--charcoal);
    margin-bottom: 1.5rem;
    padding-bottom: .75rem;
    border-bottom: 1px solid #e8e2d6;
}

/* Form Grid */
.form-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1.25rem;
}
.form-grid.cols-2 {
    grid-template-columns: repeat(2, 1fr);
}
@media (max-width: 768px) {
    .form-grid.cols-2 { grid-template-columns: 1fr; }
}
.col-span-2 {
    grid-column: span 2;
}
@media (max-width: 768px) {
    .col-span-2 { grid-column: span 1; }
}

/* Form Fields */
.form-field label {
    display: block;
    font-size: .875rem;
    font-weight: 600;
    color: #6b6966;
    margin-bottom: .4rem;
}
.required {
    color: #c0392b;
}
.form-field input[type="text"],
.form-field input[type="email"],
.form-field textarea,
.form-field select {
    width: 100%;
    padding: .65rem .85rem;
    border: 1px solid var(--sand);
    border-radius: 7px;
    background: var(--cream);
    color: var(--charcoal);
    font-size: .875rem;
    font-family: 'Georgia', serif;
    outline: none;
    transition: border-color .15s;
}
.form-field input[type="text"]:focus,
.form-field input[type="email"]:focus,
.form-field textarea:focus,
.form-field select:focus {
    border-color: var(--sienna);
}
.form-field input.monospace {
    font-family: 'Courier New', monospace;
    font-size: .85rem;
}
.form-field textarea {
    resize: vertical;
}
.input-error {
    border-color: #dc2626 !important;
}
.error-message {
    margin-top: .35rem;
    font-size: .8rem;
    color: #dc2626;
}

/* Checkbox Toggle */
.checkbox-toggle {
    display: flex;
    align-items: center;
    gap: .75rem;
    cursor: pointer;
}
.checkbox-toggle input[type="checkbox"] {
    width: 1rem;
    height: 1rem;
    border: 1px solid var(--sand);
    border-radius: 4px;
    color: var(--sienna);
    cursor: pointer;
}
.checkbox-toggle input[type="checkbox"]:focus {
    ring: 2px;
    ring-color: var(--sienna);
}
.checkbox-label-main {
    font-size: .875rem;
    font-weight: 600;
    color: #6b6966;
}
.checkbox-label-hint {
    font-size: .75rem;
    color: #9a9591;
}

/* Footer Actions */
.form-footer {
    margin-top: 1.5rem;
    padding-top: 1.25rem;
    border-top: 1px solid #e8e2d6;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.back-link {
    font-size: .85rem;
    color: #6b6966;
    display: flex;
    align-items: center;
    gap: .35rem;
    transition: color .15s;
}
.back-link:hover {
    color: var(--charcoal);
}
.back-link svg {
    width: 1rem;
    height: 1rem;
}
.form-actions {
    display: flex;
    gap: .75rem;
}
.btn {
    padding: .55rem 1.25rem;
    font-size: .875rem;
    font-weight: 600;
    border-radius: 7px;
    cursor: pointer;
    transition: opacity .15s;
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    border: none;
}
.btn:hover {
    opacity: .88;
}
.btn-cancel {
    background: #f5f1e8;
    color: var(--charcoal);
    border: 1px solid var(--sand);
}
.btn-primary {
    background: var(--sienna);
    color: #fff;
}
.btn svg {
    width: 1rem;
    height: 1rem;
}
</style>

<div class="edit-page">

    @if($errors->any())
        <div class="error-alert">
            <p class="error-alert-title">Please fix the following errors:</p>
            <ul class="error-list">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-card">
        <h3 class="form-title">Edit IP Address Range</h3>

        <form method="POST" action="{{ route('admin.addresses.update', $ipRange) }}">
            @csrf
            @method('PUT')

            <div class="form-grid cols-2">

                {{-- Name --}}
                <div class="form-field col-span-2">
                    <label>
                        Range Name <span class="required">*</span>
                    </label>
                    <input type="text" name="name" required
                           value="{{ old('name', $ipRange->name) }}"
                           placeholder="e.g. PG4 Admin LAN"
                           class="@error('name') input-error @enderror">
                    @error('name')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Range Start --}}
                <div class="form-field">
                    <label>
                        Range Start <span class="required">*</span>
                    </label>
                    <input type="text" name="range_start" required
                           value="{{ old('range_start', $ipRange->range_start) }}"
                           placeholder="e.g. 192.168.1.1"
                           class="monospace @error('range_start') input-error @enderror">
                    @error('range_start')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Range End --}}
                <div class="form-field">
                    <label>
                        Range End <span class="required">*</span>
                    </label>
                    <input type="text" name="range_end" required
                           value="{{ old('range_end', $ipRange->range_end) }}"
                           placeholder="e.g. 192.168.1.254"
                           class="monospace @error('range_end') input-error @enderror">
                    @error('range_end')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Subnet Mask --}}
                <div class="form-field">
                    <label>Subnet Mask</label>
                    <input type="text" name="subnet_mask"
                           value="{{ old('subnet_mask', $ipRange->subnet_mask) }}"
                           placeholder="e.g. 255.255.255.0"
                           class="monospace">
                </div>

                {{-- Gateway --}}
                <div class="form-field">
                    <label>Gateway</label>
                    <input type="text" name="gateway"
                           value="{{ old('gateway', $ipRange->gateway) }}"
                           placeholder="e.g. 192.168.1.1"
                           class="monospace @error('gateway') input-error @enderror">
                    @error('gateway')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="form-field col-span-2">
                    <label>Description</label>
                    <textarea name="description" rows="3"
                              placeholder="Purpose or notes about this IP range...">{{ old('description', $ipRange->description) }}</textarea>
                </div>

                {{-- Active Toggle --}}
                <div class="col-span-2">
                    <label class="checkbox-toggle">
                        <input type="checkbox" name="is_active" value="1"
                               {{ old('is_active', $ipRange->is_active) ? 'checked' : '' }}>
                        <div>
                            <span class="checkbox-label-main">Active</span>
                            <p class="checkbox-label-hint">Uncheck to deactivate this range without deleting it</p>
                        </div>
                    </label>
                </div>

            </div>

            <div class="form-footer">
                <a href="{{ route('admin.addresses.index') }}" class="back-link">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Ranges
                </a>
                <div class="form-actions">
                    <a href="{{ route('admin.addresses.index') }}" class="btn btn-cancel">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
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