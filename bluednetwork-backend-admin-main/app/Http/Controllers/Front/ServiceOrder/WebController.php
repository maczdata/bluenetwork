<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           WebController.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:56 PM
 */

namespace App\Http\Controllers\Front\ServiceOrder;

use App\Abstracts\Http\Controllers\PaymentController;
use App\Repositories\Common\ServiceRepository;
use App\Repositories\Common\ServiceTypeRepository;
use App\Repositories\Common\TransactionRepository;
use App\Repositories\Common\WalletRepository;
use App\Traits\Common\Flutterwave;
use App\Traits\Common\Paystack;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class WebController
 *
 * @package App\Http\Controllers\Front\ServiceOrder
 */
class WebController extends PaymentController
{
    use Paystack;

    /**
     * PrintingController constructor.
     *
     * @param ServiceTypeRepository $serviceTypeRepository
     * @param ServiceRepository $serviceRepository
     * @param WalletRepository $walletRepository
     * @param TransactionRepository $transactionRepository
     */
    public function __construct(
        protected ServiceTypeRepository $serviceTypeRepository,
        protected ServiceRepository $serviceRepository,
        protected WalletRepository $walletRepository,
        protected TransactionRepository $transactionRepository
    ) {
        parent::__construct($serviceRepository, $walletRepository, $transactionRepository);
    }

    /**
     * @OA\Post(
     *       path="/service/order/{service_key}/web",
     *       operationId="order-for-web",
     *       tags={"Payments"},
     *       summary="Order for web",
     *       security={{"bearerAuth":{}}},
     *        @OA\Parameter(
     *          name="service_key",
     *          description="service key",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *       ),
     *     @OA\Parameter(
     *          name="transaction_id",
     *          description="Transaction id",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *       ),
     *     @OA\Parameter(
     *          name="mode_of_payment",
     *          description="Mode of payment",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *              enum={"wallet","flutterwave"}
     *          )
     *       ),
     *     @OA\Response(
     *          response=200,
     *          description="ordered"
     *       ),
     *       @OA\Response(response=422, description="form/data validation error"),
     *       @OA\Response(response=500, description="technical error")
     *     )
     *
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user('frontend');
        $packageKey  = $request->service_key;
        $service = $this->getService($packageKey);
        $rules = [
            //'variant' => 'web-design-blog'
            'mode_of_payment' => 'required|in:wallet,flutterwave',
            'transaction_id' => 'required_if:mode_of_payment,flutterwave'
        ];
        $ruleMessages = [
            'mode_of_payment.required' => 'mode of payment is required',
            'mode_of_payment.in' => 'Mode of payment can only be Wallet or flutterwave'
        ];
        return $this->serviceExecution($service, $user, $request, $rules, $ruleMessages);
    }
}
