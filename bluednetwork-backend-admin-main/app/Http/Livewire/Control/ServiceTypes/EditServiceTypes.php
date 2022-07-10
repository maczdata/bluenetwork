<?php

namespace App\Http\Livewire\Control\ServiceTypes;

use App\Models\Common\ServiceType;
use LivewireUI\Modal\ModalComponent;

class EditServiceTypes extends ModalComponent
{
    public ServiceType $serviceType;

    public function mount(ServiceType $serviceType)
    {
        $this->serviceType = $serviceType;
    }


    public function render()
    {
        return view('control.livewire.service-types.edit', ['serviceType' => $this->serviceType]);
    }
}
