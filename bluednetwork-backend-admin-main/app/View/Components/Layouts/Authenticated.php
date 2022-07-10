<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           Authenticated.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     20/08/2021, 11:44 PM
 */

namespace App\View\Components\Layouts;

use Illuminate\View\Component;
use Illuminate\View\View;

/**
 * Class AuthenticatedLayout
 * @package App\View\Components\app
 */
class Authenticated extends Component
{
    /**
     * Get the view / contents that represents the component.
     *
     * @return View
     */
    public function render()
    {
        return view('control.layouts.authenticated');
    }
}
