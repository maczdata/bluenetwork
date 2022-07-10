<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           PasswordController.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Http\Controllers\Front\Account;


use App\Abstracts\Http\Controllers\FrontController;
use App\Repositories\Users\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Class PasswordController
 * @package App\Http\Controllers\Front\Account
 */
class PasswordController extends FrontController
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
     *       path="/account/update_password",
     *       operationId="account-password",
     *       tags={"Account"},
     *       summary="update user's password",
     *       description="update user's password",
     *       security={{"bearerAuth":{}}},
     *       @OA\Parameter(
     *          name="old_password",
     *          description="user's old password",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *       ),
     *       @OA\Parameter(
     *          name="password",
     *          description="new password",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *       ),
     *       @OA\Parameter(
     *          name="password_confirmation",
     *          description="confirm new password",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Response(
     *          response=200,
     *          description="user's password was successfully updated"
     *       ),
     *       @OA\Response(response=422, description="form/data validation error"),
     *       @OA\Response(response=500, description="technical error")
     *     )
     *
     *    process user's new password
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function processPassword(Request $request): JsonResponse
    {
        $user = $request->user('frontend');
        $passwordRules = [
            'password' => 'required|confirmed',
            'password_confirmation' => 'required'
        ];
        if (!is_null($user->password)) {
            $passwordRules['old_password'] = 'required|password:user';
        }

        $rulesMessage = [
            'old_password.required' => 'Current password is required',
            'old_password.password' => 'Current password is wrong',
            'password.required' => 'New password is required',
            'password.confirm' => 'Please confirm new password',
        ];
        $validator = Validator::make($request->all(), $passwordRules, $rulesMessage);
        if ($validator->fails()) {
            return response()->json($this->failedValidation($validator), 422);
        }
        $request->only('password');
        try {
            $this->userRepository->updateUser($user, $request);
            return api()->status(200)->message('Password was successfully updated')->respond();
        } catch (\Exception $exception) {
            logger()->error('User profile update error : ' . $exception);
            return api()->status(500)->message('There was an issue updating password, please try again')->respond();
        }
    }
}
