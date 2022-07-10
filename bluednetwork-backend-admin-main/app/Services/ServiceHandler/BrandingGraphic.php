<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           BrandingGraphic.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     16/08/2021, 11:35 AM
 */

namespace App\Services\ServiceHandler;

use App\Abstracts\Services;
use App\Exceptions\PaymentException;
use App\Exceptions\WalletException;
use App\Models\Common\ServiceVariant;
use App\Repositories\Common\ServiceRepository;
use App\Repositories\Common\TransactionRepository;
use App\Repositories\Common\WalletRepository;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Prettus\Repository\Exceptions\RepositoryException;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;

/**
 * Class BrandingGraphic
 *
 * @package App\Services\ServiceHandler
 */
class BrandingGraphic extends Services
{
    /**
     * @var Application|mixed
     */
    private mixed $serviceRepository;

    private ?ServiceVariant $variant;

    /**
     * AirtimePurchase constructor.
     *
     * @param $serviceObject
     * @param WalletRepository $walletRepository
     * @param TransactionRepository $transactionRepository
     */
    public function __construct(
        protected $serviceObject,
        protected WalletRepository $walletRepository,
        protected TransactionRepository $transactionRepository
    ) {
        parent::__construct($serviceObject, $walletRepository, $transactionRepository);
        $this->serviceRepository = app(ServiceRepository::class);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return "Graphics";
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return "";
    }

    /**
     * @param ServiceVariant|null $variant
     */
    public function setVariant(?ServiceVariant $variant)
    {
        $this->variant = $variant;
    }

    /**
     * @param array $data
     * @param array $rules
     * @param array $rulesMessages
     * @return ValidatorContract|bool
     */
    public function validate(array $data = [], array $rules = [], array $rulesMessages = []): ValidatorContract|bool
    {
        $designDocumentRequired = ($this->serviceObject->getMeta('requires_design_document') ? 'required' : 'nullable');
        $rules = [
            /*'variant_key' => [
                'required',
                Rule::exists('service_variants', 'key')->where(function ($query) {
                    $query->where('service_id', $this->serviceObject->id);

                }),
            ],*/
            'design_document' => $designDocumentRequired,
            'design_document.*' => $designDocumentRequired . '|mimes:jpeg,jpg,png,pdf',
        ];
        //        /*$serviceFeatures = $this->serviceObject->serviceFeatures;
        //        if ($serviceFeatures->count()) {
        //            foreach ($serviceFeatures as $serviceFeature) {
        //                $rules[$serviceFeature->feature->slug] = [
        //                    'nullable',
        //                    Rule::exists('featurize_values', 'id')->where(function ($query) use ($serviceFeature) {
        //                        return $query->where('featurize_id', $serviceFeature->id);
        //                    }),
        //                ];
        //            }
        //        }*/
        $rulesMessages = [
            'package.required' => 'Package is required',
            'package.exists' => 'Package does not exist'
        ];
        $validator = $this->variant->validateCustomFieldsRequest($data, $rules, $rulesMessages);
        if ($validator->fails()) {
            return $validator;
        }
        return true;
    }

    /**
     * @param $data
     * @return int[]
     * @throws Exception
     */
    public function preview($data): array
    {
        $addonPrices = 0;
        //$metaFields = $data['meta'];
        $extraPreview = [];
        $extraPreview['Package'] = $this->variant->title;
        $servicePrice = $this->variant->price ?? 0;
        $serviceFeatures = $this->variant->serviceFeatures;
        if ($serviceFeatures->count()) {
            foreach ($serviceFeatures as $serviceFeature) {
                $requestHasFeature = $data->has($serviceFeature->feature->slug);
                if (!$requestHasFeature || ($requestHasFeature && is_null($featureValue = $serviceFeature->featureValues()->where('id', $data->input($serviceFeature->feature->slug))->first()))) {
                    continue;
                }
                $extraPreview[$serviceFeature->feature->title] = [
                    'title' => $featureValue->title,
                ];
            }
        }
        if ($this->variant->addons->count() && isset($data['addons']) && is_array($data['addons'])) {
            $extraPreview['addons'] = [];
            foreach ($data['addons'] as $addon) {
                $addonExist = $this->variant->addons()->where('id', $addon)->first();
                if (is_null($addonExist)) {
                    continue;
                }
                $extraPreview['addons'][$addonExist->slug] = [
                    'title' => $addonExist->title,
                    'raw_price' => $addonExist->price,
                    'formatted_price' => core()->formatBasePrice($addonExist->price ?? 0),
                ];
                $addonPrices += ($addonExist->price ?? 0);
            }
        }
        $amount = ($servicePrice + $addonPrices);
        $extraPreview['amount_to_pay'] = $amount;
        $extraPreview['formatted_amount_to_pay'] =  core()->formatBasePrice($amount ?? 0);
        if (isset($data['design_document']) && !is_null($data['design_document'])) {
            $extraPreview['number_of_design_files'] = count($data['design_document']);
        }

        return array_merge($data['custom_fields'], $extraPreview);
    }

    /**
     * @param Request $data
     * @return bool|string
     * @throws PaymentException
     * @throws RepositoryException
     * @throws WalletException
     * @throws Exception
     */
    public function execute(Request $data)
    {
        $user = $this->currentUser;
        $transactionId = $data['transaction_id'];
        $modeOfPayment = $data['mode_of_payment'];
        $metaFields = $data['meta'] ?? [];
        $customFields = $data['custom_fields'] ?? [];

        $servicePrice = $this->variant->price;
        $addonPrices = 0;
        $orderItems = [];
        $serviceFeatures = $this->variant->serviceFeatures;
        $testFeature = [];
        if ($serviceFeatures->count()) {
            foreach ($serviceFeatures as $serviceFeature) {
                $requestHasFeature = $data->has($serviceFeature->feature->slug);
                if (!$requestHasFeature || ($requestHasFeature && is_null($featureValue = $serviceFeature->featureValues()->where('id', $data->input($serviceFeature->feature->slug))->first()))) {
                    continue;
                }
                $testFeature[] = $featureValue->title;
                $orderItems[$serviceFeature->id] = [
                    'item' => $featureValue,
                    'quantity' => 1,
                ];
            }
        }
        if ($this->variant->addons->count() && isset($data['addons']) && is_array($data['addons'])) {
            foreach ($data['addons'] as $addonKey => $addon) {
                $addonExist = $this->variant->addons()->where('id', $addon)->first();
                if (is_null($addonExist)) {
                    continue;
                }
                $orderItems[$addonKey] = [
                    'item' => $addonExist,
                    'quantity' => 1,
                    'price' => $addonExist->price ?? 0,
                    'total' => $addonExist->price ?? 0,
                ];
                $addonPrices += ($addonExist->price ?? 0);
            }
        }
        $amount = ($servicePrice + $addonPrices);

        if ($amount > $user->profile->accountLevel->transaction_limit) {
            throw new Exception(
                'Sorry, your account is currently limited to transactions equal or less than'
                . core()->formatBasePrice($user->profile->accountLevel->transaction_limit ?? 0)
            );
        }

        $processOtherData = $this->verifyAmountPaid($amount, $modeOfPayment, $transactionId);

        if ($processOtherData) {
            DB::beginTransaction();
            try {
                $order = $this->saveOrder($this->variant, $amount, $orderItems, $modeOfPayment, $customFields);
                if (isset($data['design_document'])) {
                    $order->addMultipleMediaFromRequest(['design_document'])
                        ->each(function ($fileAdder) {
                            $fileAdder->toMediaCollection('design_document');
                        });
                }
                $transaction = $user->transactions()
                    ->create([
                        'transactionable_type' => $order->getMorphClass(),
                        'transactionable_id' => $order->getKey(),
                        'amount' => $amount,
                        'type' => 2,
                    ]);
                $creditingSuccessful = 'Your order for service (' . ucfirst($this->serviceObject->title) . ' ' . ucfirst($this->serviceObject?->parent?->title) . ') was successful';
                if ($modeOfPayment === 'wallet') {
                    $this->walletRepository->withDraw($user, $amount);
                }
                $transaction->update(['status' => 1]);
                $order->syncMeta($metaFields);

                DB::commit();
                return $creditingSuccessful;
            } catch (Exception $exception) {
                logger()->error($exception);
                DB::rollBack();
                throw new Exception($exception);
            }
        }
        return PaymentException::paymentUnverified();
    }
}
