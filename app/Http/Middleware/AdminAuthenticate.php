<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthenticate
{
    /**
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (!Auth::check()) {
            // For AJAX/JSON requests, return 401 instead of redirecting
            if ($request->expectsJson()) {
                return response('Unauthorized', 401);
            }
            return redirect()->route('admin.login');
        }

        return $next($request);
    }
}
