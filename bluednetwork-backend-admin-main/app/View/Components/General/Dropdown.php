<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           Dropdown.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     13/06/2021, 1:46 PM
 */

namespace App\View\Components\General;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Class Dropdown
 * @package App\View\Components\General
 */
class Dropdown extends Component
{
    /**
     * @var string
     */
    public string $align;
    /**
     * @var string
     */
    public string $width;
    /**
     * @var string
     */
    public string $contentClasses;
    /**
     * @var mixed|string
     */
    public mixed $dropdownClasses;

    /**
     * Create a new component instance.
     *
     * @param string $align
     * @param string $width
     * @param string $contentClasses
     * @param string $dropdownClasses
     */
    public function __construct(string $align = 'right', string $width = '48', string $contentClasses = 'py-1 bg-white', string $dropdownClasses = '')
    {
        $this->align = $align;

        $this->width = $width;

        $this->contentClasses = $contentClasses;

        $this->dropdownClasses =  $dropdownClasses;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return Application|Factory|View
     */
    public function render()
    {
        return view('components.general.dropdown');
    }
}
