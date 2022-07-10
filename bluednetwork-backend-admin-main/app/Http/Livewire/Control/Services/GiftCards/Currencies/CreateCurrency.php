<?php

namespace App\Http\Livewire\Control\Services\GiftCards\Currencies;

use App\Repositories\Common\CurrencyRepository;
use LivewireUI\Modal\ModalComponent;

class CreateCurrency extends ModalComponent
{
    private CurrencyRepository $currencyRepository;
    public $currencies;

    public function mount()
    {
        $this->currencyRepository = app(CurrencyRepository::class);
        $this->currencies = $this->currencyRepository->all();
    }
    public function render()
    {
        return view('control.livewire.services.giftcards.currencies.create-currency');
    }
}
