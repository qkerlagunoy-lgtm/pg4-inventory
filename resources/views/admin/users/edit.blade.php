@extends('layouts.admin')

@section('title', 'Edit User')

@section('page-title', 'Edit User')

@section('content')

<style>
:root {
    --cream:    #FAF7F0;
    --sand:     #D8D2C2;
    --sienna:   #B17457;
    --charcoal: #4A4947;
}

/* ── PAGE ── */
.edit-user-page {
    background: var(--cream);
    padding: 2rem;
    font-family: 'Georgia', serif;
    min-height: 100vh;
}

/* ── FORM CARD ── */
.form-card {
    max-width: 56rem;
    background: #fff;
    border: 1px solid var(--sand);
    border-radius: 10px;
    padding: 2rem;
    box-shadow: 0 2px 8px rgba(74,73,71,.08);
}

/* ── GRID ── */
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}
@media (max-width: 768px) {
    .form-grid { grid-template-columns: 1fr; }
}

/* ── FORM FIELDS ── */
.form-field {
    display: flex;
    flex-direction: column;
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
.form-select {
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
.form-select:focus {
    border-color: var(--sienna);
    box-shadow: 0 0 0 3px rgba(177,116,87,.1);
}
.form-input.error,
.form-select.error {
    border-color: #d87070;
}
.form-error {
    font-size: .8rem;
    color: #c0392b;
    margin-top: .4rem;
}
.form-hint {
    font-size: .75rem;
    color: #9a9591;
    margin-top: .3rem;
}

/* ── PASSWORD SECTION ── */
.password-section {
    background: #fdf6ee;
    border: 1px solid #e6ccb3;
    border-radius: 8px;
    padding: 1.25rem;
    margin: 1.5rem 0;
}
.password-hint {
    font-size: .875rem;
    color: #8a5a40;
    margin-bottom: 1rem;
    display: flex;
    align-items: flex-start;
    gap: .5rem;
}

/* ── FORM ACTIONS ── */
.form-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--sand);
}
.form-actions-left {
    display: flex;
    gap: .75rem;
}

/* ── BUTTONS ── */
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
.btn-danger  { background: #c0392b; color: #fff; }
</style>

<div class="edit-user-page">
    <div class="form-card">
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <!-- Username -->
                <div class="form-field">
                    <label for="username" class="form-label">
                        Username <span class="required">*</span>
                    </label>
                    <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}" required
                           class="form-input @error('username') error @enderror">
                    @error('username')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="form-field">
                    <label for="email" class="form-label">
                        Email <span class="required">*</span>
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="form-input @error('email') error @enderror">
                    @error('email')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- First Name -->
                <div class="form-field">
                    <label for="first_name" class="form-label">
                        First Name <span class="required">*</span>
                    </label>
                    <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" required
                           class="form-input @error('first_name') error @enderror">
                    @error('first_name')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Last Name -->
                <div class="form-field">
                    <label for="last_name" class="form-label">
                        Last Name <span class="required">*</span>
                    </label>
                    <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" required
                           class="form-input @error('last_name') error @enderror">
                    @error('last_name')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Middle Name (Optional) -->
                <div class="form-field">
                    <label for="middle_name" class="form-label">
                        Middle Name
                    </label>
                    <input type="text" id="middle_name" name="middle_name" value="{{ old('middle_name', $user->middle_name) }}"
                           class="form-input @error('middle_name') error @enderror">
                    @error('middle_name')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Suffix (Optional) -->
                <div class="form-field">
                    <label for="suffix" class="form-label">
                        Suffix
                    </label>
                    <input type="text" id="suffix" name="suffix" value="{{ old('suffix', $user->suffix) }}"
                           class="form-input @error('suffix') error @enderror" placeholder="Jr., Sr., III">
                    @error('suffix')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Password Section -->
            <div class="password-section">
                <p class="password-hint">
                    💡 Leave password fields empty to keep the current password
                </p>

                <div class="form-grid">
                    <!-- New Password -->
                    <div class="form-field">
                        <label for="password" class="form-label">
                            New Password
                        </label>
                        <input type="password" id="password" name="password"
                               class="form-input @error('password') error @enderror">
                        @error('password')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                        <p class="form-hint">Minimum 8 characters</p>
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-field">
                        <label for="password_confirmation" class="form-label">
                            Confirm New Password
                        </label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                               class="form-input">
                    </div>
                </div>
            </div>

            <div class="form-grid">
                <!-- Sex/Gender -->
                <div class="form-field">
                    <label for="sex" class="form-label">
                        Gender
                    </label>
                    <select id="sex" name="sex" class="form-select @error('sex') error @enderror">
                        <option value="">Select Gender</option>
                        <option value="male" {{ old('sex', $user->sex) == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('sex', $user->sex) == 'female' ? 'selected' : '' }}>Female</option>
                    </select>
                    @error('sex')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Unit - Dropdown with predefined options -->
                <div class="form-field">
                    <label for="unit" class="form-label">
                        Unit
                    </label>
                    <select id="unit" name="unit" class="form-select @error('unit') error @enderror">
                        <option value="" disabled {{ old('unit', $user->unit) ? '' : 'selected' }}>Select Unit</option>
                        @foreach(['BDCU','CUI','COMMAND', 'TFD','ISU','LSO','PAU','PG1','PG3','PG4','PG10','PPBU'] as $unit)
                            <option value="{{ $unit }}" {{ old('unit', $user->unit) == $unit ? 'selected' : '' }}>{{ $unit }}</option>
                        @endforeach
                    </select>
                    @error('unit')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Role -->
                <div class="form-field">
                    <label for="role" class="form-label">
                        Role <span class="required">*</span>
                    </label>
                    <select id="role" name="role" required
                            class="form-select @error('role') error @enderror">
                        <option value="">Select Role</option>
                        <option value="admin" {{ old('role', $user->type) == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="user" {{ old('role', $user->type) == 'user' ? 'selected' : '' }}>User</option>
                    </select>
                    @error('role')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div class="form-field">
                    <label for="status" class="form-label">
                        Status <span class="required">*</span>
                    </label>
                    <select id="status" name="status" required
                            class="form-select @error('status') error @enderror">
                        <option value="">Select Status</option>
                        <option value="active" {{ old('status', $user->email_verified_at ? 'active' : 'inactive') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $user->email_verified_at ? 'active' : 'inactive') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                    <p class="form-hint">Active users can log in</p>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <div class="form-actions-left">
                    <button type="submit" class="btn btn-primary">
                        Update User
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-muted">
                        Cancel
                    </a>
                </div>

                @if($user->id !== auth()->id())
                    <button type="button" onclick="confirmDelete()" class="btn btn-danger">
                        Delete User
                    </button>
                @endif
            </div>
        </form>

        <!-- Hidden delete form -->
        @if($user->id !== auth()->id())
            <form id="delete-form" action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>
        @endif
    </div>
</div>

<script>
    function confirmDelete() {
        if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
            document.getElementById('delete-form').submit();
        }
    }
</script>
@endsection