<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           WithdrawalController.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Http\Controllers\Front\Account;

use App\Abstracts\Http\Controllers\FrontController;
use App\Repositories\Common\WalletRepository;
use App\Repositories\Payout\PayoutRequestRepository;
use App\Transformers\Common\PayoutTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

/**
 * Class WithdrawalController
 * @package App\Http\Controllers\Front\Account
 */
class WithdrawalController extends FrontController
{

    /**
     * WithdrawalController constructor.
     * @param PayoutRequestRepository $payoutRequestRepository
     * @param WalletRepository $walletRepository
     */
    public function __construct(
        protected PayoutRequestRepository $payoutRequestRepository,
        protected WalletRepository $walletRepository
    ) {
        parent::__construct();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user('frontend');
        $payouts = $user->payouts;
        return api()->status(200)->data(fractal($payouts, PayoutTransformer::class)->toArray())->respond();
    }

    /**
     * @OA\Post(
     *      path="/account/wallet/debit",
     *      operationId="wallet_debit",
     *      tags={"Account"},
     *      summary="wallet crediting",
     *      description="Credit wallet",
     *      security={{"bearerAuth":{}}},
     *       @OA\Parameter(
     *          name="amount",
     *          description="Amount to debit in wallet",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *    @OA\Parameter(
     *          name="withdrawal_pin",
     *          description="Withdrawal pin",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="token",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *           type="string"
     *          )
     *       ),
     *     @OA\Response(response=200,description="wallet credited successfully"),
     *     @OA\Response(response=400, description="Bad request"),
     *     )
     *
     * credit wallet
     *
     * @param Request $request
     * @return JsonResponse
     * @throws RepositoryException
     */
    public function processRequest(Request $request): JsonResponse
    {
        $user = $request->user('frontend');
        $validator = Validator::make($request->all(), [
            'amount' => 'required',
            'withdrawal_pin' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($this->failedValidation($validator), 422);
        }
        if ($user->profile?->withdrawal_pin === (int) $request->withdrawal_pin) {
            return api()->status(422)->message('Wrong withdrawal pin')->respond();
        }

        $amount = $request->amount;
        if ($amount > $user->profile?->accountLevel?->transaction_limit) {
            return api()->status(422)
                ->message('Sorry, your account is currently limited to transactions equal or less than '
                    . number_format($user->profile->accountLevel->withdrawal_limit))->respond();
        }

        if ($amount > $user->profile?->accountLevel?->withdrawal_limit) {
            return api()->status(422)
                ->message('Sorry, your account is currently limited to withdrawals equal or less than '
                    . number_format($user->profile->accountLevel->withdrawal_limit))->respond();
        }

        if (!$user->wallet->canWithdraw($amount)) {
            return api()->status(422)->message('You have insufficient funds in your wallet for payout')->respond();
        }

        DB::beginTransaction();
        try {
            $p_request = $this->payoutRequestRepository->makePayout($user, $amount, 0);
      
            $this->walletRepository->withDraw($user, $amount);

            DB::commit();
            return api()->status(200)->message('Payout requested, and awaiting approval')->respond();
        } catch (\Exception $exception) {
            DB::rollBack();
            logger()->error('payout request exception error : ' . $exception);
            return api()->status(500)->message('There was an error making payout'. $exception->getMessage())->respond();
        }
    }
}
