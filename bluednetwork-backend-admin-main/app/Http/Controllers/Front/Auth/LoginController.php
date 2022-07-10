<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           LoginController.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 2:40 PM
 */

namespace App\Http\Controllers\Front\Auth;

use App\Abstracts\Http\Controllers\FrontController;
use App\Services\Authorization;
use App\Services\DataSerializer;
use App\Transformers\Users\AuthTransformer;
use Exception;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * Class LoginController
 * @package App\Http\Controllers\Front\Auth
 */
class LoginController extends FrontController
{
    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */

    /**
     * @OA\Post(
     ** path="/auth/login",
     *   tags={"Authentication"},
     *   summary="Login",
     *   operationId="login",
     *
     *   @OA\Parameter(
     *      name="identity",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="string"
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
     *       description="Unauthenticated",
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
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function processLogin(Request $request): JsonResponse
    {
        $jwtToken = null;
        $this->ensureIsNotRateLimited($request);
        $rules = [
            'identity' => 'required',
            'password' => 'required',
        ];
        $rulesMessage = [
            'identity.required' => 'Email or phone number required',
            'password' => 'Password is required'
        ];
        $request->validate($rules, $rulesMessage);
        if (!$jwtToken = auth('frontend')->attempt($this->credentials($request))) {
            RateLimiter::hit($this->throttleKey($request));
            return api()->status(401)->message('Invalid login details')->respond();
        }
        $user = auth('frontend')->user();
        if ($user?->roles->first()->guard_name !== 'frontend') {
            auth('frontend')->logout();
            return api()->status(403)->message('You do not have the required authorization.')->respond();
        }
        RateLimiter::clear($this->throttleKey($request));
        try {
            $authorization = new Authorization('frontend', $jwtToken);
            return api()->status(200)->message('successfully logged in')->data(fractal(
                $user,
                new AuthTransformer($authorization)
            )->toArray())->respond();
        } catch (Exception $exception) {
            logger()->error('user login error : ' . $exception);
            return api()->status(400)->message('an issue occurred')->respond();
        }
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

        if (is_numeric($login)) {
            $field = 'phone';
        } elseif (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $field = 'email';
        } else {
            $field = 'username';
        }

        $request->merge([$field => $login]);

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
