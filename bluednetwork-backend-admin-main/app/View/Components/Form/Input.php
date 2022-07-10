<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           Input.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     03/07/2021, 9:46 PM
 */

namespace App\View\Components\Form;

use App\Abstracts\BladeComponent;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

/**
 * Class Input
 * @package App\View\Components\Form
 */
class Input extends BladeComponent
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(public ?string $name = null, public ?string $id = null, public string $type = 'text', public ?string $value = '', public $disabled = null)
    {
        $this->id = $id ?? $name;
        $this->value = $name ? old($name, $value ?? '') :'';
    }

    /**
     * @return Application|Factory|Htmlable|View
     */
    public function render(): View|Factory|Htmlable|Application
    {
        return view('components.form.input');
    }
}
