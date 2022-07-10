<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           PreviewController.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     16/08/2021, 12:53 PM
 */

namespace App\Http\Controllers\Front\ServiceOrder;

use App\Abstracts\Http\Controllers\PaymentController;
use App\Services\ServiceHandler\Preview;
use Exception;
use Illuminate\Http\Request;

/**
 * Class PreviewController
 * @package App\Http\Controllers\Front\ServiceOrder
 */
class PreviewController extends PaymentController
{

    /**
     * @OA\Post(
     *       path="/service/order/preview",
     *       operationId="service-pay-preview",
     *       tags={"Payments"},
     *       summary="preview payment service before you pay",
     *       description="preview payment service before you pay",
     *       security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="service_key",
     *          description="service key",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *       ),
     *     @OA\Parameter(
     *          name="service_type",
     *          description="service type",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *       ),
     *     @OA\Parameter(
     *          name="service_type_slug",
     *          description="service type slug",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *       ),
     * 
     *   @OA\Parameter(
     *          name="token",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *           type="string"
     *          )
     *       ),
     *     @OA\Response(
     *          response=200,
     *          description="preview returned"
     *       ),
     *       @OA\Response(response=422, description="form/data validation error"),
     *       @OA\Response(response=500, description="technical error")
     *     )
     *
     *    Preview order
     *
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function index(Request $request): mixed
    {
        auth('frontend')->user();
        $serviceKey = $request->service_key;
        $serviceTypeSlug = $request->service_type_slug;
        $service = $this->getService($serviceKey, $serviceTypeSlug);
        
        $serviceConfig = getServiceConfig($service);
        if (!$service->enabled || ($service->enabled && (!$validClass = $this->validateServiceClass($service)))) {
            return api()->status(404)->message('Service is currently not available')->respond();
        }
        $requiresPreview = $service->getMeta('requires_preview');
        
        $variant = null;
        if ($service->variants->count()) {
            if (is_null($request->variant_key)) {
                return api()->status(404)->message('Service requires variant')->respond();
            }

            if (is_null($variant = $service->variants()->where('key', $request->variant_key)->first())) {
                return api()->status(404)->message('Service variant is invalid')->respond();
            }
            //$requiresPreview = $variant->getMeta('requires_preview');
        }
       
        $serviceClass = new $validClass($service, $this->walletRepository, $this->transactionRepository);
        if (!$requiresPreview || ($requiresPreview == false) || (($requiresPreview == true) && !method_exists($serviceClass, 'preview'))) {
            return api()->status(403)->message('Service does not require preview')->respond();
        }
        if (method_exists($serviceClass, 'setVariant')) {
            $serviceClass->setVariant($variant);
        }
        if (method_exists($serviceClass, 'validate') && !is_bool($validator = $serviceClass->validate($request->toArray()))) {
            return response()->json($this->failedValidation($validator), 422);
        }

        return $serviceClass->preview($request);
    }

     /**
     * @OA\Post(
     *       path="/service/order/preview2",
     *       operationId="service-pay-preview-2",
     *       tags={"Payments"},
     *       summary="preview payment service before you pay",
     *       description="preview payment service before you pay",
     *       security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *          name="service_key",
     *          description="service key e.g gotv , dstv , startimes",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *       ),
     *      @OA\Parameter(
     *          name="billerNo",
     *          description="This could be the meter number or smart card number",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *       ),
     *      @OA\Parameter(
     *          name="type",
     *          description="Usually postpaid or prepaid, an only needed for electric bills",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *       ),
     *    @OA\Parameter(
     *          name="token",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *           type="string"
     *          )
     *       ),
     *     @OA\Response(
     *          response=200,
     *          description="preview returned"
     *       ),
     *       @OA\Response(response=422, description="form/data validation error"),
     *       @OA\Response(response=500, description="technical error")
     *     )
     *
     *    Preview order
     *
     */
    public function preview(Request $request)
    {
        auth('frontend')->user();
        // $serviceKey = $request->service_key;
        // $serviceTypeSlug = $request->service_type_slug;
        // $service = $this->getService($serviceKey, $serviceTypeSlug);
        
        $preview= new Preview;

        $serviceID = $request->service_key;
        $billersCode= $request->billerNo;
        $type = null;
        if(isset($request->type))
        {
            $type = $request->type;
        }

    try{

        $see_details = $preview->verify($serviceID, $billersCode, $type);
        $preview = json_decode($see_details);

        
        if(isset($preview->content->error))
        {
            $error = $preview->content->error;
            return api()->status(400)->message("$error")->respond();
        }else{
            return $preview;
        }

    } catch (\Exception $e) {
    
        logger()->error("Order preview error : " . $e);
        return api()->status(500)->message("Order preview error : " . $e->getMessage())->respond();
    }

    }
}
