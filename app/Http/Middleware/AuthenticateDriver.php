<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthenticateDriver
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('driver_authenticated_id')) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }
            return redirect()->route('driver.login')->with('error', 'Veuillez vous connecter pour accéder à l\'interface chauffeur.');
        }

        return $next($request);
    }
}
