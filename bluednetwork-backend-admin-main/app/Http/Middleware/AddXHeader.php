<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           AddXHeader.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Http\Middleware;

use Closure;

class AddXHeader
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Check if we should add header
        if (method_exists($response, 'header')) {
            $response->header('X-ChistelBrown', 'Crafted and Developed by Chistel Brown(chistlelbrown@gmail.com)');
        }

        return $response;
    }
}
