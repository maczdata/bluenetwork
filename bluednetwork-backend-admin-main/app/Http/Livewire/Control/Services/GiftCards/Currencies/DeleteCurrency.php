<?php

namespace App\Http\Livewire\Control\Services\GiftCards\Currencies;

use App\Models\Common\GiftCardCurrency;
use LivewireUI\Modal\ModalComponent;

class DeleteCurrency extends ModalComponent
{
    public GiftCardCurrency $giftCardCurrency;

    public function mount(GiftCardCurrency $giftCardCurrency)
    {
        $this->giftCardCurrency = $giftCardCurrency;
    }

    public function delete()
    {
        $deleted = GiftCardCurrency::destroy($this->giftCardCurrency->id);
        if ($deleted) {
            flash('Gift Card Currency deleted successfully')->success();
        } else {
            flash('Unable to delete Gift Card Currency')->error();
        }
        $this->closeModal();
        return redirect(request()->header('Referer'));
    }

    public function render()
    {
        return view('control.livewire.services.giftcards.currencies.delete-currency');
    }
}
