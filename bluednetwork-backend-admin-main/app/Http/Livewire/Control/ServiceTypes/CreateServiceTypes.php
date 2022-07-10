<?php

namespace App\Http\Livewire\Control\ServiceTypes;

use LivewireUI\Modal\ModalComponent;

class CreateServiceTypes extends ModalComponent
{
    public function render()
    {
        return view('control.livewire.service-types.create');
    }
}
