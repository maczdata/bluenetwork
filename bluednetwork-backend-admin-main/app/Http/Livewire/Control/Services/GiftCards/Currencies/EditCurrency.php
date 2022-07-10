<?php

namespace App\Http\Livewire\Control\Services\GiftCards\Currencies;

use App\Models\Common\GiftCardCurrency;
use App\Repositories\Common\CurrencyRepository;
use LivewireUI\Modal\ModalComponent;

class EditCurrency extends ModalComponent
{
    private CurrencyRepository $currencyRepository;
    public $currencies;
    public $currentCurrencyId;

    public function mount(GiftCardCurrency $giftCardCurrency)
    {
        $this->currencyRepository = app(CurrencyRepository::class);
        $this->currencies = $this->currencyRepository->all();
        $this->currentCurrencyId = $giftCardCurrency->currency_id;
    }

    public function render()
    {
        return view('control.livewire.services.giftcards.currencies.edit-currency');
    }
}
