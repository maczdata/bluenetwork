<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           ExchangeController.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Http\Controllers\Front\ServiceOrder;

use App\Abstracts\Http\Controllers\PaymentController;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

/**
 * Class ExchangeController
 * @package App\Http\Controllers\Front\ServiceOrder
 */
class ExchangeController extends PaymentController
{
    /**
     * @OA\Post(
     *       path="/service/order/exchange/giftcard",
     *       operationId="order-for-exchage",
     *       tags={"Payments"},
     *       summary="Order for exchange service",
     *       description="Order for exchange service",
     *       security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *                mediaType="multipart/form-data",
     *                @OA\Schema(
     *                   type="object",
     *                   required={"gift_card_id","gift_card_form_id","gift_card_currency_id","gift_card_rates[]","gift_card_proof_files[]"},
     *                   @OA\Property(
     *                      property="gift_card_id",
     *                      type="integer"
     *                   ),
     *                   @OA\Property(
     *                      property="gift_card_form_id",
     *                      type="string",
     *                   ),
     *                  @OA\Property(
     *                      property="gift_card_currency_id",
     *                      type="string",
     *                   ),
     *                  @OA\Property(
     *                      property="gift_card_rates[]",
     *                      type="array",
     *                      @OA\Items(
     *                         type="object",
     *                         @OA\Property (
     *                           property="rate_id",
     *                           type="string",
     *                         ),
     *                         @OA\Property (
     *                           property="quantity",
     *                           type="integer",
     *                         )
     *                      )
     *                   ),
     *                  @OA\Property(
     *                      property="gift_card_proof_files[]",
     *                      type="array",
     *                      @OA\Items(
     *                        type="file",
     *                        format="binary",
     *                      )
     *                   ),
     *                ),
     *             )
     *       ),
     *     @OA\Response(
     *          response=200,
     *          description="ordered"
     *       ),
     *       @OA\Response(response=422, description="form/data validation error"),
     *       @OA\Response(response=500, description="technical error")
     *     )
     *
     *    process user's new password
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function giftCardExchangeOrder(Request $request): JsonResponse
    {
        $user = $request->user('frontend');
        $service = $this->getService('gift-card-exchange');
        return $this->serviceExecution($service, $user, $request);
    }

    /**
     * @OA\Post(
     *      path="/service/order/exchange/airtimetocash",
     *       operationId="service-airtime-to-cash",
     *       tags={"Payments"},
     *       summary="Order airtime to cash service",
     *       description="Order airtime to cash service",
     *       security={{"bearerAuth":{}}},
     *     @OA\Response(
     *          response=200,
     *          description="service ordered"
     *       ),
     *       @OA\Response(response=422, description="form/data validation error"),
     *       @OA\Response(response=500, description="technical error")
     *     )
     *
     *    process user's new password
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function airtimeToCash(Request $request): JsonResponse
    {
        $user = $request->user('frontend');
        $service = $this->getService('airtime-for-cash');
        return $this->serviceExecution($service, $user, $request);
    }
}
