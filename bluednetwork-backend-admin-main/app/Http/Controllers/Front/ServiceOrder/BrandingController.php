<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           BrandingController.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     22/08/2021, 11:16 PM
 */
namespace App\Http\Controllers\Front\ServiceOrder;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Abstracts\Http\Controllers\PaymentController;

/**
 * Class BrandingController
 * @package App\Http\Controllers\Front\ServiceOrder
 */
class BrandingController extends PaymentController
{
    /**
     * @OA\Post(
     *       path="/service/order/branding/{service_type_slug}",
     *       operationId="order-for-branding-service",
     *       tags={"Payments"},
     *       summary="Order for branding service",
     *       security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *                mediaType="multipart/form-data",
     *                @OA\Schema(
     *                   type="object",
     *                   required={"service_type_slug","service_key","meta[]","design_document[]"},
     *                  @OA\Property(
     *                      property="serive_key",
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
    public function index(Request $request): JsonResponse
    {
        $user = $request->user('frontend');
        $serviceKey  = $request->service_key;
        $serviceTypeSlug = $request->service_type_slug;
        $service = $this->getService($serviceKey, $serviceTypeSlug);
        return $this->serviceExecution($service, $user, $request);
    }
}
