<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        // Check if the user is authenticated for any of the given guards
        if (Auth::guard(array_shift($guards))->check()) {
            return $next($request);
        }
        
        // For AJAX or API request, return unauthorized JSON response
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }
        
        // Redirect to login page if not authenticated
        return redirect()->route('login');
    }
}

