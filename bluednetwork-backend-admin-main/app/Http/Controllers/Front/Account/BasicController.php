<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           BasicController.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Http\Controllers\Front\Account;


use App\Abstracts\Http\Controllers\FrontController;
use App\Repositories\Users\UserRepository;
use App\Transformers\Users\UserTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Propaganistas\LaravelPhone\Exceptions\CountryCodeException;

/**
 * Class BasicController
 * @package App\Http\Controllers\Front\Account
 */
class BasicController extends FrontController
{
    /**
     * ProfileController constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(protected UserRepository $userRepository)
    {
        parent::__construct();
    }

    /**
     * @OA\Post(
     *       path="/account/update_basic",
     *       operationId="account_basic",
     *       tags={"Account"},
     *       summary="update user basic data",
     *       security={{"bearerAuth":{}}},
     *       description="update users basic information",
     *      @OA\Parameter(
     *          name="token",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *           type="string"
     *          )
     *       ),
     *        @OA\RequestBody(
     *          @OA\MediaType(
     *                mediaType="multipart/form-data",
     *                @OA\Schema(
     *                   required={"first_name","last_name","email","phone_number"},
     *                   @OA\Property(
     *                      property="first_name",
     *                      type="string",
     *                   ),
     *                   @OA\Property(
     *                      property="last_name",
     *                      type="string",
     *                   ),
     *                   @OA\Property(
     *                      property="email",
     *                      type="string",
     *                   ),
     *                   @OA\Property(
     *                      property="phone_number",
     *                      type="string",
     *                   ),
     *                   @OA\Property(
     *                      property="profile_image",
     *                      type="string",
     *                      format="binary",
     *                   ),
     *                ),
     *             )
     *       ),
     *       @OA\Response(
     *          response=200,
     *          description="user profile update successfull"
     *       ),
     *       @OA\Response(response=400, description="Bad request"),
     *       @OA\Response(response=401, description="failed to update profile")
     *     )
     *
     * @param Request $request
     * @return JsonResponse
     * @throws CountryCodeException
     */
    public function processBasic(Request $request): JsonResponse
    {
        $user = $request->user('frontend');
        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id . ',id',
            'phone_number' => 'required|phone:NG|unique:users,intl_phone_number,' . $user->id . ',id',
            'profile_image' => 'nullable|image',

        ];
        $rulesMessage = [
            'first_name.required' => 'First name is required',
            'last_name.required' => 'Last name is required',
            'email.required' => 'Email address is required',
            'email.email' => 'Please use a valid email address',
            'email.unique' => 'Email already in use',
            'phone_number.required' => 'Phone number is required',
            'phone_number.phone' => 'Please use a valid Nigerian phone number',
            'phone_number.unique' => 'Phone number already in use',
        ];

        $validator = Validator::make($request->all(), $rules, $rulesMessage);
        if ($validator->fails()) {
            return response()->json($this->failedValidation($validator), 422);
        }

        $formattedRequest = [
            'phone_number' => phone($request->phone_number, 'NG')->formatForMobileDialingInCountry('NG'),
            'intl_phone_number' => phone($request->phone_number, 'NG')->formatE164()
        ];
        $request->merge($formattedRequest);
        try {
            $user = $this->userRepository->updateUser($user, $request);
            return api()->status(200)->data(fractal($user, UserTransformer::class)->toArray())->message('Profile data updated')->respond();
        } catch (\Exception $exception) {
            logger()->error('User profile update error : ' . $exception);
            return api()->status(500)->message('Profile data could not be updated')->respond();
        }
    }
}
