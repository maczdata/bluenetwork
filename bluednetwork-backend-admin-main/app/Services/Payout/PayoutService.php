<?php

namespace App\Services\Payout;

use Illuminate\Support\Str;
use App\Models\Users\User;
use App\Traits\Common\Paystack;
use App\Models\Finance\PayoutRequest;
use App\Models\Finance\WithdrawalSetup;

class PayoutService
{
    use Paystack;

    public function list()
    {
        return PayoutRequest::orderby('id', 'desc')->get();
    }

    public function approve($id)
    {
        $payout = PayoutRequest::where('id', $id)->first();
        $existingWithdrawalSetup = WithdrawalSetup::where('withdrawable_id', $payout->ownerable->id)->first();
        $this->transfer([
            "source" => "balance",
            "reason" => $payout->ownerable->first_name . " withdrawal",
            "amount" => $payout->amount,
            "recipient" => $existingWithdrawalSetup->recipient,
        ]);

        return PayoutRequest::where('id', $id)->update([
            'status' => 1,
        ]);
    }

    public function delined($id)
    {
        return PayoutRequest::where('id', $id)->update([
            'status' => 2,
        ]);
    }
}
