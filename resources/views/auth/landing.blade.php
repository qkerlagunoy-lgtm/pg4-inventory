@extends('layouts.landing')

@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

* { box-sizing: border-box; margin: 0; padding: 0; }

body {
    font-family: 'Plus Jakarta Sans', sans-serif;
    min-height: 100vh;
    background: #0f1623;
    overflow-x: hidden;
}

/* ── BACKGROUND ── */
.bg-scene {
    position: fixed; inset: 0; z-index: 0;
    background: linear-gradient(135deg, #0f1623 0%, #1a2540 50%, #0f1e2e 100%);
    overflow: hidden;
}
.bg-orb {
    position: absolute; border-radius: 50%;
    filter: blur(80px); opacity: .25;
    animation: drift 12s ease-in-out infinite alternate;
}
.bg-orb-1 { width: 500px; height: 500px; background: #1e40af; top: -100px; left: -100px; animation-delay: 0s; }
.bg-orb-2 { width: 400px; height: 400px; background: #0e7490; bottom: -80px; right: -80px; animation-delay: -4s; }
.bg-orb-3 { width: 300px; height: 300px; background: #7c3aed; top: 40%; left: 40%; animation-delay: -8s; }

@keyframes drift {
    from { transform: translate(0,0) scale(1); }
    to   { transform: translate(30px,20px) scale(1.08); }
}

/* ── GRID OVERLAY ── */
.bg-grid {
    position: absolute; inset: 0;
    background-image:
        linear-gradient(rgba(255,255,255,.025) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,.025) 1px, transparent 1px);
    background-size: 50px 50px;
}

/* ── MAIN LAYOUT ── */
.auth-shell {
    position: relative; z-index: 1;
    min-height: 100vh;
    display: flex; align-items: center; justify-content: center;
    padding: 2rem 1rem;
}

.auth-container {
    display: flex;
    width: 100%; max-width: 1100px;
    min-height: 640px;
    border-radius: 24px;
    overflow: hidden;
    box-shadow: 0 40px 100px rgba(0,0,0,.5), 0 0 0 1px rgba(255,255,255,.06);
}

/* ── LEFT PANEL ── */
.auth-left {
    width: 42%;
    background: linear-gradient(160deg, #1e3a5f 0%, #0f2744 60%, #0a1f3a 100%);
    padding: 3rem 2.5rem;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    position: relative; overflow: hidden;
    border-right: 1px solid rgba(255,255,255,.07);
}
.auth-left::before {
    content: '';
    position: absolute; inset: 0;
    background: radial-gradient(ellipse at 30% 20%, rgba(59,130,246,.15) 0%, transparent 60%),
                radial-gradient(ellipse at 70% 80%, rgba(14,116,144,.12) 0%, transparent 60%);
}
.left-content { position: relative; z-index: 1; text-align: center; }

.logo-ring {
    width: 130px; height: 130px;
    border-radius: 50%;
    border: 2px solid rgba(59,130,246,.4);
    padding: 6px;
    margin: 0 auto 1.75rem;
    box-shadow: 0 0 0 1px rgba(59,130,246,.15), 0 20px 40px rgba(0,0,0,.4);
    animation: pulse-ring 3s ease-in-out infinite;
}
.logo-ring img {
    width: 100%; height: 100%;
    border-radius: 50%; object-fit: contain;
}
@keyframes pulse-ring {
    0%, 100% { box-shadow: 0 0 0 1px rgba(59,130,246,.15), 0 20px 40px rgba(0,0,0,.4), 0 0 0 0 rgba(59,130,246,.2); }
    50%       { box-shadow: 0 0 0 1px rgba(59,130,246,.15), 0 20px 40px rgba(0,0,0,.4), 0 0 0 10px rgba(59,130,246,0); }
}

.left-title {
    font-size: 1.1rem; font-weight: 800;
    color: #fff; line-height: 1.3;
    letter-spacing: .01em; margin-bottom: .75rem;
}
.left-sub {
    font-size: .78rem; color: rgba(148,183,255,.7);
    line-height: 1.6; margin-bottom: 2rem;
}

.left-pills {
    display: flex; flex-wrap: wrap;
    justify-content: center; gap: .5rem;
}
.left-pill {
    display: flex; align-items: center; gap: .35rem;
    padding: .35rem .85rem;
    background: rgba(59,130,246,.12);
    border: 1px solid rgba(59,130,246,.2);
    border-radius: 20px;
    font-size: .7rem; font-weight: 600;
    color: rgba(148,183,255,.8);
}
.left-pill svg { width: 12px; height: 12px; }

/* ── RIGHT PANEL ── */
.auth-right {
    flex: 1;
    background: #111827;
    padding: 2.5rem 2.75rem;
    display: flex; flex-direction: column;
    overflow-y: auto;
}

/* ── TABS ── */
.tab-bar {
    display: flex;
    background: rgba(255,255,255,.04);
    border: 1px solid rgba(255,255,255,.07);
    border-radius: 12px;
    padding: 4px;
    margin-bottom: 2rem;
}
.tab-btn {
    flex: 1; padding: .7rem 1rem;
    background: none; border: none;
    border-radius: 9px;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: .82rem; font-weight: 600;
    color: rgba(255,255,255,.4);
    cursor: pointer;
    transition: all .2s;
    display: flex; align-items: center; justify-content: center; gap: .45rem;
}
.tab-btn svg { width: 15px; height: 15px; }
.tab-btn:hover { color: rgba(255,255,255,.7); background: rgba(255,255,255,.04); }
.tab-btn.active {
    background: #1d4ed8;
    color: #fff;
    box-shadow: 0 4px 16px rgba(29,78,216,.4);
}

/* ── FORM HEADING ── */
.form-head { margin-bottom: 1.75rem; }
.form-head h2 {
    font-size: 1.4rem; font-weight: 800;
    color: #fff; margin-bottom: .3rem;
}
.form-head p { font-size: .8rem; color: rgba(255,255,255,.4); }

/* ── ALERTS ── */
.alert-error {
    background: rgba(220,38,38,.1);
    border: 1px solid rgba(220,38,38,.3);
    border-radius: 10px; padding: .85rem 1rem;
    margin-bottom: 1.25rem;
    display: flex; align-items: flex-start; gap: .65rem;
}
.alert-error svg { width: 16px; height: 16px; color: #f87171; flex-shrink: 0; margin-top: .1rem; }
.alert-error-text { font-size: .78rem; color: #fca5a5; line-height: 1.5; }
.alert-success {
    background: rgba(16,185,129,.1);
    border: 1px solid rgba(16,185,129,.25);
    border-radius: 10px; padding: .85rem 1rem;
    margin-bottom: 1.25rem;
    font-size: .78rem; color: #6ee7b7;
}

/* ── FORM FIELDS ── */
.f-row { display: grid; grid-template-columns: 1fr 1fr; gap: .65rem 1rem; margin-bottom: .65rem; }
.f-row.full { grid-template-columns: 1fr; }
.f-group { display: flex; flex-direction: column; gap: .3rem; margin-bottom: .65rem; }
.f-group:last-child { margin-bottom: 0; }

.f-label {
    font-size: .67rem; font-weight: 700;
    letter-spacing: .07em; text-transform: uppercase;
    color: rgba(255,255,255,.45);
}
.req { color: #f87171; }

.f-input, .f-select {
    padding: .65rem .9rem;
    background: rgba(255,255,255,.05);
    border: 1px solid rgba(255,255,255,.1);
    border-radius: 9px;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: .84rem; color: #fff;
    outline: none; width: 100%;
    transition: border-color .15s, background .15s, box-shadow .15s;
}
.f-input::placeholder { color: rgba(255,255,255,.2); }
.f-input:focus, .f-select:focus {
    border-color: #3b82f6;
    background: rgba(59,130,246,.06);
    box-shadow: 0 0 0 3px rgba(59,130,246,.12);
}
.f-input.err { border-color: rgba(220,38,38,.5); background: rgba(220,38,38,.05); }
.f-select option { background: #1f2937; color: #fff; }

.f-err { font-size: .68rem; color: #f87171; margin-top: .15rem; }

/* ── PASSWORD WRAPPER ── */
.pw-wrap { position: relative; }
.pw-wrap .f-input { padding-right: 2.5rem; }
.pw-toggle {
    position: absolute; right: .75rem; top: 50%;
    transform: translateY(-50%);
    background: none; border: none; cursor: pointer;
    color: rgba(255,255,255,.3); padding: 0;
    transition: color .15s;
}
.pw-toggle:hover { color: rgba(255,255,255,.7); }
.pw-toggle svg { width: 16px; height: 16px; display: block; }

/* ── STRENGTH BAR ── */
.strength-wrap { margin-top: .4rem; }
.strength-bar-bg {
    height: 3px; background: rgba(255,255,255,.08);
    border-radius: 2px; overflow: hidden; margin-bottom: .25rem;
}
.strength-bar-fill { height: 100%; border-radius: 2px; transition: width .3s, background .3s; }
.strength-label { font-size: .65rem; color: rgba(255,255,255,.35); }

/* ── RADIO PILLS ── */
.radio-row { display: flex; gap: .5rem; flex-wrap: wrap; }
.radio-pill {
    display: flex; align-items: center; gap: .35rem;
    padding: .5rem 1rem;
    background: rgba(255,255,255,.04);
    border: 1px solid rgba(255,255,255,.1);
    border-radius: 8px;
    font-size: .8rem; font-weight: 500; color: rgba(255,255,255,.5);
    cursor: pointer; transition: all .15s; user-select: none;
}
.radio-pill:hover { border-color: #3b82f6; color: rgba(255,255,255,.8); }
.radio-pill.on { border-color: #3b82f6; background: rgba(59,130,246,.12); color: #93c5fd; font-weight: 600; }
.radio-pill input { accent-color: #3b82f6; width: 13px; height: 13px; }

/* ── REMEMBER / TERMS ── */
.check-row {
    display: flex; align-items: flex-start; gap: .5rem;
    margin-bottom: 1.25rem;
}
.check-row input { accent-color: #3b82f6; width: 14px; height: 14px; flex-shrink: 0; margin-top: .15rem; }
.check-row label { font-size: .78rem; color: rgba(255,255,255,.4); line-height: 1.5; }
.check-row label a { color: #60a5fa; text-decoration: none; }
.check-row label a:hover { text-decoration: underline; }

/* ── DIVIDER ── */
.form-divider {
    border: none; border-top: 1px solid rgba(255,255,255,.06);
    margin: 1.25rem 0;
}

/* ── SUBMIT BUTTON ── */
.btn-submit {
    width: 100%; padding: .8rem;
    background: linear-gradient(135deg, #1d4ed8, #2563eb);
    border: none; border-radius: 10px;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: .875rem; font-weight: 700; color: #fff;
    cursor: pointer; transition: all .15s;
    display: flex; align-items: center; justify-content: center; gap: .5rem;
    box-shadow: 0 4px 20px rgba(29,78,216,.35);
}
.btn-submit:hover { background: linear-gradient(135deg, #1e40af, #1d4ed8); transform: translateY(-1px); box-shadow: 0 6px 24px rgba(29,78,216,.45); }
.btn-submit.green { background: linear-gradient(135deg, #059669, #10b981); box-shadow: 0 4px 20px rgba(16,185,129,.3); }
.btn-submit.green:hover { background: linear-gradient(135deg, #047857, #059669); box-shadow: 0 6px 24px rgba(16,185,129,.4); }
.btn-submit svg { width: 16px; height: 16px; }

/* ── SCROLL (register form) ── */
.scroll-form { max-height: 420px; overflow-y: auto; padding-right: .25rem; }
.scroll-form::-webkit-scrollbar { width: 3px; }
.scroll-form::-webkit-scrollbar-track { background: transparent; }
.scroll-form::-webkit-scrollbar-thumb { background: rgba(255,255,255,.1); border-radius: 2px; }

/* ── FORGOT LINK ── */
.forgot-link {
    display: block; text-align: right;
    font-size: .75rem; color: #60a5fa;
    text-decoration: none; margin-top: .35rem;
}
.forgot-link:hover { text-decoration: underline; }

/* ── RESPONSIVE ── */
@media (max-width: 768px) {
    .auth-left { display: none; }
    .auth-right { padding: 2rem 1.5rem; }
    .f-row { grid-template-columns: 1fr; }
    .auth-container { max-width: 460px; border-radius: 18px; }
}
</style>

<div class="bg-scene">
    <div class="bg-orb bg-orb-1"></div>
    <div class="bg-orb bg-orb-2"></div>
    <div class="bg-orb bg-orb-3"></div>
    <div class="bg-grid"></div>
</div>

<div class="auth-shell">
    <div class="auth-container">

        {{-- LEFT PANEL --}}
        <div class="auth-left">
            <div class="left-content">
                <div class="logo-ring">
                    <img src="{{ asset('images/logo.png') }}" alt="AFPPGMC">
                </div>
                <h1 class="left-title">
                    INVENTORY &amp; LOGISTICS<br>MANAGEMENT SYSTEM
                </h1>
                <p class="left-sub">
                    Armed Forces of the Philippines<br>
                    Pension &amp; Gratuity Management Center
                </p>
                <div class="left-pills">
                    <span class="left-pill">
                        <svg fill="currentColor" viewBox="0 0 20 20"><path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"/><path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8z" clip-rule="evenodd"/></svg>
                        Inventory
                    </span>
                    <span class="left-pill">
                        <svg fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5z" clip-rule="evenodd"/></svg>
                        Requests
                    </span>
                    <span class="left-pill">
                        <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/></svg>
                        Issuances
                    </span>
                    <span class="left-pill">
                        <svg fill="currentColor" viewBox="0 0 20 20"><path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zm6-4a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zm6-3a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/></svg>
                        Reports
                    </span>
                </div>
            </div>
        </div>

        {{-- RIGHT PANEL --}}
        <div class="auth-right">

            {{-- TABS --}}
            <div class="tab-bar">
                <button id="loginTab" class="tab-btn active" onclick="switchTab('login')">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                    </svg>
                    Sign In
                </button>
                <button id="registerTab" class="tab-btn" onclick="switchTab('register')">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"/>
                    </svg>
                    Register
                </button>
            </div>

            {{-- ══ LOGIN FORM ══ --}}
            <div id="loginPanel">
                <div class="form-head">
                    <h2>Welcome Back</h2>
                    <p>Sign in to your account to continue</p>
                </div>

                @if(session('status'))
                    <div class="alert-success">{{ session('status') }}</div>
                @endif

                @if($errors->any() && !old('first_name'))
                    <div class="alert-error">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                        </svg>
                        <div class="alert-error-text">
                            @foreach($errors->all() as $err)<div>{{ $err }}</div>@endforeach
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="f-group">
                        <label class="f-label">Email or Username</label>
                        <input class="f-input {{ $errors->has('login') && !old('first_name') ? 'err' : '' }}"
                               type="text" name="login"
                               value="{{ old('login') }}"
                               placeholder="Enter your email or username"
                               required autofocus autocomplete="username">
                    </div>
                    <div class="f-group">
                        <label class="f-label">Password</label>
                        <div class="pw-wrap">
                            <input class="f-input" type="password" name="password"
                                   placeholder="Enter your password"
                                   required autocomplete="current-password" id="loginPw">
                            <button type="button" class="pw-toggle" onclick="togglePw('loginPw', this)">
                                <svg fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>
                            </button>
                        </div>
                        <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
                    </div>
                    <div class="check-row">
                        <input type="checkbox" id="remember_me" name="remember">
                        <label for="remember_me">Keep me signed in</label>
                    </div>
                    <button type="submit" class="btn-submit">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14"/>
                        </svg>
                        Sign In
                    </button>
                </form>
            </div>

            {{-- ══ REGISTER FORM ══ --}}
            <div id="registerPanel" style="display:none;">
                <div class="form-head">
                    <h2>Create Account</h2>
                    <p>Join AFPPGMC Inventory System — pending admin approval</p>
                </div>

                @if($errors->any() && old('first_name'))
                    <div class="alert-error">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                        </svg>
                        <div class="alert-error-text">
                            @foreach($errors->all() as $err)<div>{{ $err }}</div>@endforeach
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="scroll-form">

                        {{-- Name --}}
                        <div class="f-row">
                            <div class="f-group">
                                <label class="f-label">First Name <span class="req">*</span></label>
                                <input class="f-input {{ $errors->has('first_name') ? 'err' : '' }}"
                                       type="text" name="first_name"
                                       value="{{ old('first_name') }}"
                                       placeholder="Juan" required>
                                @error('first_name')<p class="f-err">{{ $message }}</p>@enderror
                            </div>
                            <div class="f-group">
                                <label class="f-label">Last Name <span class="req">*</span></label>
                                <input class="f-input {{ $errors->has('last_name') ? 'err' : '' }}"
                                       type="text" name="last_name"
                                       value="{{ old('last_name') }}"
                                       placeholder="dela Cruz" required>
                                @error('last_name')<p class="f-err">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        <div class="f-row">
                            <div class="f-group">
                                <label class="f-label">Middle Name</label>
                                <input class="f-input" type="text" name="middle_name"
                                       value="{{ old('middle_name') }}" placeholder="Optional">
                            </div>
                            <div class="f-group">
                                <label class="f-label">Suffix</label>
                                <input class="f-input" type="text" name="suffix"
                                       value="{{ old('suffix') }}" placeholder="Jr., Sr.">
                            </div>
                        </div>

                        {{-- Sex --}}
                        <div class="f-group">
                            <label class="f-label">Gender <span class="req">*</span></label>
                            <div class="radio-row">
                                <label class="radio-pill {{ old('sex') === 'male' ? 'on' : '' }}">
                                    <input type="radio" name="sex" value="male" {{ old('sex') === 'male' ? 'checked' : '' }} onchange="togglePill(this)"> Male
                                </label>
                                <label class="radio-pill {{ old('sex') === 'female' ? 'on' : '' }}">
                                    <input type="radio" name="sex" value="female" {{ old('sex') === 'female' ? 'checked' : '' }} onchange="togglePill(this)"> Female
                                </label>
                            </div>
                            @error('sex')<p class="f-err">{{ $message }}</p>@enderror
                        </div>

                        {{-- Unit --}}
                        <div class="f-group">
                            <label class="f-label">Unit / Department <span class="req">*</span></label>
                            <select class="f-select {{ $errors->has('unit') ? 'err' : '' }}" name="unit" required>
                                <option value="" disabled {{ old('unit') ? '' : 'selected' }}>Select Unit</option>
                                @foreach(['BDCU','CUI','COMMAND','ISU','LSO','PAU','PG1','PG3','PG4','PG10','PPBU'] as $u)
                                    <option value="{{ $u }}" {{ old('unit') === $u ? 'selected' : '' }}>{{ $u }}</option>
                                @endforeach
                            </select>
                            @error('unit')<p class="f-err">{{ $message }}</p>@enderror
                        </div>

                        <hr class="form-divider">

                        {{-- Credentials --}}
                        <div class="f-row">
                            <div class="f-group">
                                <label class="f-label">Username <span class="req">*</span></label>
                                <input class="f-input {{ $errors->has('username') ? 'err' : '' }}"
                                       type="text" name="username"
                                       value="{{ old('username') }}"
                                       placeholder="juandelacruz" required>
                                @error('username')<p class="f-err">{{ $message }}</p>@enderror
                            </div>
                            <div class="f-group">
                                <label class="f-label">Email <span class="req">*</span></label>
                                <input class="f-input {{ $errors->has('email') ? 'err' : '' }}"
                                       type="email" name="email"
                                       value="{{ old('email') }}"
                                       placeholder="juan@pgmc.com" required>
                                @error('email')<p class="f-err">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        <div class="f-row">
                            <div class="f-group">
                                <label class="f-label">Password <span class="req">*</span></label>
                                <div class="pw-wrap">
                                    <input class="f-input {{ $errors->has('password') ? 'err' : '' }}"
                                           type="password" name="password"
                                           id="regPw" placeholder="Min. 8 chars" required
                                           autocomplete="new-password"
                                           oninput="checkStrength(this.value)">
                                    <button type="button" class="pw-toggle" onclick="togglePw('regPw', this)">
                                        <svg fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>
                                    </button>
                                </div>
                                <div class="strength-wrap" id="strengthWrap" style="display:none;">
                                    <div class="strength-bar-bg"><div class="strength-bar-fill" id="strengthFill"></div></div>
                                    <span class="strength-label" id="strengthLabel"></span>
                                </div>
                                @error('password')<p class="f-err">{{ $message }}</p>@enderror
                            </div>
                            <div class="f-group">
                                <label class="f-label">Confirm Password <span class="req">*</span></label>
                                <div class="pw-wrap">
                                    <input class="f-input" type="password" name="password_confirmation"
                                           id="regPwConf" placeholder="Repeat password" required
                                           autocomplete="new-password">
                                    <button type="button" class="pw-toggle" onclick="togglePw('regPwConf', this)">
                                        <svg fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="check-row">
                            <input type="checkbox" id="terms" name="terms" required>
                            <label for="terms">
                                I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a> *
                            </label>
                        </div>

                        <button type="submit" class="btn-submit green">
                            <svg fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Create Account
                        </button>

                    </div>
                </form>
            </div>

        </div>{{-- end auth-right --}}
    </div>
</div>

<script>
// ── TAB SWITCHING ──
function switchTab(tab) {
    const loginPanel    = document.getElementById('loginPanel');
    const registerPanel = document.getElementById('registerPanel');
    const loginTab      = document.getElementById('loginTab');
    const registerTab   = document.getElementById('registerTab');

    if (tab === 'login') {
        loginPanel.style.display    = '';
        registerPanel.style.display = 'none';
        loginTab.classList.add('active');
        registerTab.classList.remove('active');
    } else {
        loginPanel.style.display    = 'none';
        registerPanel.style.display = '';
        registerTab.classList.add('active');
        loginTab.classList.remove('active');
    }
}

// Auto-open register tab if there are register errors
@if($errors->any() && old('first_name'))
    document.addEventListener('DOMContentLoaded', () => switchTab('register'));
@endif

// ── PASSWORD TOGGLE ──
function togglePw(id, btn) {
    const input = document.getElementById(id);
    const isText = input.type === 'text';
    input.type = isText ? 'password' : 'text';
    btn.style.color = isText ? 'rgba(255,255,255,.3)' : 'rgba(255,255,255,.7)';
}

// ── RADIO PILLS ──
function togglePill(input) {
    document.querySelectorAll('.radio-pill').forEach(p => p.classList.remove('on'));
    input.closest('.radio-pill').classList.add('on');
}

// ── PASSWORD STRENGTH ──
function checkStrength(val) {
    const wrap  = document.getElementById('strengthWrap');
    const fill  = document.getElementById('strengthFill');
    const label = document.getElementById('strengthLabel');
    if (!val) { wrap.style.display = 'none'; return; }
    wrap.style.display = '';

    let s = 0;
    if (val.length >= 8) s++;
    if (/[a-z]/.test(val)) s++;
    if (/[A-Z]/.test(val)) s++;
    if (/[0-9]/.test(val)) s++;
    if (/[^A-Za-z0-9]/.test(val)) s++;

    const colors = ['#ef4444','#f97316','#eab308','#3b82f6','#10b981'];
    const labels = ['Very Weak','Weak','Fair','Good','Strong'];
    fill.style.width = (s / 5 * 100) + '%';
    fill.style.background = colors[s - 1] || '#ef4444';
    label.textContent = labels[s - 1] || 'Very Weak';
    label.style.color = colors[s - 1] || '#ef4444';
}
</script>

@endsection