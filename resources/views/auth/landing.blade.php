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
.f-group { display: flex; flex-direction: column; gap: .3rem; margin-bottom: 1.25rem; }
.f-group:last-child { margin-bottom: 0; }

.f-label {
    font-size: .7rem; font-weight: 700;
    letter-spacing: .07em; text-transform: uppercase;
    color: rgba(255,255,255,.45);
}

.f-input {
    padding: .75rem 1rem;
    background: rgba(255,255,255,.05);
    border: 1px solid rgba(255,255,255,.1);
    border-radius: 9px;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: .9rem; color: #fff;
    outline: none; width: 100%;
    transition: border-color .15s, background .15s, box-shadow .15s;
}
.f-input::placeholder { color: rgba(255,255,255,.2); }
.f-input:focus {
    border-color: #3b82f6;
    background: rgba(59,130,246,.06);
    box-shadow: 0 0 0 3px rgba(59,130,246,.12);
}
.f-input.err { border-color: rgba(220,38,38,.5); background: rgba(220,38,38,.05); }

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
.pw-toggle svg { width: 18px; height: 18px; display: block; }

/* ── REMEMBER CHECKBOX ── */
.check-row {
    display: flex; align-items: center; gap: .5rem;
    margin: 1.25rem 0 1.5rem;
}
.check-row input { 
    accent-color: #3b82f6; 
    width: 16px; 
    height: 16px; 
    cursor: pointer;
}
.check-row label { 
    font-size: .85rem; 
    color: rgba(255,255,255,.5); 
    cursor: pointer;
}

/* ── FORGOT LINK ── */
.forgot-link {
    display: block; text-align: right;
    font-size: .8rem; color: #60a5fa;
    text-decoration: none; margin-top: .5rem;
}
.forgot-link:hover { text-decoration: underline; }

/* ── SUBMIT BUTTON ── */
.btn-submit {
    width: 100%; padding: .9rem;
    background: linear-gradient(135deg, #1d4ed8, #2563eb);
    border: none; border-radius: 10px;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: .95rem; font-weight: 700; color: #fff;
    cursor: pointer; transition: all .15s;
    display: flex; align-items: center; justify-content: center; gap: .5rem;
    box-shadow: 0 4px 20px rgba(29,78,216,.35);
}
.btn-submit:hover { 
    background: linear-gradient(135deg, #1e40af, #1d4ed8); 
    transform: translateY(-1px); 
    box-shadow: 0 6px 24px rgba(29,78,216,.45); 
}
.btn-submit svg { width: 18px; height: 18px; }

/* ── INFO MESSAGE FOR UNAUTHORIZED ACCESS ── */
.info-message {
    text-align: center;
    margin-top: 1.5rem;
    padding: 1rem;
    background: rgba(59,130,246,.08);
    border: 1px solid rgba(59,130,246,.15);
    border-radius: 8px;
    font-size: .8rem;
    color: rgba(255,255,255,.5);
}
.info-message a {
    color: #60a5fa;
    text-decoration: none;
    font-weight: 600;
}
.info-message a:hover {
    text-decoration: underline;
}

/* ── RESPONSIVE ── */
@media (max-width: 768px) {
    .auth-left { display: none; }
    .auth-right { padding: 2rem 1.5rem; }
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

        {{-- RIGHT PANEL - LOGIN ONLY --}}
        <div class="auth-right">
            <div class="form-head">
                <h2>Welcome Back</h2>
                <p>Sign in to your account to access the system</p>
            </div>

            @if(session('status'))
                <div class="alert-success">{{ session('status') }}</div>
            @endif

            @if($errors->any())
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
                    <input class="f-input {{ $errors->has('login') ? 'err' : '' }}"
                           type="text" name="login"
                           value="{{ old('login') }}"
                           placeholder="Enter your email or username"
                           required autofocus autocomplete="username">
                </div>
                
                <div class="f-group">
                    <label class="f-label">Password</label>
                    <div class="pw-wrap">
                        <input class="f-input {{ $errors->has('password') ? 'err' : '' }}" 
                               type="password" name="password"
                               placeholder="Enter your password"
                               required autocomplete="current-password" id="loginPw">
                        <button type="button" class="pw-toggle" onclick="togglePw('loginPw', this)">
                            <svg fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                            </svg>
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

            {{-- Optional: Add a message about account creation --}}
            <div class="info-message">
                <svg fill="currentColor" viewBox="0 0 20 20" width="14" height="14" style="display: inline; margin-right: 4px;">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                Accounts are created by system administrators. 
                <a href="#">Contact support</a> for assistance.
            </div>
        </div>
    </div>
</div>

<script>
// ── PASSWORD TOGGLE ──
function togglePw(id, btn) {
    const input = document.getElementById(id);
    const isText = input.type === 'text';
    input.type = isText ? 'password' : 'text';
    btn.style.color = isText ? 'rgba(255,255,255,.3)' : 'rgba(255,255,255,.7)';
}
</script>

@endsection