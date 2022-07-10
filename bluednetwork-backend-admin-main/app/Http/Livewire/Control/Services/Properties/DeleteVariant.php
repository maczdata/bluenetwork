<?php

namespace App\Http\Livewire\Control\Services\Properties;

use App\Models\Common\ServiceVariant;
use LivewireUI\Modal\ModalComponent;

class DeleteVariant extends ModalComponent
{
    public int $metaId;

    public function mount(ServiceVariant $variant)
    {
        $this->variant = $variant;
    }

    public function delete()
    {
        $deleted = ServiceVariant::destroy($this->variant->id);
        if ($deleted) {
            flash('Service variant deleted successfully')->success();
        } else {
            flash('Unable to delete service variant')->error();
        }
        $this->closeModal();
        return redirect(request()->header('Referer'));
    }
    public function render()
    {
        return view('control.livewire.services.properties.delete-variant');
    }
}
