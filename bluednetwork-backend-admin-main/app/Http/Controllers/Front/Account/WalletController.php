<?php

/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           WalletController.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Http\Controllers\Front\Account;

use App\Abstracts\Http\Controllers\FrontController;
use App\Notifications\WalletCredited;
use App\Traits\Common\Flutterwave;
use App\Traits\Common\Paystack;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Repositories\Common\WalletRepository;
use App\Repositories\Common\TransactionRepository;
use Illuminate\Support\Facades\Validator;
use Prettus\Repository\Exceptions\RepositoryException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class WalletController
 * @package App\Http\Controllers\Front\Account
 */
class WalletController extends FrontController
{
    use Paystack;
    /**
     * WalletController constructor.
     * @param WalletRepository $walletRepository
     * @param TransactionRepository $transactionRepository
     */
    public function __construct(
        protected WalletRepository $walletRepository,
        protected TransactionRepository $transactionRepository
    ) {
        parent::__construct();
    }

    /**
     * @OA\Post(
     *      path="/account/wallet/credit",
     *      operationId="wallet_credit",
     *      tags={"Account"},
     *      summary="wallet crediting",
     *      description="Credit wallet",
     *      security={{"bearerAuth":{}}},
     *       @OA\Parameter(
     *          name="amount",
     *          description="Amount to credit in wallet",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *    @OA\Parameter(
     *          name="ref_number",
     *          description="payment ref number",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
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
    public function credit(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'ref_number' => 'required',
            'amount' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json($this->failedValidation($validator), 422);
        }

        $amount = $request->input('amount');
        $refNumber = $request->ref_number;
        $user = $request->user('frontend');
        $transaction = $this->transactionRepository->scopeQuery(function ($query) use ($refNumber) {
            return $query->whereHasMeta('psk_transaction_id')->whereMeta('psk_transaction_id', $refNumber);
        })->count();
        if ($transaction != false) {
            return api()->status(403)->message('Fraudulent activity detected')->respond();
        }

        try {
            $paymentData = $this->verifyPayment($refNumber, round($amount, 2));

            if (strtolower($user->email) != strtolower(optional($paymentData->customer)->email)) {
                return api()->status(403)->message('Payment could not be verified')->respond();
            }
            $this->walletRepository->deposit($user, $amount, ['psk_transaction_id' => $refNumber]);
            $user->notify(new WalletCredited($amount, $user->id, 'paystack'));
            return api()->status(200)->message('Your wallet has been credited')->respond();
        } catch (\Exception $exception) {
            logger()->error('wallet crediting error : ' . $exception);
            return api()->status(500)->message('Your wallet could not be credited, please try again. if issue persist, contact us')->respond();
        }
    }
}
