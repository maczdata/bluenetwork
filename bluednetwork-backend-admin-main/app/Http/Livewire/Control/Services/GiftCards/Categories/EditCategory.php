<?php

namespace App\Http\Livewire\Control\Services\GiftCards\Categories;

use LivewireUI\Modal\ModalComponent;

class EditCategory extends ModalComponent
{
    public function render()
    {
        return view('control.livewire.services.giftcards.categories.edit-category');
    }
}
