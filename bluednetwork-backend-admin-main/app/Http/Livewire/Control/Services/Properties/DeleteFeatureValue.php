<?php

namespace App\Http\Livewire\Control\Services\Properties;

use App\Models\Common\FeaturizeValue;
use LivewireUI\Modal\ModalComponent;

class DeleteFeatureValue extends ModalComponent
{
    public FeaturizeValue $featurizeValue;

    public function mount(FeaturizeValue $featurizeValue)
    {
        $this->featurizeValue = $featurizeValue;
    }

    public function delete()
    {
        $deleted = FeaturizeValue::destroy($this->featurizeValue->id);
        if ($deleted) {
            flash('Feature Value deleted successfully')->success();
        } else {
            flash('Unable to delete featurize value')->error();
        }
        $this->closeModal();
        return redirect(request()->header('Referer'));
    }
    public function render()
    {
        return view('control.livewire.services.properties.delete-feature-value');
    }
}
