<?php

namespace App\Http\Livewire\Control\Services\Properties;

use App\Models\Common\ItemAddon;
use LivewireUI\Modal\ModalComponent;

class DeleteAddon extends ModalComponent
{
    public ItemAddon $addon;

    public function mount(ItemAddon $addon)
    {
        $this->addon = $addon;
    }

    public function delete()
    {
        $deleted = ItemAddon::destroy($this->addon->id);
        if ($deleted) {
            flash('Add on deleted successfully')->success();
        } else {
            flash('Unable to delete addon')->error();
        }
        $this->closeModal();
        return redirect(request()->header('Referer'));
    }
    public function render()
    {
        return view('control.livewire.services.properties.delete-addon');
    }
}
