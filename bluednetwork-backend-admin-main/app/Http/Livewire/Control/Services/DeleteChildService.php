<?php

namespace App\Http\Livewire\Control\Services;

use App\Models\Common\Service;
use LivewireUI\Modal\ModalComponent;

class DeleteChildService extends ModalComponent
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
            flash('Child Service deleted successfully')->success();
        } else {
            flash('Unable to delete child service')->error();
        }
        $this->closeModal();
        return redirect(request()->header('Referer'));
    }
    public function render()
    {
        return view('control.livewire.services.delete-child-service');
    }
}
