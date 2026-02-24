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
            \Log::warning('Unauthorized access blocked by CheckRole', [
                'user_id' => $user->id ?? null,
                'role' => $user->role->name ?? null,
                'required_roles' => $roles,
                'path' => $request->path(),
            ]);

            // Redirect to dashboard with friendly message instead of generic 403 page
            return redirect()->route('dashboard')->with('error', 'Akses ditolak. Anda tidak memiliki izin untuk halaman ini.');
        }

        return $next($request);
    }
}
