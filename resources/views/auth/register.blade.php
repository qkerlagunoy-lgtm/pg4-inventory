<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - AFPPGMC</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #4e5d82 0%, #6E7DA2 60%, #7bbfc3 100%);
            display: flex; align-items: center; justify-content: center;
            padding: 2rem 1rem;
        }

        .register-wrap {
            width: 100%; max-width: 680px;
        }

        /* Header */
        .reg-header {
            text-align: center; margin-bottom: 1.75rem;
        }
        .reg-logo {
            width: 68px; height: 68px; border-radius: 50%;
            margin: 0 auto .85rem;
            border: 3px solid rgba(255,255,255,.3);
            box-shadow: 0 6px 20px rgba(0,0,0,.2);
            display: block;
        }
        .reg-header h1 {
            font-size: 1.35rem; font-weight: 700; color: #fff;
            margin-bottom: .25rem;
        }
        .reg-header p { font-size: .8rem; color: rgba(255,255,255,.7); }

        /* Card */
        .reg-card {
            background: #fff; border-radius: 22px;
            padding: 2.25rem 2.5rem;
            box-shadow: 0 24px 64px rgba(0,0,0,.18);
        }
        @media(max-width: 520px) { .reg-card { padding: 1.75rem 1.25rem; } }

        /* Section divider */
        .form-section {
            margin-bottom: 1.5rem;
        }
        .section-label {
            font-size: .65rem; font-weight: 700;
            letter-spacing: .1em; text-transform: uppercase;
            color: #9ca3af;
            display: flex; align-items: center; gap: .6rem;
            margin-bottom: .85rem;
        }
        .section-label::after {
            content: ''; flex: 1; height: 1px; background: #eaecf2;
        }

        /* Grid */
        .f-grid { display: grid; grid-template-columns: 1fr 1fr; gap: .75rem 1rem; }
        .f-grid.g1 { grid-template-columns: 1fr; }
        @media(max-width:520px){ .f-grid { grid-template-columns: 1fr; } }
        .f-group { display: flex; flex-direction: column; gap: .3rem; }

        .f-label {
            font-size: .7rem; font-weight: 700;
            letter-spacing: .04em; text-transform: uppercase;
            color: #5A5A5A;
            display: flex; align-items: center; gap: .2rem;
        }
        .req { color: #DB996C; }

        .f-input, .f-select {
            padding: .6rem .85rem;
            border: 1.5px solid #eaecf2;
            border-radius: 9px;
            font-size: .845rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #1e2535;
            background: #FCF8F3;
            outline: none; width: 100%;
            transition: border-color .15s, box-shadow .15s, background .15s;
        }
        .f-input:focus, .f-select:focus {
            border-color: #6E7DA2;
            box-shadow: 0 0 0 3px rgba(110,125,162,.1);
            background: #fff;
        }
        .f-input.is-error { border-color: #e53e3e; background: #fff8f8; }
        .f-input::placeholder { color: #9ca3af; font-size: .82rem; }

        .f-error { font-size: .7rem; color: #e53e3e; margin-top: .15rem; display: flex; align-items: center; gap: .2rem; }
        .f-error svg { width: 11px; height: 11px; flex-shrink: 0; }

        /* Radio */
        .radio-row { display: flex; gap: .6rem; flex-wrap: wrap; }
        .radio-pill {
            display: flex; align-items: center; gap: .4rem;
            padding: .5rem .95rem;
            border: 1.5px solid #eaecf2; border-radius: 9px;
            cursor: pointer; font-size: .825rem; font-weight: 500;
            color: #5A5A5A; transition: all .12s; background: #FCF8F3;
            user-select: none;
        }
        .radio-pill:hover { border-color: #6E7DA2; color: #6E7DA2; }
        .radio-pill.on { border-color: #6E7DA2; background: rgba(110,125,162,.08); color: #6E7DA2; font-weight: 600; }
        .radio-pill input { accent-color: #6E7DA2; width: 13px; height: 13px; }

        /* Global error box */
        .error-box {
            background: #fff5f5; border: 1px solid #fed7d7;
            border-radius: 10px; padding: .85rem 1rem;
            margin-bottom: 1.25rem;
            display: flex; align-items: flex-start; gap: .6rem;
        }
        .error-box svg { width: 16px; height: 16px; color: #e53e3e; flex-shrink: 0; margin-top: .1rem; }
        .error-box-text { font-size: .8rem; color: #c53030; line-height: 1.5; }
        .error-box-title { font-weight: 700; margin-bottom: .25rem; }

        /* Submit */
        .form-footer {
            display: flex; align-items: center; justify-content: space-between;
            padding-top: 1.25rem; margin-top: .5rem;
            border-top: 1px solid #eaecf2;
            gap: 1rem; flex-wrap: wrap;
        }
        .login-link {
            font-size: .82rem; color: #6b7280;
            text-decoration: none;
        }
        .login-link span { color: #6E7DA2; font-weight: 600; }
        .login-link:hover span { text-decoration: underline; }

        .btn-submit {
            display: inline-flex; align-items: center; gap: .45rem;
            background: #6E7DA2; color: #fff;
            padding: .7rem 1.75rem; border-radius: 10px;
            font-size: .875rem; font-weight: 700;
            border: none; cursor: pointer;
            font-family: 'Plus Jakarta Sans', sans-serif;
            transition: all .15s;
            box-shadow: 0 4px 14px rgba(110,125,162,.3);
        }
        .btn-submit:hover { background: #4e5d82; transform: translateY(-1px); box-shadow: 0 6px 18px rgba(110,125,162,.35); }
        .btn-submit svg { width: 15px; height: 15px; }
    </style>
</head>
<body>

<div class="register-wrap">

    {{-- Header --}}
    <div class="reg-header">
        <img src="{{ asset('images/logo.png') }}" alt="AFPPGMC" class="reg-logo">
        <h1>Create an Account</h1>
        <p>Register to access the AFPPGMC Logistics System</p>
    </div>

    {{-- Card --}}
    <div class="reg-card">

        {{-- Global error summary --}}
        @if($errors->any())
            <div class="error-box">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
                <div class="error-box-text">
                    <div class="error-box-title">Please fix the following errors:</div>
                    <ul style="list-style:none;display:flex;flex-direction:column;gap:.2rem;">
                        @foreach($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            {{-- Personal Info --}}
            <div class="form-section">
                <div class="section-label">Personal Information</div>
                <div class="f-grid">
                    <div class="f-group">
                        <label class="f-label">First Name <span class="req">*</span></label>
                        <input class="f-input {{ $errors->has('first_name') ? 'is-error' : '' }}"
                               type="text" name="first_name"
                               value="{{ old('first_name') }}"
                               placeholder="Juan" required>
                        @error('first_name')
                            <p class="f-error">
                                <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div class="f-group">
                        <label class="f-label">Last Name <span class="req">*</span></label>
                        <input class="f-input {{ $errors->has('last_name') ? 'is-error' : '' }}"
                               type="text" name="last_name"
                               value="{{ old('last_name') }}"
                               placeholder="dela Cruz" required>
                        @error('last_name')
                            <p class="f-error">
                                <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div class="f-group">
                        <label class="f-label">Middle Name</label>
                        <input class="f-input" type="text" name="middle_name"
                               value="{{ old('middle_name') }}"
                               placeholder="Optional">
                    </div>
                    <div class="f-group">
                        <label class="f-label">Suffix</label>
                        <input class="f-input" type="text" name="suffix"
                               value="{{ old('suffix') }}"
                               placeholder="Jr., Sr., III (Optional)">
                    </div>
                </div>

                <div class="f-group" style="margin-top:.75rem;">
                    <label class="f-label">Gender <span class="req">*</span></label>
                    <div class="radio-row">
                        <label class="radio-pill {{ old('sex') === 'male' ? 'on' : '' }}">
                            <input type="radio" name="sex" value="male"
                                   {{ old('sex') === 'male' ? 'checked' : '' }}
                                   onchange="togglePill(this)"> Male
                        </label>
                        <label class="radio-pill {{ old('sex') === 'female' ? 'on' : '' }}">
                            <input type="radio" name="sex" value="female"
                                   {{ old('sex') === 'female' ? 'checked' : '' }}
                                   onchange="togglePill(this)"> Female
                        </label>
                    </div>
                    @error('sex')
                        <p class="f-error">
                            <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            {{-- Account Info --}}
            <div class="form-section">
                <div class="section-label">Account Information</div>
                <div class="f-grid">
                    <div class="f-group">
                        <label class="f-label">Username <span class="req">*</span></label>
                        <input class="f-input {{ $errors->has('username') ? 'is-error' : '' }}"
                               type="text" name="username"
                               value="{{ old('username') }}"
                               placeholder="juandelacruz" required>
                        @error('username')
                            <p class="f-error">
                                <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div class="f-group">
                        <label class="f-label">Email Address <span class="req">*</span></label>
                        <input class="f-input {{ $errors->has('email') ? 'is-error' : '' }}"
                               type="email" name="email"
                               value="{{ old('email') }}"
                               placeholder="juan@pgmc.com" required>
                        @error('email')
                            <p class="f-error">
                                <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div class="f-group">
                        <label class="f-label">Password <span class="req">*</span></label>
                        <input class="f-input {{ $errors->has('password') ? 'is-error' : '' }}"
                               type="password" name="password"
                               placeholder="Min. 8 characters" required autocomplete="new-password">
                        @error('password')
                            <p class="f-error">
                                <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div class="f-group">
                        <label class="f-label">Confirm Password <span class="req">*</span></label>
                        <input class="f-input"
                               type="password" name="password_confirmation"
                               placeholder="Repeat password" required autocomplete="new-password">
                    </div>
                </div>
            </div>

            {{-- Unit --}}
            <div class="form-section">
                <div class="section-label">Assignment</div>
                <div class="f-grid g1">
                    <div class="f-group">
                        <label class="f-label">Unit / Department <span class="req">*</span></label>
                        <select class="f-select {{ $errors->has('unit') ? 'is-error' : '' }}" name="unit" required>
                            <option value="" disabled {{ old('unit') ? '' : 'selected' }}>Select Unit/Department</option>
                            @foreach(['BDCU','CUI','COMMAND','ISU','LSO','PAU','PG1','PG3','PG4','PG10','PPBU'] as $unit)
                                <option value="{{ $unit }}" {{ old('unit') === $unit ? 'selected' : '' }}>
                                    {{ $unit }}
                                </option>
                            @endforeach
                        </select>
                        @error('unit')
                            <p class="f-error">
                                <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="form-footer">
                <a href="{{ route('login') }}" class="login-link">
                    Already have an account? <span>Sign in</span>
                </a>
                <button type="submit" class="btn-submit">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    Create Account
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function togglePill(input) {
    document.querySelectorAll('.radio-pill').forEach(p => p.classList.remove('on'));
    input.closest('.radio-pill').classList.add('on');
}
</script>

</body>
</html>