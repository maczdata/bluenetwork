<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           FormWrapper.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     27/06/2021, 1:44 PM
 */

namespace App\View\Components\Form;

use App\Abstracts\BladeComponent;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ViewErrorBag;

/**
 * Class FormWrapper
 * @package App\View\Components\Form
 */
class FormWrapper extends BladeComponent
{
    /*
     * Form method spoofing to support PUT, PATCH and DELETE actions.
     * https://laravel.com/docs/master/routing#form-method-spoofing
     */
    public bool $spoofMethod;

    /**
     * FormWrapper constructor.
     * @param string $action
     * @param string $method
     * @param bool $hasFiles
     * @param bool $spellcheck
     */
    public function __construct(
        public string $action = '',
        public string $method = 'POST',
        public bool $hasFiles = false,
        public bool $spellcheck = false,
    )
    {
        $this->method = strtoupper($this->method);
        $this->spoofMethod = in_array($this->method, ['PUT', 'PATCH', 'DELETE'], true);
    }

    /**
     * @param string $bag
     * @return bool
     */
    public function hasError(string $bag = 'default'): bool
    {
        $errors = View::share('errors',request()->session()->get('errors', new ViewErrorBag));

        return $errors->getBag($bag)->isNotEmpty();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return Application|Factory|\Illuminate\Contracts\View\View
     */
    public function render(): View|Factory|Application
    {
        return view('components.form.form-wrapper');
    }
}
