<?php

namespace App\Http\Livewire\Control\Services\GiftCards;

use App\Models\Common\FeaturizeValue;
use App\Models\Common\GiftCard;
use LivewireUI\Modal\ModalComponent;

class EditGiftCard extends ModalComponent
{
    public $giftCard;

    public function mount(GiftCard $giftCard)
    {
        $this->giftCard = $giftCard;
    }

    public function render()
    {
        return view('control.livewire.services.giftcards.edit');
    }
}
