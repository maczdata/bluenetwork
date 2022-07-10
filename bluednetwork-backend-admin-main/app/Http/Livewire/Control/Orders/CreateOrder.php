<?php

namespace App\Http\Livewire\Control\Orders;

use App\Models\Common\CategoryOfGiftCard;
use App\Models\Common\GiftCard;
use App\Models\Common\GiftCardCategory;
use App\Models\Common\GiftCardCurrency;
use App\Models\Common\Service;
use App\Models\Common\ServiceVariant;
use App\Models\Users\User;
use App\Repositories\Common\GiftCardCurrencyRateRepository;
use LivewireUI\Modal\ModalComponent;

class CreateOrder extends ModalComponent
{
    private GiftCardCurrencyRateRepository $giftCardCurrencyRateRepository;
    public $service;
    public $user;
    public $serviceProviders = [];
    public $serviceProvider;
    public $cableTvPackages = [];
    public $selectedService;
    public $giftCardCurrencies = [];
    public $dataValues = [];
    public $dataValue;
    public $cablePackage;
    public $orderKey;
    public $serviceVariants = [];
    public $giftCard;
    public $giftCardCurrency;
    public $giftCardCategory;
    public $giftCardCategoryId;
    public $giftCardSubCategory;
    public $giftCardCategories = [];
    public $giftCardSubCategories = [];
    public $giftCardCurrencyRates = [];

    public function render()
    {
        $this->giftCardCurrencyRateRepository = app(GiftCardCurrencyRateRepository::class);
        if (!empty($this->service)) {
            $this->selectedService = Service::find($this->service);
            $this->orderKey = $this->selectedService->key;
            $this->serviceProviders = Service::where('parent_id', $this->service)->get();
            $this->serviceVariants = ServiceVariant::where('service_id', $this->service)->get();
            if (!empty($this->giftCard)) {
                $this->giftCardCurrencies = GiftCardCurrency::where('gift_card_id', $this->giftCard)->get();
                if (!empty($this->giftCardCurrency)) {
                    $this->giftCardCategories = GiftCardCategory::where('gift_card_id', $this->giftCard)->get();
                }

                if (!empty($this->giftCardCategoryId)) {
                    $this->giftCardCategory = GiftCardCategory::where('id', $this->giftCardCategoryId)->first();
                    $this->giftCardSubCategories = CategoryOfGiftCard::where(
                        'parent_id',
                        $this->giftCardCategory?->categoryOfGiftCard?->id
                    )->get();
                }

                if (!empty($this->giftCardSubCategory)) {
                    $currencyId = $this->giftCardCurrency;
                    $giftCardId = $this->giftCard;
                    $categoryId = $this->giftCardCategoryId;
                    $giftCardCurrencyRates = $this->giftCardCurrencyRateRepository->scopeQuery(
                        function ($query) use ($giftCardId, $currencyId, $categoryId) {
                            return $query->where(
                                [
                                    'gift_card_currency_id' => $currencyId,
                                    'gift_card_category_id' => $categoryId,
                                ]
                            )->whereHas('giftCard', function ($query) use ($giftCardId) {
                                return $query->where('id', $giftCardId)->enabled();
                            })->whereHas('giftCardCurrency');
                        }
                    )->get();
                    foreach ($giftCardCurrencyRates as $rate) {
                        $payload = [
                            'rate_id' => $rate->id,
                            'quantity' => 0,
                            'price' => $rate->rate_value,
                            'codes' => '',
                        ];
                        $status = false;
                        foreach ($this->giftCardCurrencyRates as $currentRate) {
                            if ($currentRate['rate_id'] == $rate->id) {
                                $status = true;
                                break;
                            }
                        }
                        if (!$status) {
                            array_push($this->giftCardCurrencyRates, $payload);
                        }
                    }
                }
            }


            if (!empty($this->serviceProvider)) {
                if ($this->selectedService?->key === "cable_tv") {
                    $this->cableTvPackages = ServiceVariant::where('service_id', $this->serviceProvider)->get();
                }

                if ($this->selectedService?->key === "data-subscription") {
                    $this->dataValues = ServiceVariant::where('service_id', $this->serviceProvider)->get();
                }
            }
        }

        return view('control.livewire.orders.create', [
            'services' => Service::orderBy('title')->get(),
            'users' => User::all(),
            'selectedService' => $this->selectedService,
            'giftCards' => GiftCard::all(),
        ]);
    }
}
