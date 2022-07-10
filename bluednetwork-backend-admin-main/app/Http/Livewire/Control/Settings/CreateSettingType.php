<?php

namespace App\Http\Livewire\Control\Settings;

use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class CreateSettingType extends ModalComponent
{
    public function render()
    {
        return view('control.livewire.settings.create-setting-type');
    }
}
