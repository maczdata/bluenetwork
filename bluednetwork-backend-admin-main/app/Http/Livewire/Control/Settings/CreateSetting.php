<?php

namespace App\Http\Livewire\Control\Settings;

use LivewireUI\Modal\ModalComponent;

class CreateSetting extends ModalComponent
{
    public $settingTypes;
    public $settingDataType;

    public function mount($settingTypes)
    {
        $this->settingTypes = $settingTypes;
    }


    public function render()
    {
        return view('control.livewire.settings.create-setting');
    }
}
