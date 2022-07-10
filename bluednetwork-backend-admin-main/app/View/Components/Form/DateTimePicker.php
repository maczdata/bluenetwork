<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           DateTimePicker.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     03/07/2021, 9:58 PM
 */

namespace App\View\Components\Form;

use Closure;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

/**
 * Class DateTimePicker
 * @package App\View\Components\Form
 */
class DateTimePicker extends Input
{
    /**
     * DateTimePicker constructor.
     * @param string|null $name
     * @param string|null $id
     * @param string|null $value
     * @param string|null $placeholder
     * @param array $options
     */
    public function __construct(
        public ?string $name = null,
        public ?string $id = null,
        public ?string $value = '',
        public ?string $placeholder = null,
        public array $options = []
    )
    {
        parent::__construct($name, $id, 'text', $value);
    }


    /**
     * @return array
     */
    public function options(): array
    {
        return array_merge([
            'dateFormat' => 'Y-m-d',
            'enableTime' => false,
            'noCalendar' => false
        ], $this->options);
    }

    /**
     * @return string
     */
    public function jsonOptions(): string
    {
        if (empty($this->options())) {
            return '';
        }

        return json_encode((object)$this->options());
    }

    /**
     * @return Application|Htmlable|Factory|View
     */
    public function render(): View|Factory|Htmlable|Application
    {
        return view('components.form.date-time-picker');
    }
}
