<?php

namespace App\Http\Middleware;

use App\Models\Vcard;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureActiveSubscription
{
    public function handle(Request $request, Closure $next): mixed
    {
        $user = Auth::user();

        if (!$user) {
            return $next($request);
        }

        if (method_exists($user, 'hasRole') && ($user->hasRole('admin') || $user->hasRole('super-admin'))) {
            return $next($request);
        }

        Vcard::where('user_id', $user->id)
            ->where('subscription_status', 'active')
            ->whereNotNull('subscription_expires_at')
            ->where('subscription_expires_at', '<', now())
            ->update(['subscription_status' => 'inactive']);

        $hasActive = Vcard::where('user_id', $user->id)
            ->where('subscription_status', 'active')
            ->where(function ($query) {
                $query->whereNull('subscription_started_at')
                    ->orWhere('subscription_started_at', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('subscription_expires_at')
                    ->orWhere('subscription_expires_at', '>=', now());
            })
            ->exists();

        if (!$hasActive) {
            Auth::logout();
            return redirect()->route('subscription.inactive');
        }

        return $next($request);
    }
}
