<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           BillController.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:57 PM
 */

namespace App\Http\Controllers\Front\ServiceOrder;

use App\Abstracts\Http\Controllers\FrontController;
use App\Abstracts\Http\Controllers\PaymentController;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class BillController
 * @package App\Http\Controllers\Front\Serviceorder
 */
class BillController extends PaymentController
{
    /**
     * @OA\Post(
     *       path="/service/order/{service_key}/bills",
     *       operationId="service-pay",
     *       tags={"Payments"},
     *       summary="pay for bills service",
     *       description="pay for bill service",
     *       security={{"bearerAuth":{}}},
     *      @OA\Parameter(
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
     *       @OA\Parameter(
     *          name="token",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *           type="string"
     *          )
     *        ),
     *     @OA\Response(
     *          response=200,
     *          description="payed for"
     *       ),
     *       @OA\Response(response=422, description="form/data validation error"),
     *       @OA\Response(response=500, description="technical error")
     *     )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user('frontend');
        if (is_null($user->phone_number)) {
            return api()->status(403)->message('Phone number is required')->respond();
        }
        $serviceKey = $request->service_key;
        $service = $this->getService($serviceKey);
        $rules = [
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
