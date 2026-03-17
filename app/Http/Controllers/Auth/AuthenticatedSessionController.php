<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        // Updated to point to the landing page which now contains the login form
        return view('auth.landing');
    }

    public function store(Request $request): RedirectResponse
    {
        \Log::info('Login attempt started', [
            'has_csrf_token' => $request->has('_token'),
            'csrf_token'     => $request->input('_token'),
            'session_token'  => csrf_token(),
            'session_id'     => session()->getId(),
            'ip'             => $request->ip(),
        ]);

        $request->validate([
            'login'    => ['required', 'string'],
            'password' => ['required'],
        ]);

        \Log::info('Login validation passed', [
            'login'      => $request->login,
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
                'login'      => $request->login,
                'login_type' => $loginType,
            ]);

            throw ValidationException::withMessages([
                'login' => __('auth.failed'),
            ]);
        }

        \Log::info('Login successful', [
            'user_id'    => Auth::id(),
            'user_type'  => Auth::user()->type,
            'user_email' => Auth::user()->email,
        ]);

        // ============================================
        // CHECK IF USER IS ACTIVE
        // ============================================
        $user = Auth::user();

        if (is_null($user->email_verified_at)) {
            \Log::warning('Login blocked - inactive account', [
                'user_id'    => $user->id,
                'user_email' => $user->email,
                'user_type'  => $user->type,
            ]);

            Auth::logout();

            throw ValidationException::withMessages([
                'login' => 'Your account is inactive. Please contact the administrator.',
            ]);
        }

        \Log::info('User account active - proceeding with login', [
            'user_id'           => $user->id,
            'email_verified_at' => $user->email_verified_at,
        ]);
        // ============================================
        // END OF ACTIVE CHECK
        // ============================================

        $request->session()->regenerate();
        $request->session()->forget('url.intended');

        // ============================================
        // RESTORE CART FROM DATABASE AFTER LOGIN
        // ============================================
        $this->restoreCartFromDatabase();
        // ============================================

        if ($user->type === 'admin') {
            \Log::info('Redirecting to admin dashboard', ['user_id' => $user->id]);
            return redirect()->route('admin.dashboard');
        }

        \Log::info('Redirecting to user dashboard', ['user_id' => $user->id]);
        return redirect()->route('dashboard');
    }

    public function destroy(Request $request): RedirectResponse
    {
        \Log::info('User logging out', [
            'user_id'    => Auth::id(),
            'user_email' => Auth::user()?->email,
        ]);

        // ============================================
        // SAVE CART TO DATABASE BEFORE LOGOUT
        // ============================================
        $this->saveCartToDatabase();
        // ============================================

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    // ============================================
    // CART HELPER METHODS
    // ============================================

    private function saveCartToDatabase(): void
    {
        if (! auth()->check()) return;

        $cart = session('cart', []);
        if (empty($cart)) return;

        $userId = auth()->id();

        // Wipe old saved cart and replace with current session cart
        CartItem::where('user_id', $userId)->delete();

        foreach ($cart as $itemId => $data) {
            CartItem::create([
                'user_id'  => $userId,
                'item_id'  => $itemId,
                'quantity' => $data['quantity'] ?? 1,
                'notes'    => $data['notes'] ?? null,
            ]);
        }

        \Log::info('Cart saved to DB on logout', [
            'user_id'     => $userId,
            'items_saved' => count($cart),
        ]);
    }

    private function restoreCartFromDatabase(): void
    {
        $userId = auth()->id();
        $dbCart = CartItem::with('item')->where('user_id', $userId)->get();

        if ($dbCart->isEmpty()) return;

        $sessionCart = [];

        foreach ($dbCart as $cartItem) {
            // Skip if the inventory item was deleted
            if (! $cartItem->item) continue;

            $sessionCart[$cartItem->item_id] = [
                'item'     => $cartItem->item,
                'quantity' => $cartItem->quantity,
                'notes'    => $cartItem->notes ?? '',
            ];
        }

        session(['cart' => $sessionCart]);

        \Log::info('Cart restored from DB on login', [
            'user_id'        => $userId,
            'items_restored' => count($sessionCart),
        ]);
    }
}