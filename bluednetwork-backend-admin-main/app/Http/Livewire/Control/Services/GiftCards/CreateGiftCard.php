<?php
namespace App\Http\Livewire\Control\Services\GiftCards;

use LivewireUI\Modal\ModalComponent;

class CreateGiftCard extends ModalComponent
{
    public function render()
    {
        return view('control.livewire.services.giftcards.create');
    }
}
