<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    public function handle(Request $request, Closure $next, string $permissions): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(403);
        }

        // Super-admin bypasses all permission checks
        if (method_exists($user, 'hasRole') && $user->hasRole('super-admin')) {
            return $next($request);
        }

        $required = array_filter(array_map('trim', explode('|', $permissions)));
        $userPermissions = collect($user->permissions ?? [])->map(fn ($permission) => strtolower((string) $permission));

        $hasPermission = collect($required)
            ->map(fn ($permission) => strtolower((string) $permission))
            ->contains(fn ($permission) => $userPermissions->contains($permission));

        if (!$hasPermission) {
            abort(403);
        }

        return $next($request);
    }
}
