<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           Label.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     13/06/2021, 1:46 PM
 */

namespace App\View\Components\Form;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Class Label
 * @package App\View\Components\Form
 */
class Label extends Component
{
/**
     * The alert type.
     *
     * @var string
     */
    public string $value;
    /**
     * @var mixed|bool
     */
    public mixed $required;

    /**
     * Create the component instance.
     *
     * @param $value
     * @param bool $required
     */
    public function __construct($value, bool $required = false)
    {
        $this->value = $value;

        $this->required = $required;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return Application|Factory|View
     */
    public function render()
    {
        return view('components.form.label');
    }
}
