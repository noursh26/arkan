<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Check if user is an admin (has is_admin flag or role)
        $user = auth()->user();
        
        // Check if user has admin role or is_admin flag
        $isAdmin = $user->is_admin ?? false;
        $isSuperAdmin = ($user->role ?? null) === 'super_admin';
        $isEditor = ($user->role ?? null) === 'editor';
        
        if (!$isAdmin && !$isSuperAdmin && !$isEditor) {
            abort(403, 'غير مصرح لك بالوصول إلى لوحة التحكم');
        }

        return $next($request);
    }
}
