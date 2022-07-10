<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           RegisterController.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     24/08/2021, 4:54 PM
 */

namespace App\Http\Controllers\Front\Auth;

use App\Abstracts\Http\Controllers\FrontController;
use App\Http\Requests\Front\RegisterRequest;
use App\Repositories\Common\TokenRepository;
use App\Repositories\Users\UserRepository;
use App\Services\Authorization;
use App\Services\Response\Builder;
use App\Traits\Common\FormError;
use App\Transformers\Users\AuthTransformer;
use App\Transformers\Users\UserTransformer;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Propaganistas\LaravelPhone\Exceptions\CountryCodeException;

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

        $this->tokenRepository = $tokenRepository;

        $this->userRepository = $userRepository;
    }

    /**
     * @OA\Post(
     *      path="/register",
     *      operationId="register-user",
     *      tags={"Registration"},
     *      summary="sign up as a user",
     *    @OA\Parameter(
     *          name="first_name",
     *          description="user's first name",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="last_name",
     *          description="user's last name",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="username",
     *          description="user's username",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *       @OA\Parameter(
     *          name="email",
     *          description="enter email address",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="phone_number",
     *          description="phone number is required",
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
     *     @OA\Response(response=200,description="successfully created user"),
     *     @OA\Response(response=422,description="Form/data validation error"),
     *       @OA\Response(response=500, description="Bad request"),
     *     )
     *
     *
     * @param Request $request
     * @return Builder|JsonResponse
     * @throws CountryCodeException
     */
    public function processRegistration(Request $request): JsonResponse|Builder
    {
        $refCode = $request->ref_code;
        $referrer = $this->userRepository->where(function ($query) use ($refCode) {
            $query->when($refCode, function ($rCodeQuery) use ($refCode) {
                $rCodeQuery->where(['ref_code' => $refCode]);
            });
        })->first();
        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'phone_number' => 'required|phone:NG|unique:users,intl_phone_number',
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
        $rulesMessage['phone_number.phone'] = 'Phone number is invalid';
        $rulesMessage['phone_number.unique'] = 'Phone number already in use';

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($this->failedValidation($validator), 422);
        }
        $formattedRequest = [
            'referral_id' => $referrer ? $referrer->id : null,
            'phone_number' => phone($request->phone_number, 'NG')->formatForMobileDialingInCountry('NG'),
            'intl_phone_number' => phone($request->phone_number, 'NG')->formatE164(),
        ];
        $request->merge($formattedRequest);
        try {
            $user = $this->userRepository->addUser($request);
            $user->assignRole('user');
            event(new Registered($user));
            $jwtToken = auth('frontend')->login($user);
            $authorization = new Authorization('frontend', $jwtToken);
            return api()
                ->status(200)
                ->message('Registration was successful, please activate your account to have access to all features')
                ->data(fractal($user, new AuthTransformer($authorization))->toArray())->respond();
        } catch (\Exception $exception) {
            logger()->error('User reg error : ' . $exception);
            return api()->status(500)->message('Registration was not successful, please try again')->respond();
        }
    }
}
