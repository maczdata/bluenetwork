<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           PaymentController.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     20/08/2021, 7:30 PM
 */

namespace App\Abstracts\Http\Controllers;

use App\Abstracts\Http\Controllers\FrontController;
use App\Exceptions\PaymentException;
use App\Exceptions\WalletException;
use App\Repositories\Common\ServiceRepository;
use App\Repositories\Common\TransactionRepository;
use App\Repositories\Common\WalletRepository;
use App\Services\ServiceHandler\CustomServiceHandler;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Prettus\Repository\Exceptions\RepositoryException;

/**
 * Class PaymentController
 *
 * @package App\Abstracts\Http\Controllers
 */
class PaymentController extends FrontController
{
    public function __construct(
        protected ServiceRepository     $serviceRepository,
        protected WalletRepository      $walletRepository,
        protected TransactionRepository $transactionRepository
    ) {
        parent::__construct();
    }

    /**
     * @param $serviceKey
     * @param null $serviceType
     * @return mixed
     * @throws Exception
     */
    protected function getService($serviceKey, $serviceType = null): mixed
    {
        $service = $this->serviceRepository->scopeQuery(function ($query) use ($serviceKey, $serviceType) {
            return $query->where('key', $serviceKey)
                ->when($serviceType, function ($query) use ($serviceType) {
                    return $query->whereHas('service_type', function ($query) use ($serviceType) {
                        $query->where(['slug' => $serviceType]);
                    });
                });
        })->first();
        
        if (is_null($service)) {
            throw new Exception('Service does not exist', 404);
        }

        return $service;
    }


    /**
     * @param $service
     * @return false|string
     */
    protected function validateServiceClass($service): bool|string
    {
        $serviceConfig = config('bds.bds_service_types.' . $service->service_type->slug);
        if (isset($serviceConfig['class']) && !empty($serviceConfig['class']) && class_exists($serviceConfig['class'])) {
            return $serviceConfig['class'];
        }

        if (!is_null($service->parent)) {
            $parentServiceConfig = config('bds.bds_service_types.' . $service->parent->service_type->slug . '.services.' . $service->parent->key);
            if (isset($parentServiceConfig['class']) && !empty($parentServiceConfig['class']) && class_exists($parentServiceConfig['class'])) {
                return $parentServiceConfig['class'];
            }
        }
        $serviceConfig = config('bds.bds_service_types.' . $service->service_type->slug . '.services.' . $service->key);
        if (isset($serviceConfig['class']) && !empty($serviceConfig['class']) && class_exists($serviceConfig['class'])) {
            return $serviceConfig['class'];
        }

        return false;
    }

    /**
     * @param $service
     * @param $user
     * @param $request
     * @param array $validationRules
     * @param array $ruleMessages
     * @throws PaymentException
     * @throws WalletException
     * @throws RepositoryException
     */
    protected function serviceExecution(
        $service,
        $user,
        $request,
        array $validationRules = [],
        array $ruleMessages = []
    ): JsonResponse {
        if ($user->profile->account_level_id <= 1) {
            return api()->status(412)->message('You need to complete your KYC, before you can proceed')->respond();
        }

        $serviceConfig = getServiceConfig($service);
        if (!$service->enabled) {
            return api()->status(404)->message('Service is currently not available')->respond();
        }
        if ($service->enabled && (!$validatedClass = $this->validateServiceClass($service))) {
            $validatedClass = CustomServiceHandler::class;
        }

        $variant = null;
        if ($service->variants->count()) {
            if (is_null($request->variant_key)) {
                return api()->status(404)->message('Service requires variant')->respond();
            }

            if (is_null($variant = $service->variants()->where('key', $request->variant_key)->first())) {
                return api()->status(404)->message('Service variant is invalid')->respond();
            }
        }


        $serviceClass = new $validatedClass($service, $this->walletRepository, $this->transactionRepository);
        if (method_exists($serviceClass, 'setVariant')) {
            $serviceClass->setVariant($variant);
        }
        // if (!is_bool($validator = $serviceClass->validate($request->toArray(), $validationRules, $ruleMessages))) {
        //     return response()->json($this->failedValidation($validator), 422);
        // }

        return rescue(static function () use ($serviceClass, $user, $request, $service) {
            $execute = $serviceClass->setCurrentUser($user)->execute($request);

            if (is_bool($execute) && $execute == false) {
                return api()->status(400)->message($service->title . ' order was not successful, please try again')->data([
                    'status' => false,
                ])->respond();
            }

            // return api()->status(200)->message("checking vtpass")->data([
            //     'status' => true,
            //     'data' => $execute,
            // ])->respond();
            
            return api()->status(200)->message($execute)->data([
                'status' => true,
            ])->respond();
        }, static function ($exception) use ($service) {
            logger()->error($service->title . ' order error : ' . $exception);
            return api()->status(500)->message($exception->getMessage())->data([
                'status' => false,
            ])->respond();
        });
    }
}
