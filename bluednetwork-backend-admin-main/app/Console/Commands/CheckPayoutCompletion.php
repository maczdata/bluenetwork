<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           CheckPayoutCompletion.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Console\Commands;

use App\Repositories\Payout\PayoutRequestRepository;

//use App\Traits\Common\Flutterwave;
use App\Traits\Common\Paystack;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * Class CheckPayoutCompletion
 * @package App\Console\Commands
 */
class CheckPayoutCompletion extends Command
{
    use Paystack;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payout:check_payout_completion';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check paystack payout completion';

    /**
     * @var int
     */
    protected int $completedCount = 0;
    /**
     * @var PayoutRequestRepository
     */
    protected PayoutRequestRepository $payoutRequestRepository;

    /**
     * CheckPayoutCompletion constructor.
     * @param PayoutRequestRepository $payoutRequestRepository
     */
    public function __construct(PayoutRequestRepository $payoutRequestRepository)
    {
        parent::__construct();

        $this->payoutRequestRepository = $payoutRequestRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $payoutRequests = $this->payoutRequestRepository->scopeQuery(function ($query) {
            return $query->where([
                'status' => 1,
                'completed' => 0,
            ])->whereHas('transaction');
        })->get();
        if ($payoutRequests->count()) {
            try {
                DB::beginTransaction();
                foreach ($payoutRequests as $payoutRequest) {
                    $transaction = $payoutRequest->transaction;
                    $transactionRef = $transaction->treference;
                    if (is_null($transactionRef)) {
                        continue;
                    }
                    $verifyData = $this->verifyTransfer(['id' => $transactionRef]);
                    if ($verifyData->status == 'success') {
                        $payoutRequest->update(['completed' => 1]);
                        $payoutRequest->transaction->update(['status' => 1]);
                    }
                }
                DB::commit();
            } catch (\Exception $exception) {
                DB::rollBack();
                logger()->error('Payout completion error : ' . $exception);
            } catch (Throwable $throwable) {
                DB::rollBack();
                logger()->error('Payout completion error : ' . $throwable);
            }
        }
    }
}
