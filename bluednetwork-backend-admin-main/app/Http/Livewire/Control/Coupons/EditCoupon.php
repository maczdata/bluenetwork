<?php

namespace App\Http\Livewire\Control\Coupons;

use App\Models\Common\GiftCard;
use App\Models\Common\Service;
use App\Models\Finance\Coupon;
use App\Repositories\Common\GiftCardRepository;
use App\Repositories\Common\ServiceRepository;
use App\Repositories\Common\ServiceVariantRepository;
use LivewireUI\Modal\ModalComponent;

class EditCoupon extends ModalComponent
{
    public Coupon $coupon;
    private GiftCardRepository $giftCardRepository;
    private ServiceRepository $serviceRepository;
    private ServiceVariantRepository $serviceVariantRepository;

    public $couponableTypeCategory = 'gift_cards';
    public $couponableTypeOptions;
    public $type;

    public function mount(Coupon $coupon)
    {
        $this->type = $coupon->type;
        if ($coupon->couponable_type === get_class(new GiftCard())) {
            $this->couponableTypeCategory = "gift_cards";
        } elseif ($coupon->couponable_type === get_class(new Service())) {
            $this->couponableTypeCategory = "services";
        } else {
            $this->couponableTypeCategory = "service_variants";
        }

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

        $this->coupon = $coupon;
    }


    public function render()
    {
        return view('control.livewire.coupons.edit-coupon');
    }
}
