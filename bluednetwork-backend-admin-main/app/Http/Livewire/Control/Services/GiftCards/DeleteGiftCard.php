<?php

namespace App\Http\Livewire\Control\Services\GiftCards;

use App\Models\Common\GiftCard;
use LivewireUI\Modal\ModalComponent;

class DeleteGiftCard extends ModalComponent
{
    public GiftCard $giftCard;

    public function mount(GiftCard $giftCard)
    {
        $this->giftCard = $giftCard;
    }

    public function delete()
    {
        $deleted = GiftCard::destroy($this->giftCard->id);
        if ($deleted) {
            flash('Gift Card deleted successfully')->success();
        } else {
            flash('Unable to delete Gift Card')->error();
        }
        $this->closeModal();
        return redirect(request()->header('Referer'));
    }
    public function render()
    {
        return view('control.livewire.services.giftcards.delete');
    }
}
