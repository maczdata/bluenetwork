<?php

namespace App\Http\Controllers\Front;

use App\Abstracts\Http\Controllers\FrontController;
use App\Repositories\Common\GiftCardRepository;
use App\Repositories\Common\ServiceRepository;
use App\Repositories\Common\ServiceVariantRepository;
use App\Repositories\Finance\CouponRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CouponController extends FrontController
{
    public function __construct(
        protected CouponRepository $couponRepository,
        protected GiftCardRepository $giftCardRepository,
        protected ServiceRepository $serviceRepository,
        protected ServiceVariantRepository $serviceVariantRepository
    ) {
        parent::__construct();
    }


    /**
     * @OA\Post(
     *      path="/account/coupons/verify",
     *      operationId="order_verify_coupon",
     *      tags={"Common"},
     *      summary="Verify Coupon ",
     *       security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="coupon_code",
     *          description="Coupon Code",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *    @OA\Parameter(
     *          name="amount",
     *          description="amount",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="integer",
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="item_type",
     *          description="Item Type",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *              enum={"gift_cards","services", "service_variants"}
     *          )
     *       ),
     *     @OA\Parameter(
     *          name="item_id",
     *          description="Item Id",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="integer",
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="token",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *           type="string"
     *          )
     *        ),
     *     @OA\Response(response=200,description="fetched data"),
     *     @OA\Response(response=500, description="Bad request"),
     *     )
     */
    public function verifyCoupon(Request $request)
    {
        set_time_limit(300);
        $user = Auth::guard('frontend')->user();;

        // $validator = Validator::make($request->all(), [
        //     'coupon_code' => ['required', 'string', 'max:255'],
        //     'amount' => ['required'],
        //     'item_type' => 'required,|in:gift_cards,services,service_variants',
        //     'item_id' => ["required"],
        // ], []);

        // if ($validator->fails()) {
        //     return response()->json($this->failedValidation($validator), 422);
        // }

        $service = null;
        if ($request->item_type === "gift_cards") {
            $service = $this->giftCardRepository->find($request->item_id);
        } elseif ($request->item_type === "services") {
            $service = $this->serviceRepository->find($request->item_id);
        } else {
            $service = $this->serviceVariantRepository->find($request->item_id);
        }

        if (is_null($service)) {
            return api()->status(400)->message('The item is invalid')->respond();
        }

        $checkCoupon = $this->couponRepository->checkIfCouponIsUsable(
            $request->coupon_code,
            $user,
            (float)$request->amount,
        );

        if (!$checkCoupon->status) {
            return api()->status(400)->message($checkCoupon->message)->respond();
        }
        return api()->status(200)->message($checkCoupon->message)->respond();
    }
}
