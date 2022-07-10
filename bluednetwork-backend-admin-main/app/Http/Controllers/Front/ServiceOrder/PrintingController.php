<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           PrintingController.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     18/08/2021, 4:51 AM
 */

namespace App\Http\Controllers\Front\ServiceOrder;

use App\Abstracts\Http\Controllers\PaymentController;
use App\Exceptions\PaymentException;
use App\Exceptions\WalletException;
use App\Repositories\Common\ServiceRepository;
use App\Repositories\Common\ServiceTypeRepository;
use App\Repositories\Common\TransactionRepository;
use App\Repositories\Common\WalletRepository;
use App\Services\Generators\OrderNumberSequencer;
use App\Traits\Common\Flutterwave;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Abstracts\Http\Controllers\FrontController;

/**
 * Class PrintingController
 *
 * @package App\Http\Controllers\Front\ServiceOrder
 */
class PrintingController extends PaymentController
{
    use Flutterwave;

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
     *       path="/service/order/printing",
     *       operationId="order-for-printing",
     *       tags={"Payments"},
     *       summary="Order for printing service",
     *       security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *                mediaType="multipart/form-data",
     *                @OA\Schema(
     *                   type="object",
     *                   required={"package_key","meta[]","design_document[]"},
     *                  @OA\Property(
     *                      property="package_key",
     *                      type="string",
     *                   ),
     *                  @OA\Property(
     *                      property="meta[]",
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
     *                      property="design_document[]",
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
    public function makeOrder(Request $request): JsonResponse
    {
        $user = $request->user('frontend');
        //$serviceTypeKey = $request->service_type_key;
        $packageKey = $request->service_key;
        $service = $this->getService($packageKey, 'bd-printing');
        return $this->serviceExecution($service, $user, $request);
    }
}
