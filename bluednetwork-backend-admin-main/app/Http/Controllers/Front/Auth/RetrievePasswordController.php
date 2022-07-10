<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           RetrievePasswordController.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 2:40 PM
 */

namespace App\Http\Controllers\Front\Auth;

use App\Abstracts\Http\Controllers\FrontController;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
     * @OA\Post(
     ** path="/password/reset_request",
     *  tags={"Password Reset"},
     *   summary="Reset request",
     *   operationId="Reset",
     *
     *   @OA\Parameter(
     *      name="email",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     * @param Request $request
     * @return JsonResponse
     */
    public function processRequest(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);
        if ($validator->fails()) {
            return response()->json($this->failedValidation($validator), 422);
        }
        try {

            $response = $this->broker()->sendResetLink(
                $request->only(['email'])
            );

            if ($response == Password::RESET_LINK_SENT) {
                return api()->status(200)->message(trans($response))->respond();
            }
        } catch (\Swift_RfcComplianceException $e) {
            return api()->status(200)->message('We have e-mailed your reset password link.')->respond();
        } catch (\Exception $e) {
            logger()->error('user password reswet request exception : ' . $e);
            return api()->status(500)->message('There was a technical error making request, please contact us')->respond();
        }
    }


    /**
     * @OA\Post(
     *      path="/password/reset_save",
     *      operationId="Reset-user-password",
     *      tags={"Password Reset"},
     *      summary="Reset user password",
     *       @OA\Parameter(
     *          name="email",
     *          description="enter email address",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *       @OA\Parameter(
     *          name="password",
     *          description="enter password",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="password_confirmation",
     *          description="enter confirmation password",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *
     *     @OA\Parameter(
     *          name="token",
     *          description="enter token key",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Response(response=200,description="successfully reset"),
     *       @OA\Response(response=500, description="Bad request"),
     *     )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function processReset(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json($this->failedValidation($validator), 422);
        }
        try {

            $response = $this->broker()->reset(
                request(['email', 'password', 'password_confirmation', 'token']),
                function ($user, $password) {
                    $this->resetPassword($user, $password);
                }
            );


            if ($response == Password::PASSWORD_RESET)
                return api()->status(200)->message(trans($response))->respond();
        } catch (\Exception $e) {
            logger()->error('User password save exception : ' . $e);
            return api()->status(500)->message($e->getMessage())->respond();
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

        //auth()->guard('user')->login($user);
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return PasswordBroker
     */
    public function broker()
    {
        return Password::broker('users');
    }
}
