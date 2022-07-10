<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAuthUserGuard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $guard)
    {
        if (is_null(auth()->user()->roles->first()) || auth()->user()->roles->first()->guard_name !== $guard) {
            auth()->logout();
            throw new \Spatie\Permission\Exceptions\UnauthorizedException(403, 'You do not have the required authorization.');
        }
        return $next($request);
    }
}
