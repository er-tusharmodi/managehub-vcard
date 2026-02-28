<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleOrPermissionMiddleware
{
    public function handle(Request $request, Closure $next, string $value): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(403);
        }

        // Super-admin bypasses all role and permission checks
        if (method_exists($user, 'hasRole') && $user->hasRole('super-admin')) {
            return $next($request);
        }

        $parts = array_filter(array_map('trim', explode('|', $value)));
        $roles = [];
        $permissions = [];

        foreach ($parts as $part) {
            if (str_starts_with($part, 'role:')) {
                $roles[] = substr($part, 5);
                continue;
            }

            if (str_starts_with($part, 'permission:')) {
                $permissions[] = substr($part, 11);
                continue;
            }

            $roles[] = $part;
            $permissions[] = $part;
        }

        $hasRole = method_exists($user, 'hasRole') && !empty($roles) && $user->hasRole($roles);
        $userPermissions = collect($user->permissions ?? [])->map(fn ($permission) => strtolower((string) $permission));
        $hasPermission = collect($permissions)
            ->map(fn ($permission) => strtolower((string) $permission))
            ->contains(fn ($permission) => $userPermissions->contains($permission));

        if (!$hasRole && !$hasPermission) {
            abort(403);
        }

        return $next($request);
    }
}
