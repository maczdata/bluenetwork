<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           Badge.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     23/10/2021, 9:59 AM
 */

namespace App\View\Components\General;

use App\Abstracts\BladeComponent;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class Badge extends BladeComponent
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(public string $type)
    {
    }

    /**
     * Get the HTML Class for the avatar.
     *
     * @return string
     */
    public function getClassAttributes()
    {
        $class = ' text-gray-600 bg-gray-100';
        if ($this->type == 'success') {
            $class = ' text-white bg-green-500';
            //bg-indigo-500';
        } elseif ($this->type == 'info') {
            $class = ' text-white bg-blue-500';
        }elseif($this->type == 'warning'){
            $class = ' text-white bg-yellow-500';
        }elseif($this->type == 'danger'){
            $class =  ' text-white bg-red-500';
        }

        return $class;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return Application|Factory|View
     */
    public function render()
    {
        return view('components.general.badge');
    }
}
