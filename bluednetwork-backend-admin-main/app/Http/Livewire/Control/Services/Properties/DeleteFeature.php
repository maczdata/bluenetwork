<?php

namespace App\Http\Livewire\Control\Services\Properties;

use App\Models\Common\Featurize;
use App\Models\Common\ItemAddon;
use App\Models\Common\Service;
use App\Models\Sales\Order;
use LivewireUI\Modal\ModalComponent;

class DeleteFeature extends ModalComponent
{
    public Featurize $featurize;

    public function mount(Featurize $featurize)
    {
        $this->featurize = $featurize;
    }

    public function delete()
    {
        $deleted = Featurize::destroy($this->featurize->id);
        if ($deleted) {
            flash('Featurize deleted successfully')->success();
        } else {
            flash('Unable to delete featurize')->error();
        }
        $this->closeModal();
        return redirect(request()->header('Referer'));
    }
    public function render()
    {
        return view('control.livewire.services.properties.delete-feature');
    }
}
