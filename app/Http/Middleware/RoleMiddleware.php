<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(403);
        }

        // Super-admin bypasses all role checks
        if (method_exists($user, 'hasRole') && $user->hasRole('super-admin')) {
            return $next($request);
        }

        $allowedRoles = array_filter(array_map('trim', explode('|', $roles)));

        if (empty($allowedRoles) || !method_exists($user, 'hasRole') || !$user->hasRole($allowedRoles)) {
            abort(403);
        }

        return $next($request);
    }
}
