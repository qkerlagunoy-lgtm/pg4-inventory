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

        $request->session()->regenerate();
        $request->session()->forget('url.intended');

        $user = Auth::user();

        if ($user->type === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('dashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}