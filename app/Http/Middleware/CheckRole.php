<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        $user = auth()->user();
        $roles = func_get_args();
        array_shift($roles); // Remove $request
        array_shift($roles); // Remove $next

        foreach ($roles as $requiredRole) {
            if ($user->role === $requiredRole) {
                return $next($request);
            }
        }

        abort(403, 'Akses ditolak');
    }
}
