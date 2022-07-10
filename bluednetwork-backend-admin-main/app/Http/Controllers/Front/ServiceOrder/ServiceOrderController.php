<?php

namespace App\Http\Controllers\Front\ServiceOrder;

use App\Abstracts\Http\Controllers\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class ServiceOrderController extends PaymentController
{
    /**
     * @OA\Post(
     *      path="/service/order/create",
     *      operationId="service_orders",
     *      tags={"Payments"},
     *      summary="single service orders",
     *      description="Create order for single service",
     *       security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *                mediaType="multipart/form-data",
     *                @OA\Schema(
     *                   type="object",
     *                   required={"service_type_slug","service_key","custom_fields[]"},
     *                  @OA\Property(
     *                      property="service_key",
     *                      type="string",
     *                   ),
     *                  @OA\Property(
     *                      property="service_type_slug",
     *                      type="string",
     *                   ),
     *                  @OA\Property(
     *                      property="custom_fields[]",
     *                      type="array",
     *                          @OA\Items(
     *                        type="key",
     *                        format="string",
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
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'service_key' => 'required',
                'mode_of_payment' => 'required|in:wallet,flutterwave,paystack',
                // 'custom_fields' => 'required|array',
            ]);

            if ($validator->fails()) {
                return response()->json($this->failedValidation($validator), 422);
            }
            $user = $request->user('frontend');
            $serviceKey  = $request->service_key;
            $serviceTypeSlug = $request->service_type_slug;
            $service = $this->getService($serviceKey, $serviceTypeSlug);
            
            return $this->serviceExecution($service, $user, $request);
        } catch (Throwable $exception) {
            logger()->error($request->service_key . ' order error : ' . $exception);
            return api()->status(400)->message($exception->getMessage())->respond();
        }
    }
}
