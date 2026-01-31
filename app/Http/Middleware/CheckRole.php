<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect('login');
        }

        $user = auth()->user();
        
        // Eager load role to prevent multiple database queries
        if (!$user->relationLoaded('role')) {
            $user->load('role');
        }
        
        if (!$user->role || !in_array($user->role->name, $roles)) {
            abort(403, 'Akses ditolak.');
        }

        return $next($request);
    }
}
