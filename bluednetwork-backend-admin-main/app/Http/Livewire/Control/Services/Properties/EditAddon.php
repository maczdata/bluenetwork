<?php

namespace App\Http\Livewire\Control\Services\Properties;

use App\Models\Common\ItemAddon;
use LivewireUI\Modal\ModalComponent;

class EditAddon extends ModalComponent
{
    public ItemAddon $addon;
    public $title;
    public $description;
    public $price;
    public $enabled;

    protected $rules = [
        'title' => 'required|string',
    ];

    public function mount(ItemAddon $addon)
    {
        $this->addon = $addon;
        $this->title = $this->addon->title;
        $this->description = $this->addon->description;
        $this->price = $this->addon->price;
        $this->enabled = $this->addon->enabled;
    }

    public function update()
    {
        $this->validate();
        $updated = ItemAddon::where(['id' => $this->addon->id])->update([
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'enabled' => $this->enabled,
        ]);
        if ($updated) {
            flash('Addon updated successfully')->success();
        } else {
            flash('Unable to update addon')->error();
        }
        $this->closeModal();
        return redirect(request()->header('Referer'));
    }

    public function render()
    {
        return view('control.livewire.services.properties.edit-addon');
    }
}
