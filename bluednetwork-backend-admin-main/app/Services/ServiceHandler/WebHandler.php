<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           WebHandler.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     18/08/2021, 6:24 PM
 */

namespace App\Services\ServiceHandler;

use App\Repositories\Common\ServiceVariantRepository;
use Exception;
use App\Abstracts\Services;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Exceptions\WalletException;
use App\Exceptions\PaymentException;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Common\ServiceRepository;
use App\Repositories\Common\WalletRepository;
use Illuminate\Contracts\Foundation\Application;
use App\Repositories\Common\TransactionRepository;
use Prettus\Repository\Exceptions\RepositoryException;

/**
 * Class WebHandler
 *
 * @package App\Services\ServiceHandler
 */
class WebHandler extends Services
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return "Web Service";
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return "";
    }

    /**
     * @param array $data
     * @param array $rules
     * @param array $rulesMessages
     * @return \Illuminate\Contracts\Validation\Validator|bool
     */
    public function validate(
        array $data = [],
        array $rules = [],
        array $rulesMessages = []
    ): \Illuminate\Contracts\Validation\Validator|bool {

        $serviceFeatures = $this->serviceObject->serviceFeatures;
        if ($serviceFeatures->count()) {
            foreach ($serviceFeatures as $serviceFeature) {
                $rules[$serviceFeature->feature->slug] = [
                    'nullable',
                    Rule::exists('featurize_values', 'id')->where(function ($query) use ($serviceFeature) {
                        return $query->where('featurize_id', $serviceFeature->id);
                    }),
                ];
            }
        }
        $rulesMessages = [
            'variant_id.required' => 'Variant is required',
            'variant_id.exists' => 'Variant does not exist',
        ];
        $rulesMessages['meta.variants.*'] = [
            'exists' => 'The selected addon does not exist',
        ];

        return parent::validate($data, $rules, $rulesMessages);
    }

    /**
     * @param $data
     * @return int[]
     * @throws Exception
     */
    public function preview($data): array
    {
        $addonPrices = 0;
        $service = $this->serviceObject;
        $variant = $service->variants()->where(['key' => $data['variant_key']])->first();
        if (!$variant) {
            throw new Exception('Variant not available on this provider');
        }
        $variantPrice = ($variant->price ?? 0);
        $serviceFeatures = $variant->serviceFeatures;
        $extraPreview = [];
        if ($serviceFeatures->count()) {
            foreach ($serviceFeatures as $serviceFeature) {
                $requestHasFeature = $data->has($serviceFeature->feature->slug);
                if (!$requestHasFeature || ($requestHasFeature && is_null($featureValue = $serviceFeature->featureValues()->where('id', $data->input($serviceFeature->feature->slug))->first()))) {
                    continue;
                }
                $extraPreview[$serviceFeature->feature->title] = [
                    'title' => $featureValue->title,
                    //'raw_price' => $addonExist->price,
                    //'formatted_price' => core()->formatBasePrice($addonExist->price ?? 0),
                ];
            }
        }
        $extraPreview['variant_description'] = $variant->description;
        $extraPreview['variant_icon'] = $variant->service_variant_icon;
        if ($variant->addons->count() && isset($data['addons']) && is_array($data['addons'])) {
            $extraPreview['addons'] = [];
            foreach ($data['addons'] as $addon) {
                $addonExist = $variant->addons()->where('id', $addon)->first();
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
        $amount = ($variantPrice + $addonPrices);
        $extraPreview['amount_to_pay'] = $amount;
        $extraPreview['formatted_amount_to_pay'] = core()->formatBasePrice($amount ?? 0);
        if (isset($data['design_document']) && !is_null($data['design_document'])) {
            $extraPreview['number_of_design_files'] = count($data['design_document']);
        }
        return array_merge($data['custom_fields'], $extraPreview);
    }

    /**
     * @param Request $data
     * @return PaymentException|string
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
        $variant = $this->serviceObject->variants()->where(['key' => $data['variant_key']])->first();
        if (!$variant) {
            throw new Exception('Variant not available on this provider');
        }
        $servicePrice = $variant->price;
        $addonPrices = 0;
        $orderItems = [];
        $serviceFeatures = $variant->serviceFeatures;
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
                    //'price' => $addonExist->price ?? 0,
                    //'total' => $addonExist->price ?? 0,
                ];
            }
        }
        if ($variant->addons->count() && isset($data['addons']) && is_array($data['addons'])) {
            foreach ($data['addons'] as $addonKey => $addon) {
                $addonExist = $variant->addons()->where('id', $addon)->first();
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
                $order = $this->saveOrder($variant, $amount, $orderItems, $modeOfPayment, ($data['custom_fields'] ?? []));
                if (isset($data['design_document'])) {
                    $order->addMultipleMediaFromRequest(['design_document'])
                        ->each(function ($fileAdder) {
                            $fileAdder->toMediaCollection('design_document');
                        });
                }
                if ($modeOfPayment === 'wallet') {
                    $this->walletRepository->withDraw($user, $amount);
                }
                $user->transactions()
                    ->create([
                        'transactionable_type' => $order->getMorphClass(),
                        'transactionable_id' => $order->getKey(),
                        'amount' => $amount,
                        'status' => 1,
                        'type' => 2,
                    ]);
                $creditingSuccessful = 'Your order for service (' . ucfirst($this->serviceObject->title) . ' ' . ucfirst($variant->title) . ') was successful';

                DB::commit();
                return $creditingSuccessful;
            } catch (Exception $exception) {
                logger()->error('Web handler exception error : ' . $exception);
                DB::rollBack();
                throw new Exception($exception);
            }
        }
        return PaymentException::paymentUnverified();
    }
}
