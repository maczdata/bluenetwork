<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           JwtAuthenticated.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     21/08/2021, 1:17 AM
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtAuthenticated extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param $request
     * @param Closure $next
     * @param string $guard
     * @return JsonResponse|mixed
     */
    public function handle($request, Closure $next, string $guard)
    {
        $this->checkForToken($request);

        auth()->shouldUse($guard);
        try {
            $this->auth->parseToken()->authenticate();
        } catch (\Exception $e) {
            $message = 'No auth token found';
            if ($e instanceof TokenInvalidException) {
                $message = 'Invalid auth token';
            } else if ($e instanceof TokenExpiredException) {
                $message = 'Auth token expired';
            }
            return api()->status(401)->message($message)->respond();
        }

        return $next($request);
    }
}
