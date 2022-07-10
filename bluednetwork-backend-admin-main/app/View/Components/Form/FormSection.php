<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           FormSection.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     03/07/2021, 9:18 PM
 */

namespace App\View\Components\Form;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Class FormSection
 * @package App\View\Components\Form
 */
class FormSection extends Component
{
    /**
     * Create the component instance.
     *
     * @param string $method
     * @param null $action
     * @param null $actions
     * @param null $formencoding
     * @param string $title
     * @param string $title
     * @param string $description
     */
    public function __construct(
        public string $method = 'post',
        public $action = null,
        public $formencoding = null,
        public string $title = '',
        public string $description = ''
    )
    {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|Closure|string
     */
    public function render(): View|string|Closure
    {
        return view('components.form.form-section');
    }
}
