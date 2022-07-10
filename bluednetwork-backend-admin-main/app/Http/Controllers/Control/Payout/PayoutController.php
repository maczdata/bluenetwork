<?php

namespace App\Http\Controllers\Control\Payout;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\Payout\PayoutService;
use App\Abstracts\Http\Controllers\ControlController;

class PayoutController extends ControlController
{
    //
    public function index()
    {
        $payoutService = new PayoutService();
        $payouts = $payoutService->list();
        return view('control.payout.list')->with(compact('payouts'));
    }

    public function approve($id)
    {
        DB::beginTransaction();
        try {
            $payoutService = new PayoutService();

            $payoutService->approve($id);

            DB::commit();

            flash("This payout request has been approved")->success();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            logger()->error('payout error : ' . $e);
            flash($e->getMessage())->error();
            return redirect()->back();
        }
    }

    public function decline($id)
    {
        DB::beginTransaction();
        try {
            $payoutService = new PayoutService();

            $payoutService->declined($id);

            DB::commit();

            flash("This payout request has been declined")->success();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            logger()->error('payout error : ' . $e);
            flash($e->getMessage())->error();
            return redirect()->back();
        }
    }

    public function delete()
    {
        DB::beginTransaction();
        try {
            $payoutService = new PayoutService();

            $payoutService->approve($id);

            DB::commit();

            flash("This payout request has been deleted")->success();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            logger()->error('payout error : ' . $e);
            flash($e->getMessage())->error();
            return redirect()->back();
        }
    }
}
