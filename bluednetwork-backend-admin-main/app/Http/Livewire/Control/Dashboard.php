<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           Dashboard.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     21/08/2021, 1:29 AM
 */

namespace App\Http\Livewire\Control;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\View\View;
use Livewire\Component;

/**
 * Class Dashboard
 * @package App\Http\Livewire\Control
 */
class Dashboard extends Component
{
    /**
     * Get the current user of the application.
     *
     * @return Authenticatable|null
     */
    public function getUserProperty(): ?Authenticatable
    {
        return auth('frontend')->user();
    }

    /**
     * Render the component.
     *
     * @return View
     */
    public function render()
    {
        return view('front.portal.dashboard')->layout('layouts.portal.authenticated');
    }
}
