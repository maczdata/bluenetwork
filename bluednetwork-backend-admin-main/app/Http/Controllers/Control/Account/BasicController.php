<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           BasicController.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     20/08/2021, 7:32 PM
 */

namespace App\Http\Controllers\Control\Account;

use App\Abstracts\Http\Controllers\ControlController;
use App\Repositories\Users\UserRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Class BasicController
 * @package App\Http\Controllers\Front\Portal\Account
 */
class BasicController extends ControlController
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
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request): Factory|View|Application
    {
        return view($this->_config['view']);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function processBasic(Request $request): RedirectResponse
    {
        $user = $request->user();
        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id . ',id',
        ];
        $rulesMessage = [
            'first_name.required' => 'First name is required',
            'last_name.required' => 'Last name is required',
            'email.required' => 'Email address is required',
            'email.email' => 'Please use a valid email address',
            'email.unique' => 'Email already in use',
        ];

        $request->validate($rules, $rulesMessage);

        /*$formattedRequest = [
            'phone_number' => phone($request->phone_number, 'NG')->formatForMobileDialingInCountry('NG'),
            'intl_phone_number' => phone($request->phone_number, 'NG')->formatE164()
        ];
        $request->merge($formattedRequest);*/
        try {
            $this->userRepository->updateUser($user, $request);
            flash('Basic information successfully updated')->success();
            return redirect()->route('control.account.manage.basic');
        } catch (\Exception $exception) {
            logger()->error('User profile update error : ' . $exception);
            flash('Basic information could not be updated, please try again')->error();
            return redirect()->route('control.account.manage.basic');
        }
    }
}
