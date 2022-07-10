<?php

namespace App\Http\Livewire\Control\Services\GiftCards\Categories;

use LivewireUI\Modal\ModalComponent;

class CreateCategory extends ModalComponent
{
    public function render()
    {
        return view('control.livewire.services.giftcards.categories.create-category');
    }
}
