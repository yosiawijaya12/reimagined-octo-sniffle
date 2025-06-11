<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Check if the user is authenticated
        if (!Auth::check()) {
            return redirect('/login'); // Redirect to login if not authenticated
        }

        // Get the user's role
        $userRole = Auth::user()->role;

        // Check if the user's role is in the allowed roles
        if (!in_array($userRole, $roles)) {
            return redirect('/dashboard'); // Redirect to dashboard if role is not allowed
        }

        return $next($request); // Proceed to the next middleware or request
    }
}
