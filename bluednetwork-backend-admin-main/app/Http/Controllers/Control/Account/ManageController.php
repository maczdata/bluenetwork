<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           ManageController.php
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
use Illuminate\Http\Request;

class ManageController extends ControlController
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
    public function index(Request $request): View|Factory|Application
    {
        $user = $request->user();
        return view($this->_config['view'], compact('user'));
    }
}
