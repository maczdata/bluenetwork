<?php

namespace App\Http\Controllers\Control\Orders;

use App\Abstracts\Http\Controllers\ControlController;
use App\Repositories\Common\ServiceRepository;
use App\Repositories\Common\TransactionRepository;
use App\Repositories\Common\WalletRepository;
use App\Repositories\Finance\OrderRepository;
use App\Repositories\Users\UserRepository;
use App\Services\ServiceHandler\CustomServiceHandler;
use App\Traits\Common\FormError;
use Exception;
use Throwable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ManageOrderController extends ControlController
{
    use FormError;

    public function __construct(
        protected ServiceRepository     $serviceRepository,
        protected WalletRepository      $walletRepository,
        protected TransactionRepository $transactionRepository,
        protected UserRepository $userRepository,
        protected OrderRepository $orderRepository
    ) {
        parent::__construct();
    }

    public function create(Request $request)
    {
        $user = $this->userRepository->find($request->user);
        $serviceKey = $request->service_key;
        $payload = [];
        $validationRules = [
            'mode_of_payment' => 'required|in:wallet',
        ];
        $validationMessages = [
            'mode_of_payment.required' => 'mode of payment is required',
            'mode_of_payment.in' => 'Mode of payment can only be Wallet'
        ];


        if ($serviceKey === 'cable_tv') {
            $payload['cable_tv_service'] = $request->service_provider;
            $payload['cable_tv_package'] = $request->cable_tv_package;
            $payload['cable_tv_smart_card_number'] = $request->cable_tv_smart_card_number;
        } elseif ($serviceKey === "electricity") {
            $payload['electricity_disco'] = $request->service_provider;
            $payload['electricity_amount'] = $request->amount;
            $payload['electricity_meter_number'] = $request->electricity_meter_number;
            $payload['electricity_meter_type'] = $request->electricity_meter_type;
        } elseif ($serviceKey === "data-subscription") {
            $payload['variant'] = $request->variant;
            $payload['network'] = $request->service_provider;
            $payload['phone_number'] = $request->phone_number;
        } else {
            $payload['network'] = $request->service_provider;
            $payload['phone_number'] = $request->phone_number;
            $payload['amount'] = $request->amount;
        }
        
        $payload['coupon_code'] = $request->coupon_code;

        $request->request->add(['custom_fields' => $payload]);


        $service = $this->getService($serviceKey);
        try {
            if ($user->profile->account_level_id <= 1) {
                throw new \Exception('You need to complete your KYC, before you can proceed');
            }

            if ($request->amount > $user->profile->level->transaction_limit) {
                throw new \Exception(
                    'Sorry, your account is currently limited to transactions equal or less than'
                        . number_format($user->profile->level->transaction_limit)
                );
            }

            $response = $this->serviceExecution($service, $user, $request, $validationRules, $validationMessages);
            if ($response->status) {
                flash($response->message)->success();
                return redirect()->back();
            } else {
                flash($response->message)->error();
                return back()->withErrors($response->data);
            }
        } catch (Throwable $exception) {
            logger()->error($service->title . ' order error : ' . $exception);
            flash($exception->getMessage())->error();
            return back();
        }
    }

    public function update(Request $request, string $orderId)
    {
        $order = $this->orderRepository->findByHashidOrFail($orderId);
        if (is_null($order)) {
            flash("Invalid order")->error();
            return back();
        }

        $this->orderRepository->update(['status' => $request->status], $order->id);
        flash("Order status updated")->success();
        return back();
    }
    /**
     * @param $serviceKey
     * @param null $serviceType
     * @return mixed
     * @throws Throwable
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
        } else {
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
        }

        return false;
    }

    /**
     * @param $service
     * @param $user
     * @param $request
     * @param array $validationRules
     * @param array $ruleMessages
     * @return JsonResponse
     */
    protected function serviceExecution(
        $service,
        $user,
        $request,
        array $validationRules = [],
        array $ruleMessages = []
    ) {
        if (!$service->enabled) {
            return $this->orderResponseDto('Service is currently not available', false, 404, []);
        }
        if ($service->enabled && (!$validatedClass = $this->validateServiceClass($service))) {
            $validatedClass = CustomServiceHandler::class;
        }
        $variant = null;
        if ($service->variants->count()) {
            if (is_null($request->variant_key)) {
                return $this->orderResponseDto('Service requires variant', false, 404, []);
            }

            if (is_null($variant = $service->variants()->where('key', $request->variant_key)->first())) {
                return $this->orderResponseDto('Service variant is invalid', false, 404, []);
            }
        }


        $serviceClass = new $validatedClass($service, $this->walletRepository, $this->transactionRepository);
        if (method_exists($serviceClass, 'setVariant')) {
            $serviceClass->setVariant($variant);
        }
        if (!is_bool($validator = $serviceClass->validate($request->toArray(), $validationRules, $ruleMessages))) {
            return $this->orderResponseDto('Validation error', false, 4000, $this->errorFormRequest($validator));
        }
        try {
            return rescue(function () use ($serviceClass, $user, $request, $service) {
                $execute = $serviceClass->setCurrentUser($user)->execute($request);

                if (is_bool($execute) && $execute == false) {
                    return $this->orderResponseDto($service->title . ' order was not successful, please try again', false, 400, []);
                } else {
                    return $this->orderResponseDto($execute, true, 200, []);
                }
            }, function (Throwable $exception) use ($service) {
                logger()->error($service->title . ' order error : ' . $exception);
                return $this->orderResponseDto($exception->getMessage(), false, 400, []);
            });
        } catch (Throwable $exception) {
            logger()->error($service->title . ' order error : ' . $exception);
            return $this->orderResponseDto($exception->getMessage(), false, 400, []);
        }
    }

    private function orderResponseDto(
        string $message,
        bool $status,
        int $statusCode,
        array $data = []
    )
    {
        return (object) [
            'message' => $message,
            'status' => $status,
            'data' => $data,
            'statusCode' => $statusCode,
        ];
    }
}
