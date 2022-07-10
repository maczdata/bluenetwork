<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           RetrievePasswordController.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     20/08/2021, 7:23 PM
 */

namespace App\Http\Controllers\Control\Auth;

use App\Abstracts\Http\Controllers\FrontController;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

/**
 * Class RetrievePasswordController
 * @package App\Http\Controllers\Front\Auth
 */
class RetrievePasswordController extends FrontController
{
    /**
     * @return Application|Factory|View
     */
    public function requestForm()
    {
        return view($this->_config['view']);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function processRequest(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'email' => 'required|email',
            ]);
            $response = $this->broker()->sendResetLink(
                $request->only(['email'])
            );
            //dd($response);
            if ($response == Password::RESET_LINK_SENT) {
                flash(trans($response))->success();

                return back();
            }

            flash('User not found')->error();
            return back();
            /* return back()
                 ->withInput(request(['email']))
                 ->withErrors([
                     'email' => trans($response),
                 ]);*/
        } catch (\Swift_RfcComplianceException $e) {
            flash('We have e-mailed your reset password link.')->success();
            return redirect()->back();
        } catch (\Exception $e) {
            logger()->error($e);
            flash(trans($e->getMessage()))->error();

            return redirect()->back();
        }
    }

    /**
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function resetForm(Request $request)
    {
        $token = $request->token;
        $email = $request->email;
        return view($this->_config['view'], compact('token', 'email'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function processReset(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ]);
        DB::beginTransaction();
        try {
            $response = $this->broker()->reset(
                request(['email', 'password', 'password_confirmation', 'token']),
                function ($user, $password) {
                    $this->resetPassword($user, $password);
                }
            );

            if ($response == Password::PASSWORD_RESET) {
                DB::commit();
                flash('Password reset successful, you can now login')->success();
                return redirect()->route($this->_config['redirect']);
            }

            return back()
                ->withInput(request(['email']))
                ->withErrors([
                    'email' => trans($response),
                ]);
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error($e);
            flash(trans($e->getMessage()))->error();

            return redirect()->back();
        }
    }


    /**
     * Reset the given customer password.
     *
     * @param CanResetPassword $user
     * @param string $password
     * @return void
     */
    protected function resetPassword(CanResetPassword $user, string $password)
    {
        $user->password = Hash::make($password);
        $user->setRememberToken(Str::random(60));
        $user->save();
        event(new PasswordReset($user));
        auth()->guard('frontend')->login($user);
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return PasswordBroker
     */
    public function broker(): PasswordBroker
    {
        return Password::broker('admin');
    }
}
