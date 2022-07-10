<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           AuthenticatedController.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     20/08/2021, 7:33 PM
 */

namespace App\Http\Controllers\Control\Auth;

use App\Abstracts\Http\Controllers\ControlController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class AuthenticatedController
 * @package App\Http\Controllers\Front\Auth
 */
class AuthenticatedController extends ControlController
{
    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        auth('dashboard')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('control.login.form');
    }
}
