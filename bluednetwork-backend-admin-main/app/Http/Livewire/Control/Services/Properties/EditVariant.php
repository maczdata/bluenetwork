<?php

namespace App\Http\Livewire\Control\Services\Properties;

use App\Models\Common\Service;
use App\Models\Common\ServiceVariant;
use LivewireUI\Modal\ModalComponent;

class EditVariant extends ModalComponent
{
    public ServiceVariant $serviceVariant;
    public Service $service;

    public function mount(ServiceVariant $serviceVariant, Service $service)
    {
        $this->serviceVariant = $serviceVariant;
        $this->service = $service;
    }

  
    public function render()
    {
        return view('control.livewire.services.properties.edit-variant');
    }
}
