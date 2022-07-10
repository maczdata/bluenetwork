<?php

namespace App\Http\Livewire\Control\Coupons;

use App\Models\Finance\Coupon;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class DeleteCoupon extends ModalComponent
{
    public Coupon $coupon;

    public function mount(Coupon $coupon)
    {
        $this->coupon = $coupon;
    }

    public function delete()
    {
        $deleted = Coupon::destroy($this->coupon->id);
        if ($deleted) {
            flash('Coupon deleted successfully')->success();
        } else {
            flash('Unable to delete coupon')->error();
        }
        $this->closeModal();
        return redirect(request()->header('Referer'));
    }

    public function render()
    {
        return view('control.livewire.coupons.delete-coupon');
    }
}
