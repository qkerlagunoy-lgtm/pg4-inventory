<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Pending - AFPPGMC</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #4e5d82 0%, #6E7DA2 50%, #7bbfc3 100%);
            display: flex; align-items: center; justify-content: center;
            padding: 1.5rem;
        }
        .card {
            background: #fff; border-radius: 24px;
            padding: 3rem 2.5rem; max-width: 480px; width: 100%;
            text-align: center;
            box-shadow: 0 24px 64px rgba(0,0,0,.18);
        }
        .icon-wrap {
            width: 80px; height: 80px; border-radius: 50%;
            background: linear-gradient(135deg, #AEDADD, #7bbfc3);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1.5rem;
            box-shadow: 0 8px 24px rgba(174,218,221,.4);
        }
        .icon-wrap svg { width: 36px; height: 36px; color: #fff; }
        h1 {
            font-size: 1.5rem; font-weight: 700;
            color: #1e2535; margin-bottom: .6rem;
        }
        .subtitle {
            font-size: .875rem; color: #6b7280;
            line-height: 1.7; margin-bottom: 2rem;
        }
        .steps {
            background: #FCF8F3; border-radius: 14px;
            padding: 1.25rem 1.5rem; margin-bottom: 2rem;
            text-align: left;
        }
        .steps-title {
            font-size: .72rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: .08em;
            color: #9ca3af; margin-bottom: .85rem;
        }
        .step {
            display: flex; align-items: flex-start; gap: .75rem;
            margin-bottom: .75rem;
        }
        .step:last-child { margin-bottom: 0; }
        .step-num {
            width: 22px; height: 22px; border-radius: 50%;
            background: #6E7DA2; color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-size: .65rem; font-weight: 700; flex-shrink: 0; margin-top: .1rem;
        }
        .step-num.done { background: #4a8c4a; }
        .step-text { font-size: .82rem; color: #374151; line-height: 1.5; }
        .step-text strong { color: #1e2535; }

        .alert-box {
            background: rgba(174,218,221,.15);
            border: 1px solid rgba(174,218,221,.4);
            border-radius: 10px; padding: .85rem 1rem;
            font-size: .8rem; color: #4a6b8a;
            margin-bottom: 1.75rem;
            display: flex; align-items: center; gap: .6rem;
            text-align: left;
        }
        .alert-box svg { width: 16px; height: 16px; flex-shrink: 0; color: #7bbfc3; }

        .btn-login {
            display: inline-flex; align-items: center; gap: .5rem;
            background: #6E7DA2; color: #fff;
            padding: .75rem 2rem; border-radius: 10px;
            font-size: .875rem; font-weight: 600;
            text-decoration: none;
            transition: all .15s;
            box-shadow: 0 4px 14px rgba(110,125,162,.3);
        }
        .btn-login:hover { background: #4e5d82; transform: translateY(-1px); }
        .btn-login svg { width: 15px; height: 15px; }
        .footer-note {
            font-size: .75rem; color: #9ca3af; margin-top: 1.25rem;
        }
    </style>
</head>
<body>
    <div class="card">

        <div class="icon-wrap">
            <svg fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>

        <h1>Registration Submitted!</h1>
        <p class="subtitle">
            Your account has been created and is now <strong>pending admin approval</strong>.
            You will be able to log in once an administrator activates your account.
        </p>

        <div class="steps">
            <div class="steps-title">What happens next?</div>
            <div class="step">
                <div class="step-num done">✓</div>
                <div class="step-text"><strong>Account created</strong> — Your registration has been received.</div>
            </div>
            <div class="step">
                <div class="step-num">2</div>
                <div class="step-text"><strong>Admin review</strong> — An administrator will review and activate your account.</div>
            </div>
            <div class="step">
                <div class="step-num">3</div>
                <div class="step-text"><strong>Access granted</strong> — Once approved, you can log in and start requesting items.</div>
            </div>
        </div>

        <div class="alert-box">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Contact your unit administrator if your account is not activated within 24 hours.
        </div>

      <a href="/" class="btn-login">
    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
    </svg>
    Back to Login
</a>

        <p class="footer-note">AFPPGMC Logistics Division &mdash; {{ date('Y') }}</p>
    </div>
</body>
</html>