<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
    {
        \Log::info('Login attempt started', [
            'has_csrf_token' => $request->has('_token'),
            'csrf_token' => $request->input('_token'),
            'session_token' => csrf_token(),
            'session_id' => session()->getId(),
            'ip' => $request->ip(),
        ]);
        
        $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required'],
        ]);
        
        \Log::info('Login validation passed', [
            'login' => $request->login,
            'login_type' => filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username',
        ]);

        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'username';

        if (! Auth::attempt([
            $loginType => $request->login,
            'password' => $request->password,
        ], $request->boolean('remember'))) {
            
            \Log::warning('Login failed - invalid credentials', [
                'login' => $request->login,
                'login_type' => $loginType,
            ]);

            throw ValidationException::withMessages([
                'login' => __('auth.failed'),
            ]);
        }

        \Log::info('Login successful', [
            'user_id' => Auth::id(),
            'user_type' => Auth::user()->type,
            'user_email' => Auth::user()->email,
        ]);

        // ============================================
        // CHECK IF USER IS ACTIVE (NEW CODE)
        // ============================================
        $user = Auth::user();
        
        // Check if user account is inactive (email_verified_at is null)
        if (is_null($user->email_verified_at)) {
            \Log::warning('Login blocked - inactive account', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'user_type' => $user->type,
            ]);
            
            Auth::logout();
            
            throw ValidationException::withMessages([
                'login' => 'Your account is inactive. Please contact the administrator.',
            ]);
        }
        
        \Log::info('User account active - proceeding with login', [
            'user_id' => $user->id,
            'email_verified_at' => $user->email_verified_at,
        ]);
        // ============================================
        // END OF ACTIVE CHECK
        // ============================================

        $request->session()->regenerate();
        $request->session()->forget('url.intended');

        // Redirect based on user type
        if ($user->type === 'admin') {
            \Log::info('Redirecting to admin dashboard', ['user_id' => $user->id]);
            return redirect()->route('admin.dashboard');
        }

        \Log::info('Redirecting to user dashboard', ['user_id' => $user->id]);
        return redirect()->route('dashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        \Log::info('User logging out', [
            'user_id' => Auth::id(),
            'user_email' => Auth::user()?->email,
        ]);
        
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}