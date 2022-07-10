<?php

namespace App\Http\Livewire\Control\Services\Properties;

use LivewireUI\Modal\ModalComponent;
use Plank\Metable\Meta;

class EditMeta extends ModalComponent
{
    public int $metaId;
    public $meta;
    public $key;
    public $value;

    public function mount(int $metaId)
    {
        $this->meta = Meta::find($metaId);
        $this->key = $this->meta->key;
        $this->value = $this->meta->value;
        $this->metaId = $metaId;
    }

    public function update()
    {
        $updated = Meta::where(['id' => $this->metaId])->update([
            'key' => $this->key,
            'value' => $this->value,
        ]);
        if ($updated) {
            flash('Meta updated successfully')->success();
        } else {
            flash('Unable to update meta')->error();
        }
        $this->closeModal();
        return redirect(request()->header('Referer'));
    }

    public function render()
    {
        return view('control.livewire.services.properties.edit-meta');
    }
}
