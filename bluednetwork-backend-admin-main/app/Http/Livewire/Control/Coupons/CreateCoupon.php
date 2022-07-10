<?php

namespace App\Http\Livewire\Control\Coupons;

use App\Repositories\Common\GiftCardRepository;
use App\Repositories\Common\ServiceRepository;
use App\Repositories\Common\ServiceVariantRepository;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class CreateCoupon extends ModalComponent
{
    private GiftCardRepository $giftCardRepository;
    private ServiceRepository $serviceRepository;
    private ServiceVariantRepository $serviceVariantRepository;
    public $couponableTypeCategory = 'gift_cards';
    public $couponableType;
    public $couponableTypeOptions;
    public $type = "fixed";

    public function render()
    {
        $this->giftCardRepository = app(GiftCardRepository::class);
        $this->serviceRepository = app(ServiceRepository::class);
        $this->serviceVariantRepository = app(ServiceVariantRepository::class);
        if ($this->couponableTypeCategory === 'gift_cards') {
            $this->couponableTypeOptions = $this->giftCardRepository->select('id', 'title')->get();
        } elseif ($this->couponableTypeCategory === 'services') {
            $this->couponableTypeOptions = $this->serviceRepository->select('id', 'title')->get();
        } else {
            $this->couponableTypeOptions = $this->serviceVariantRepository->select('id', 'title')->get();
        }
        return view('control.livewire.coupons.create-coupon', [
            'couponableTypeCategory' => $this->couponableTypeCategory,
            'couponableTypeOptions' => $this->couponableTypeOptions,
            'type' => $this->type,
        ]);
    }
}
