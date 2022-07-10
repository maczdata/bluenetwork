<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           SwitchToggle.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     27/06/2021, 2:11 PM
 */

namespace App\View\Components\Form;

use Illuminate\Support\Str;
use App\Abstracts\BladeComponent;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Foundation\Application;
// use App\Traits\Common\BladeComponentHandlesValidationErrors;

/**
 * Class SwitchToggle
 * @package App\View\Components\Form
 */
class SwitchToggle extends BladeComponent
{
    // use BladeComponentHandlesValidationErrors;
    /**
     * @var array|string[]
     */
    protected static array $assets = ['alpine'];
    /**
     * @var string
     */
    private string $labelId;

    /**
     * SwitchToggle constructor.
     * @param string|null $name
     * @param string|null $id
     * @param mixed|false $value
     * @param mixed|bool $onValue
     * @param mixed|false $offValue
     * @param string|null $containerClass
     * @param bool $short
     * @param string|null $label
     * @param string $labelPosition
     * @param string|null $offIcon
     * @param string|null $onIcon
     * @param string|null $buttonLabel
     * @param string|null $size
     * @param bool $disabled
     * @param string $extraAttributes
     */
    public function __construct(
        public null|string $name = null,
        public null|string $id = null,
        public mixed $value = false,
        public mixed $onValue = true,
        public mixed $offValue = false,
        public null|string $containerClass = null,
        public bool $short = false,
        public null|string $label = null,
        public string $labelPosition = 'right',
        public null|string $offIcon = null, // doesn't apply to short mode
        public null|string $onIcon = null, // doesn't apply to short mode
        public null|string $buttonLabel = 'form-components::messages.switch_button_label',
        public null|string $size = null,
        public bool $disabled = false,
        public $extraAttributes = '',
    ) {
        $this->id = $this->id ?? $this->name;
        $this->labelId = $this->id ?? Str::random(8);
    }

    public function labelId(): string
    {
        return "{$this->labelId}-label";
    }

    public function buttonClass(): string
    {
        return collect([
            'switch-toggle',
            $this->short ? 'switch-toggle-short' : 'switch-toggle-simple',
            $this->toggleSize(),
            $this->disabled ? 'disabled' : null,
        ])->filter()->implode(' ');
    }

    private function toggleSize(): string
    {
        /*
         * We are defining the size classes explicitly here to prevent
         * tailwind from purging them.
         */
        return match ($this->size ?? '') {
            'sm' => 'switch-toggle--sm',
            'lg' => 'switch-toggle--lg',
            default => 'switch-toggle--base',
        };
    }

    public function getContainerClass(): string
    {
        return collect([
            'flex',
            'items-center',
            $this->labelPosition === 'left' ? 'justify-between' : null,
            $this->containerClass,
        ])->filter()->implode(' ');
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return Application|Factory|View
     */
    public function render(): View|Factory|Application
    {
        return view('components.form.switch-toggle');
    }
}
