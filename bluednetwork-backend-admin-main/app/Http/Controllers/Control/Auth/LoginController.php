<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           LoginController.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     22/08/2021, 1:45 PM
 */

namespace App\Http\Controllers\Control\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Abstracts\Http\Controllers\ControlController;

class LoginController extends ControlController
{
    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function loginForm(Request $request): View|Factory|Application
    {
        return view($this->_config['view']);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function processLogin(Request $request): RedirectResponse
    {
        $this->ensureIsNotRateLimited($request);
        $rules = [
            'identity' => 'required',
            'password' => 'required',
        ];
        $rulesMessage = [
            'identity.required' => 'Email required',
            'password' => 'Password is required'
        ];
        $request->validate($rules, $rulesMessage);
        if (!auth('dashboard')->attempt($this->credentials($request))) {
            RateLimiter::hit($this->throttleKey($request));
            flash('Login details are invalid')->error();
            return redirect()->route('control.login.form');
        }
        if (auth('dashboard')?->user()?->roles->first()->guard_name !== 'dashboard') {
            auth('dashboard')->logout();
            flash('You do not have the required authorization.')->error();
            return redirect()->route('control.login.form');
        }
        RateLimiter::clear($this->throttleKey($request));
        return redirect()->route('control.dashboard');
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param Request $request
     * @return array
     */
    protected function credentials(Request $request): array
    {
        return $request->only($this->identity($request), 'password');
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @param Request $request
     * @return string
     */
    protected function identity(Request $request): string
    {
        $login = $request->identity;

        $field = 'email';

        $request->merge(['email' => $login]);

        return $field;
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @param Request $request
     * @return void
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(Request $request)
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey($request), 6)) {
            return;
        }

        event(new Lockout($request));

        $seconds = RateLimiter::availableIn($this->throttleKey($request));

        throw ValidationException::withMessages([
            'identity' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @param Request $request
     * @return string
     */
    protected function throttleKey(Request $request): string
    {
        return Str::lower($this->identity($request)) . '|' . $request->ip();
    }
}
