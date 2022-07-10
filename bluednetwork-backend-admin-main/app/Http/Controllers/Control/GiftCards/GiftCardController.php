<?php

namespace App\Http\Controllers\Control\GiftCards;

use App\Abstracts\Http\Controllers\ControlController;
use App\Repositories\Common\GiftCardRepository;
use App\Traits\Common\Fillable;
use Illuminate\Http\Request;

class GiftCardController extends ControlController
{
    use Fillable;

    public function __construct(protected GiftCardRepository $giftCardRepository)
    {
        parent::__construct();
    }

    public function store(Request $request)
    {
        $giftCard = $this->giftCardRepository->create($request->toArray());

        if (isset($request->icon) && file_exists($request->icon)) {
            $giftCard->clearMediaCollection('giftcard_logos');
            $giftCard->addMedia($request->icon)
                ->preservingOriginal()->toMediaCollection('giftcard_logos');
        }

        flash("Gift Card created successfully")->success();
        return back();
    }

    public function update(Request $request, string $orderId)
    {
        $giftCard = $this->giftCardRepository->findByHashidOrFail($orderId);
        if (is_null($giftCard)) {
            flash("Invalid Gift Card")->error();
            return back();
        }

        $this->giftCardRepository->update($this->filled($request->toArray()), $giftCard->id);

        if (isset($request->icon) && file_exists($request->icon)) {
            $giftCard->clearMediaCollection('giftcard_logos');
            $giftCard->addMedia($request->icon)
                ->preservingOriginal()->toMediaCollection('giftcard_logos');
        }

        flash("Gift Card updated")->success();
        return back();
    }
}
