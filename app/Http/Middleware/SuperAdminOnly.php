<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class SuperAdminOnly
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Block PG4 unit admins from accessing this route
        if ($user->type === 'admin' && strtoupper($user->unit) === 'PG4') {
            abort(403, 'Access restricted for PG4 unit accounts.');
        }

        return $next($request);
    }
}