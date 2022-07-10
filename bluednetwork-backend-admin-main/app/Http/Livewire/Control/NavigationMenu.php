<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           NavigationMenu.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     21/08/2021, 1:30 AM
 */

namespace App\Http\Livewire\Control;

use Illuminate\View\View;
use Livewire\Component;

/**
 * Class NavigationMenu
 * @package App\Http\Livewire\Control
 */
class NavigationMenu extends Component
{
    /**
     * The component's listeners.
     *
     * @var array
     */
    protected $listeners = [
        'refresh-navigation-menu' => '$refresh',
    ];

    /**
     * Render the component.
     *
     * @return View
     */
    public function render()
    {
        return view('control.fragments._header_navigation');
    }
}
