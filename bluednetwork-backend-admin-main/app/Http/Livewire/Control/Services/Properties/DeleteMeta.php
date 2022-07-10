<?php

namespace App\Http\Livewire\Control\Services\Properties;

use LivewireUI\Modal\ModalComponent;
use Plank\Metable\Meta;

class DeleteMeta extends ModalComponent
{
    public int $metaId;

    public function mount(int $metaId)
    {
        $this->metaId = $metaId;
    }

    public function delete()
    {
        $deleted = Meta::destroy($this->metaId);
        if ($deleted) {
            flash('Meta deleted successfully')->success();
        } else {
            flash('Unable to delete meta')->error();
        }
        $this->closeModal();
        return redirect(request()->header('Referer'));
    }
    public function render()
    {
        return view('control.livewire.services.properties.delete-meta');
    }
}
