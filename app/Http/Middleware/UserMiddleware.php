<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class UserMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $user = Auth::user();
        
        // Allow access if user type is 'user' or not set
        if ($user->type === 'user' || empty($user->type)) {
            return $next($request);
        }
        
        // If admin tries to access user routes, redirect to admin dashboard
        if ($user->type === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        
        abort(403, 'Access denied. User privileges required.');
    }
}