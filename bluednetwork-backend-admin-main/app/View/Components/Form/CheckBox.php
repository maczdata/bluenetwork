<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           CheckBox.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     27/06/2021, 1:52 PM
 */

namespace App\View\Components\Form;

use App\Abstracts\BladeComponent;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

/**
 * Class CheckBox
 * @package App\View\Components\Form
 */
class CheckBox extends BladeComponent
{
    /**
     * @var string
     */
    public string $type = 'checkbox';

    /**
     * CheckBox constructor.
     * @param string|null $name
     * @param string|null $id
     * @param mixed|null $value
     * @param string|null $label
     * @param string|null $description
     * @param bool $checked
     * @param string $extraAttributes
     */
    public function __construct(
        public null|string $name = null,
        public null|string $id = null,
        public mixed $value = null,
        public null|string $label = null,
        public null|string $description = '',
        public bool $checked = false,
        public string $extraAttributes = '',
    ) {
        $this->id = $this->id ?? $this->name;

        if ($this->name) {
            $this->checked = (bool) old($this->name, $this->checked);
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return Application|Factory|View
     */
    public function render(): View|Factory|Application
    {
        return view('components.form.checkbox-or-radio');
    }
}
