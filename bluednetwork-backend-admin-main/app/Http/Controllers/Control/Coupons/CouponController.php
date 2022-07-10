<?php

namespace App\Http\Controllers\Control\Coupons;

use App\Abstracts\Http\Controllers\ControlController;
use App\Repositories\Common\GiftCardRepository;
use App\Repositories\Common\ServiceRepository;
use App\Repositories\Common\ServiceVariantRepository;
use App\Repositories\Finance\CouponRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use App\Traits\Common\Fillable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use stdClass;
use Throwable;

class CouponController extends ControlController
{
    use Fillable;

    public function __construct(
        protected CouponRepository $couponRepository,
        protected GiftCardRepository $giftCardRepository,
        protected ServiceRepository $serviceRepository,
        protected ServiceVariantRepository $serviceVariantRepository
    ) {
        parent::__construct();
    }

    /**
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {
        return view($this->_config['view']);
    }

    public function store(Request $request)
    {
        $rules = [
            'couponable_type_category' => 'required',
            'couponable_type' => 'required|string',
            'type' => 'required',
            'code' => 'required|unique:coupons,code',
            'percentage_off' => 'nullable',
            'value' => 'nullable',
            'max_uses' => 'nullable',
            'starts_at' => 'required',
            'expires_at' => 'required',
            'enabled' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            flash('Validation error')->error();
            return back()->withErrors($validator);
        }

        DB::beginTransaction();
        try {
            $couponable = new stdClass();
            if ($request->couponable_type_category === "gift_cards") {
                $couponable = $this->giftCardRepository->findByHashidOrFail($request->couponable_type);
            } elseif ($request->couponable_type_category === "services") {
                $couponable = $this->serviceRepository->findByHashidOrFail($request->couponable_type);
            } else {
                $couponable = $this->serviceVariantRepository->findByHashidOrFail($request->couponable_type);
            }

            $payload = [
                'enabled' => $request->enabled,
                'code' => $request->code,
                'description' => $request->description,
                'couponable_type' => get_class($couponable),
                'couponable_id' => $couponable->id,
                'type' => $request->type,
                'value' => $request->value ?? 0,
                'percentage_off' => $request->percentage_off ?? 0,
                'uses' => 0,
                'max_uses' => $request->max_uses ?? 0,
                'starts_at' => Carbon::parse($request->starts_at),
                'expires_at' => Carbon::parse($request->expires_at),
            ];
            $this->couponRepository->create($payload);
            DB::commit();
            flash('Coupon created successfully')->success();
            return redirect()->route('control.coupons.index');
        } catch (Throwable $exception) {
            DB::rollBack();
            logger()->error('Coupon creation error : ' . $exception);
            flash('Unable to create coupon: ' . $exception->getMessage())->error();
            return back();
        }
    }

    public function update(Request $request, string $couponId)
    {
        $coupon = $this->couponRepository->findByHashidOrFail($couponId);
        if (is_null($coupon)) {
            flash('Invalid coupon: ')->error();
            return back();
        }
        $rules = [
            'couponable_type_category' => 'required',
            'couponable_type' => 'required|string',
            'type' => 'required',
            'code' => 'required|unique:coupons,code,' . $coupon->id . ',id',
            'percentage_off' => 'nullable',
            'value' => 'nullable',
            'max_uses' => 'nullable',
            'starts_at' => 'required',
            'expires_at' => 'required',
            'enabled' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            flash('Validation error')->error();
            return back()->withErrors($validator);
        }


        DB::beginTransaction();
        try {
            $couponable = new stdClass();
            if ($request->couponable_type_category === "gift_cards") {
                $couponable = $this->giftCardRepository->findByHashidOrFail($request->couponable_type);
            } elseif ($request->couponable_type_category === "services") {
                $couponable = $this->serviceRepository->findByHashidOrFail($request->couponable_type);
            } else {
                $couponable = $this->serviceVariantRepository->findByHashidOrFail($request->couponable_type);
            }

            $payload = [
                'enabled' => $request->enabled,
                'code' => $request->code,
                'description' => $request->description,
                'couponable_type' => get_class($couponable),
                'couponable_id' => $couponable->id,
                'type' => $request->type,
                'value' => $request->value ?? 0,
                'percentage_off' => $request->percentage_off ?? 0,
                'max_uses' => $request->max_uses ?? 0,
                'starts_at' => Carbon::parse($request->starts_at),
                'expires_at' => Carbon::parse($request->expires_at),
            ];
            $coupon->update($payload);
            DB::commit();
            flash('Coupon updated successfully')->success();
            return redirect()->route('control.coupons.index');
        } catch (Throwable $exception) {
            DB::rollBack();
            logger()->error('Coupon update error : ' . $exception);
            flash('Unable to create coupon: ' . $exception->getMessage())->error();
            return back();
        }
    }
}
