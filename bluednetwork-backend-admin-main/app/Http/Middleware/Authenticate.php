<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           Authenticate.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     21/08/2021, 9:22 AM
 */

namespace App\Http\Middleware;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{

    protected function unauthenticated($request, array $guards)
    {
        throw new AuthenticationException(
            'Unauthenticated.',
            $guards,
            $this->ownRedirectTo($request, $guards)
        );
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param $request
     * @param $guards
     * @return string|null
     */
    protected function ownRedirectTo($request)
    {
        if (!$request->expectsJson()) {
            if (!auth('dashboard')->check()) {
                return route('control.login.form', ['redirect_to' => request()->fullUrl()]);
            }
        }
    }
}
