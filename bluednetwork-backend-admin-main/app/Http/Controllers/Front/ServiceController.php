<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           ServiceController.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     17/08/2021, 1:28 AM
 */

namespace App\Http\Controllers\Front;

use App\Abstracts\Http\Controllers\FrontController;
use App\Http\Resources\Front\ServiceCollection;
use App\Repositories\Common\FeaturizeRepository;
use App\Repositories\Common\ServiceRepository;
use App\Repositories\Common\ServiceTypeRepository;
use App\Repositories\Common\ServiceVariantRepository;
use App\Transformers\Common\FeaturizeTransformer;
use App\Transformers\Common\ServiceTransformer;
use App\Transformers\Common\ServiceTypeTransformer;
use App\Transformers\Common\ServiceVariantTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
/**
 * Class ServiceController
 *
 * @package App\Http\Controllers\Front
 */
class ServiceController extends FrontController
{
    /**
     * ServiceController constructor.
     *
     * @param ServiceTypeRepository $serviceTypeRepository
     * @param ServiceRepository $serviceRepository
     * @param ServiceVariantRepository $serviceVariantRepository
     * @param FeaturizeRepository $featurizeRepository
     */
    public function __construct(
        protected ServiceTypeRepository    $serviceTypeRepository,
        protected ServiceRepository        $serviceRepository,
        protected ServiceVariantRepository $serviceVariantRepository,
        protected FeaturizeRepository      $featurizeRepository
    ) {
        parent::__construct();
    }

    public function index(): JsonResponse
    {
        $services = $this->serviceRepository->filterWhenNotPaginated()->get();
        return (new ServiceCollection($services))->additional([
            'message' => "List of services",
        ])->response();
    }
    /**
     * @OA\Get(
     *      path="/service/types",
     *      operationId="service_types",
     *      tags={"Service"},
     *      summary="service types",
     *      description="return all service types",
     *     @OA\Response(response=200,description="fetched all service types"),
     *     @OA\Response(response=500, description="Bad request"),
     *     )
     * @return JsonResponse
     */
    public function serviceTypes(): JsonResponse
    {
        try {
            $serviceTypes = $this->serviceTypeRepository->with(['services' => function ($query) {
                $query->whereNull('parent_id');
            }])->scopeQuery(function ($query) {
                return $query->enabled();
            })->get();

            return api()->data(fractal($serviceTypes, ServiceTypeTransformer::class)->toArray())->respond();
        } catch (\Exception $ex) {
            logger()->error('Error fetching service types : ' . $ex);
            return api()->status(500)->message('Error fetching service types')->respond();
        }
    }

    /**
     * @OA\Get(
     *      path="/service/all_service",
     *      operationId="services",
     *      tags={"Service"},
     *      summary="all services",
     *      description="return all services",
     *      @OA\Parameter(
     *          name="service_type_id",
     *          description="service type id",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *       ),
     *     @OA\Response(response=200,description="fetched all service type services"),
     *     @OA\Response(response=500, description="Bad request"),
     *     )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function services(Request $request): JsonResponse
    {
        $serviceTypeId = $request->service_type_id ?? null;
        try {
            
            $services = $this->serviceRepository->scopeQuery(function ($query) use ($serviceTypeId) {
                   return $query->whereNull('parent_id')->whereHas('service_type', function ($query) use ($serviceTypeId) {
                    return $query->where(['id' => $serviceTypeId])->orWhere('slug', $serviceTypeId);
                })->enabled();
            })->get();
            //,'variants'
            return api()->status(200)->data(
                fractal($services, ServiceTransformer::class)
                    ->parseIncludes(['children'])->toArray()
            )->respond();
        } catch (\Exception $ex) {
            logger()->error('Error fetching services : ' . $ex);
            return api()->status(500)->message('Error fetching services')->respond();
        }
    }

    /**
     * @OA\Get(
     *      path="/service/single/{service_key}",
     *      operationId="service_detail",
     *      tags={"Service"},
     *      summary="single service detail",
     *      description="return single service detail",
     *      @OA\Parameter(
     *          name="service_key",
     *          description="service key",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *       ),
     *     @OA\Response(response=200,description="fetched service"),
     *     @OA\Response(response=500, description="Bad request"),
     *     )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function serviceDetail(Request $request): JsonResponse
    {
        $serviceKey = $request->service_key ?? null;
        try {
            $service = $this->serviceRepository->scopeQuery(function ($query) use ($serviceKey) {
                return $query->where(['key' => $serviceKey])->enabled();
                /*return $query->when($serviceKey, function ($query) use ($serviceKey) {
                    return $query->where(['key' => $serviceKey]);
                })->whereHas('service_type')->enabled();*/
            })->first();
            //'variants',
            return api()->status(200)->data(fractal($service, ServiceTransformer::class)
                ->parseIncludes(['children', 'fields', 'addons'])->toArray())->respond();
        } catch (\Exception $ex) {
            logger()->error('Error fetching service : ' . $ex);
            return api()->status(500)->message('Error fetching service')->respond();
        }
    }

    /**
     * @OA\Get(
     *      path="/service/single/{service_key}/variants",
     *      operationId="service_variants",
     *      tags={"Service"},
     *      summary="single service variants",
     *      description="get all variants for single service",
     *      @OA\Parameter(
     *          name="service_key",
     *          description="service key",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *       ),
     *     @OA\Response(response=200,description="fetched service"),
     *     @OA\Response(response=500, description="Bad request"),
     *     )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function serviceVariations(Request $request): JsonResponse
    {
        $serviceKey = $request->service_key ?? null;
        try {
            $serviceVariants = $this->serviceVariantRepository->scopeQuery(function ($query) use ($serviceKey) {
                return $query->whereHas('service', function ($query) use ($serviceKey) {
                    return $query->where(['key' => $serviceKey])->enabled();
                })->enabled();
            })->all();

            return api()->status(200)->data(fractal($serviceVariants, ServiceVariantTransformer::class)->parseIncludes(['children', 'addons', 'fields'])->toArray())->respond();
        } catch (\Exception $ex) {
            logger()->error('Error fetching service variants: ' . $ex);
            return api()->status(500)->message('Error fetching service variants')->respond();
        }
    }

    /**
     * @OA\Get(
     *      path="/service/features/{featureable_id}/{type}",
     *      operationId="service_features",
     *      tags={"Service"},
     *      summary="single service features",
     *      description="get all features for single service",
     *      @OA\Parameter(
     *          name="featureable_id",
     *          description="Featurable id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *       ),
     *     @OA\Parameter(
     *          name="type",
     *          description="type of feature",
     *          in="path",
     *          @OA\Schema(
     *              type="string",
     *              enum={"service","service_variant"}
     *          )
     *      ),
     *     @OA\Response(response=200,description="fetched features"),
     *     @OA\Response(response=500, description="Bad request"),
     *     )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function serviceFeatures(Request $request): JsonResponse
    {

        /*$validator = Validator::make($request->all(), [
            'featureable_id'=>'required',
            'type'=>'required|in:service,variant'
        ]);

        if ($validator->fails()) {
            return response()->json($this->failedValidation($validator), 422);
        }*/
        $featureableKey = $request->featureable_id;
        $type = $request->type;
        try {
            $serviceFeatures = $this->featurizeRepository->scopeQuery(function ($query) use ($type, $featureableKey) {
                return $query->where(['featureable_type' => $type, 'featureable_id' => $featureableKey]);
            })->all();

            return api()->status(200)->data(fractal($serviceFeatures, FeaturizeTransformer::class)->toArray())->respond();
        } catch (\Exception $ex) {
            logger()->error('Error fetching service features: ' . $ex);
            return api()->status(500)->message('Error fetching service features')->respond();
        }
    }
}
