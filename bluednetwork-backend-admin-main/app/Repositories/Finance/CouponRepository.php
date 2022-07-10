<?php

namespace App\Repositories\Finance;

use App\Eloquent\Repository;
use App\Models\Finance\Coupon;
use App\Models\Finance\CouponUser;
use App\Models\Users\User;
use stdClass;

/**
 * Class CouponRepository
 * @package App\Repositories\Finance
 */
class CouponRepository extends Repository
{
    /**
     * @return string
     */
    public function model()
    {
        return Coupon::class;
    }

    public function discount(Coupon $coupon, float $amount)
    {
        if ($coupon->type === 'fixed') {
            return $coupon->value;
        } elseif ($coupon->type === 'percentage') {
            return ($coupon->percentage_off / 100) * $amount;
        } else {
            return 0;
        }
    }

    public function checkIfCouponIsUsable(
        string $couponCode,
        User $user,
        float $amount,

    ) {
        $response = (object) [
            'status' => false,
            'message' => '',
        ];
        $coupon = Coupon::where(['code' => $couponCode])->first();

        if (is_null($coupon)) {
            $response->message = "Coupon is not available";
            return $response;
        }

        if ($amount < $this->discount($coupon, $amount)) {
            $response->message = "This amount is not valid for use with this coupon";
            return $response;
        }
        if (now()->gt($coupon->expired_at)) {
            $response->message = "This coupon has expired, and no longer valid";
            return $response;
        }

        if ($coupon->uses >= $coupon->max_uses) {
            $response->message = "Coupon has reached it maximum usage limit";
            return $response;
        }

        if (
            $coupon->couponable_type !== get_class($service) ||
            $coupon->couponable_id !== $service->id
        ) {
            $response->message = "This coupon is not valid for this product";
        }

        $checkUserHasUsedCouponBefore = CouponUser::where(['coupon_id' => $coupon->id, 'user_id' => $user->id])->exists();

        if ($checkUserHasUsedCouponBefore) {
            $response->message = "This coupon has been used by this user already";
            return $response;
        }

        $response->status = true;
        $response->message = "Coupon is valid, and can be used";

        return $response;
    }

    public function useCoupon(string $couponCode, User $user, float $amount, stdClass $service)
    {
        $validateRequest = $this->checkIfCouponIsUsable($couponCode, $user, $amount, $service);

        if (!$validateRequest->status) {
            return $validateRequest;
        }
        $coupon = Coupon::where(['code' => $couponCode])->first();
        CouponUser::create([
            'user_id' => $user->id,
            'coupon_id' => $coupon->id,
            'redeemed_at' => now(),
        ]);
        $coupon->increment('uses');
        $coupon->save();

        $response = (object) [
            'status' => true,
            'amount' => $this->discount($coupon, $amount),
            'message' => 'Coupon has been redeem',
        ];

        return $response;
    }
}
