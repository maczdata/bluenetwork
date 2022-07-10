<?php

namespace App\Http\Livewire\Control\ServiceTypes;

use App\Models\Common\ServiceType;
use LivewireUI\Modal\ModalComponent;

class DeleteServiceTypes extends ModalComponent
{
    public ServiceType $serviceType;

    public function mount(ServiceType $serviceType)
    {
        $this->serviceType = $serviceType;
    }

    public function delete()
    {
        $deleted = ServiceType::destroy($this->serviceType->id);
        if ($deleted) {
            flash('Service Type deleted successfully')->success();
        } else {
            flash('Unable to delete service type\'s account')->error();
        }
        $this->closeModal();
        return redirect()->route('control.service-type.list');
    }
    
    public function render()
    {
        return view('control.livewire.service-types.delete');
    }
}
