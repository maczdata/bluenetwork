<?php

namespace App\Http\Livewire\Control\Services\Featurizes;

use App\Models\Common\FeaturizeValue;
use LivewireUI\Modal\ModalComponent;

class EditValue extends ModalComponent
{
    public $featurize;
    public $valueMetas;
    public $title;
    public $description;

    protected $rules = [
        'valueMetas.*.key' => 'required|string',
        'valueMetas.*.value' => 'required|string',
    ];

    public function mount(FeaturizeValue $featurizeValue)
    {
        $this->featurize = $featurizeValue;
        $this->valueMetas = $this->featurize->meta->toArray() ?? [];
        $this->description = $this->featurize->description;
        $this->title = $this->featurize->title;
    }

    public function removeRow($property, $row)
    {
        if ($property === "meta") {
            unset($this->valueMetas[$row]);
        }
    }

    public function addNewRow($property)
    {
        if ($property === "meta") {
            array_push($this->valueMetas, ["key" => "new_key", "value" => "new_value"]);
        }
    }

    public function update()
    {
        $this->validate();
        $featurize = FeaturizeValue::where(['id' => $this->featurize->id])->first();
        $updated = $featurize->update([
            'title' => $this->title,
            'description' => $this->description,
        ]);
       
        if (isset($this->valueMetas) && count($this->valueMetas) > 0) {
            $featurize->purgeMeta();
            foreach ($this->valueMetas as $meta) {
                $featurize->setMeta(
                    $meta['key'],
                    $meta['value'],
                );
            }
        }
        if ($updated) {
            flash('Value updated successfully')->success();
        } else {
            flash('Unable to update value')->error();
        }

        $this->closeModal();
        return redirect(request()->header('Referer'));
    }


    public function render()
    {
        return view('control.livewire.services.edit-featurize-value');
    }
}
