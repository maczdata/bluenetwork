<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           PasswordController.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     20/08/2021, 7:30 PM
 */

namespace App\Http\Controllers\Control\Account;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Users\UserRepository;
use App\Abstracts\Http\Controllers\ControlController;

/**
 *
 */
class PasswordController extends ControlController
{

    /**
     * ProfileController constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(protected UserRepository $userRepository)
    {
        parent::__construct();
    }


    public function processPassword(Request $request): JsonResponse
    {
        $user = $request->user();

        $passwordRules = [
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
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
