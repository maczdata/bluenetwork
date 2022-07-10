<?php

namespace App\Http\Livewire\Control\Services\Properties;

use App\Models\Common\Feature;
use App\Models\Common\Featurize;
use App\Repositories\Common\FeatureRepository;
use App\Repositories\Common\ServiceRepository;
use LivewireUI\Modal\ModalComponent;

class EditFeature extends ModalComponent
{
    public Feature $feature;
    public $title;

    protected $rules = [
        'title' => 'required|string',
    ];

    public function mount(Feature $feature)
    {
        $this->feature = $feature;
        $this->title = $this->feature->title;
    }

    public function update()
    {
        $this->validate();
        $updated = Feature::where(['id' => $this->feature->id])->update([
            'title' => $this->title,
        ]);
        if ($updated) {
            flash('Feature title updated successfully')->success();
        } else {
            flash('Unable to update feature')->error();
        }
        $this->closeModal();
        return redirect(request()->header('Referer'));
    }

    public function render()
    {
        return view('control.livewire.services.properties.edit-feature');
    }
}
