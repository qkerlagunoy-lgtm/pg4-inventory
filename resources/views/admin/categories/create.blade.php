@extends('layouts.admin')

@section('title', 'New Category')

@section('content')

<style>
:root {
    --cream:    #FAF7F0;
    --sand:     #D8D2C2;
    --sienna:   #B17457;
    --charcoal: #4A4947;
}

/* ── PAGE ── */
.create-page {
    min-height: 100vh;
    background: var(--cream);
    padding: 2rem;
    font-family: 'Georgia', serif;
}

/* ── HEADER ── */
.page-header {
    margin-bottom: 1.5rem;
}
.page-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--charcoal);
    letter-spacing: .02em;
    margin-bottom: .25rem;
}
.page-subtitle {
    font-size: .875rem;
    color: #6b6966;
}

/* ── FORM CARD ── */
.form-card {
    max-width: 48rem;
    background: #fff;
    border: 1px solid var(--sand);
    border-radius: 10px;
    padding: 2rem;
    box-shadow: 0 2px 8px rgba(74,73,71,.08);
}

/* ── FORM FIELDS ── */
.form-field {
    margin-bottom: 1.5rem;
}
.form-label {
    display: block;
    font-size: .875rem;
    font-weight: 600;
    color: var(--charcoal);
    margin-bottom: .5rem;
    letter-spacing: .02em;
}
.form-label .required {
    color: #c0392b;
    font-weight: 700;
}
.form-input,
.form-textarea {
    width: 100%;
    padding: .65rem 1rem;
    font-size: .875rem;
    font-family: inherit;
    background: var(--cream);
    border: 1px solid var(--sand);
    border-radius: 8px;
    color: var(--charcoal);
    outline: none;
    transition: border-color .2s, box-shadow .2s;
}
.form-input:focus,
.form-textarea:focus {
    border-color: var(--sienna);
    box-shadow: 0 0 0 3px rgba(177,116,87,.1);
}
.form-input.error,
.form-textarea.error {
    border-color: #d87070;
}
.form-error {
    font-size: .8rem;
    color: #c0392b;
    margin-top: .4rem;
}

/* ── CHECKBOX ── */
.checkbox-wrap {
    display: flex;
    align-items: center;
    gap: .75rem;
    cursor: pointer;
    margin-bottom: 1.75rem;
}
.checkbox-input {
    width: 1.15rem;
    height: 1.15rem;
    accent-color: var(--sienna);
    cursor: pointer;
    border-radius: 4px;
    border: 1px solid var(--sand);
}
.checkbox-label {
    font-size: .875rem;
    font-weight: 600;
    color: var(--charcoal);
}

/* ── BUTTONS ── */
.form-actions {
    display: flex;
    gap: .75rem;
}
.btn {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    padding: .6rem 1.5rem;
    font-size: .875rem;
    font-weight: 600;
    font-family: inherit;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    text-decoration: none;
    transition: opacity .15s, transform .1s;
}
.btn:hover  { opacity: .88; transform: translateY(-1px); }
.btn:active { transform: translateY(0); }
.btn-primary { background: var(--sienna); color: #fff; }
.btn-muted   { background: #6b6966; color: #fff; }
</style>

<div class="create-page">

    <!-- Header -->
    <div class="page-header">
        <h2 class="page-title">New Category</h2>
        <p class="page-subtitle">Create a new category record</p>
    </div>

    <!-- Form Card -->
    <div class="form-card">

        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf

            <!-- Code -->
            <div class="form-field">
                <label class="form-label">
                    Code <span class="required">*</span>
                </label>
                <input type="text"
                       name="code"
                       value="{{ old('code') }}"
                       required
                       maxlength="50"
                       placeholder="e.g. 001, ELEC, HARD"
                       class="form-input @error('code') error @enderror">
                @error('code')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="form-field">
                <label class="form-label">
                    Description <span class="required">*</span>
                </label>
                <textarea name="description"
                          rows="3"
                          required
                          placeholder="Enter category description"
                          class="form-textarea @error('description') error @enderror">{{ old('description') }}</textarea>
                @error('description')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <label class="checkbox-wrap">
                <input type="checkbox"
                       name="is_active"
                       value="1"
                       {{ old('is_active', true) ? 'checked' : '' }}
                       class="checkbox-input">
                <span class="checkbox-label">Active</span>
            </label>

            <!-- Buttons -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    Create Category
                </button>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-muted">
                    Cancel
                </a>
            </div>

        </form>

    </div>

</div>

@endsection