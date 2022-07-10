<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           RegisterController.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     20/08/2021, 7:22 PM
 */

namespace App\Http\Controllers\Control\Auth;

use App\Abstracts\Http\Controllers\FrontController;
use App\Repositories\Common\TokenRepository;
use App\Repositories\Users\UserRepository;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\Factory;
use Illuminate\View\View;

/**
 * Class RegisterController
 * @package App\Http\Controllers\Front\Auth
 */
class RegisterController extends FrontController
{
    /**
     * RegisterController constructor.
     * @param UserRepository $userRepository
     * @param TokenRepository $tokenRepository
     */
    public function __construct(protected UserRepository $userRepository, protected TokenRepository $tokenRepository)
    {
        parent::__construct();
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function registerForm(Request $request): Application|Factory|View
    {
        return view($this->_config['view']);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function processRegistration(Request $request): RedirectResponse
    {
        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
        ];
        if (!array_key_exists('provider', $request->toArray()) && !array_key_exists('provider_id', $request->toArray())) {
            $rules['password'] = 'required|confirmed|min:6';
        }
        $rulesMessage = [
            'password.required' => 'Password is required',
            'password.min' => 'Password has to e a minimum of 6 characters',
        ];

        $rulesMessage['last_name.required'] = 'Last name required';
        $rulesMessage['username.required'] = 'Username is required';
        $rulesMessage['username.unique'] = 'Username is already taken';
        $rulesMessage['email.email'] = 'Email address is invalid';
        $rulesMessage['email.unique'] = 'Email address not available';
        $rulesMessage['phone_number.required'] = 'Phone number is required';
        $rulesMessage['phone_number.unique'] = 'Phone number already in use';

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()
                ->route('portal.register.form')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $user = $this->userRepository->addUser($request);
            $user->assignRole('user');
            event(new Registered($user));
            auth('frontend')->login($user);
            flash('Registration was successful')->success();
            /* flash('Registration was successful, please activate your account to have access to all features')->success();*/
            return redirect()->route('portal.account.dashboard');
        } catch (\Exception $exception) {
            //dd($exception);
            logger()->error('User reg error : ' . $exception);
            flash('Registration was not successful, please try again')->error()->important();
            return redirect()->route('portal.register.form')->withInput();
        }
    }
}
