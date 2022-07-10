<?php

namespace App\Http\Livewire\Control\Services;

use App\Models\Common\Service;
use LivewireUI\Modal\ModalComponent;

class DeleteService extends ModalComponent
{
    public Service $service;

    public function mount(Service $service)
    {
        $this->service = $service;
    }

    public function delete()
    {
        $deleted = Service::destroy($this->service->id);
        
        if ($deleted) {
            flash('Service deleted successfully')->success();
        } else {
            flash('Unable to delete service')->error();
        }
        $this->closeModal();
        return redirect()->route('control.service.list');
    }
    public function render()
    {
        return view('control.livewire.services.delete');
    }
}
