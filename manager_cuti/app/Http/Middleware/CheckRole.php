<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();

        // If no specific roles are required, allow access
        if (empty($roles)) {
            return $next($request);
        }

        // Check if user's role is in the allowed roles
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // Redirect based on user's role if they don't have access
        return match ($user->role) {
            'admin' => redirect('/admin/dashboard')->with('error', 'Access denied.'),
            'hrd' => redirect('/hrd/dashboard')->with('error', 'Access denied.'),
            'division_leader' => redirect('/leader/dashboard')->with('error', 'Access denied.'),
            'user' => redirect('/dashboard')->with('error', 'Access denied.'),
            default => redirect('/dashboard')->with('error', 'Access denied.'),
        };
    }
}
