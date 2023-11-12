<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string|null ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards): mixed
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if ($guard == 'admin' && Auth::guard($guard)->check()) {
                return redirect()->route('admin.dashboard');
            }

            if ($guard == 'web' && Auth::guard($guard)->check()) {
                return redirect()->route('user.dashboard');
            }
        }

        return $next($request);
    }
}
