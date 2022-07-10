<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           ControlController.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     21/08/2021, 1:23 AM
 */

namespace App\Abstracts\Http\Controllers;

use Illuminate\Support\Facades\Auth;

/**
 * Class ControlController
 *
 * @package App\Abstracts\Http\Controllers
 */
class ControlController extends SystemController
{
    public function __construct()
    {
        parent::__construct();

        //Auth::shouldUse('admin');
    }
}
