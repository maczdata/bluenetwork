<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           PrintingHandler.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     22/08/2021, 10:11 PM
 */

namespace App\Services\ServiceHandler;

use App\Abstracts\Services;
use App\Exceptions\PaymentException;
use App\Exceptions\WalletException;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Prettus\Repository\Exceptions\RepositoryException;

/**
 * Class Printing
 *
 * @package App\Services\ServiceHandler
 */
class PrintingHandler extends Services
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return "Printing";
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
        $designDocumentRequired = ($this->serviceObject->getMeta('requires_design_document') ? 'required' : 'nullable');

        $rules = [
            //'meta' => 'required|array',
            //'meta.quantity' => 'required',
            //'meta.description' => 'required',
            //'meta.business_name' => 'required',
            //'meta.brand_colors' => 'required',
            'design_document' => $designDocumentRequired,
            'design_document.*' => $designDocumentRequired . '|mimes:jpeg,jpg,png,pdf',
        ];
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
            'package.required' => 'Package is required',
            'package.exists' => 'Package does not exist',
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
        $servicePrice = ($this->serviceObject->price ?? 0);
        $addonPrices = 0;
        $metaFields = $data['meta'];
        $serviceFeatures = $this->serviceObject->serviceFeatures;
        $extraPreview = [];
        $extraPreview['Package'] = $this->serviceObject->title;
        if ($serviceFeatures->count()) {
            foreach ($serviceFeatures as $serviceFeature) {
                $requestHasFeature = $data->has($serviceFeature->feature->slug);
                if (!$requestHasFeature || ($requestHasFeature && is_null($featureValue = $serviceFeature->featureValues()->where('id',
                            $data->input($serviceFeature->feature->slug))->first()))) {
                    continue;
                }
                $extraPreview[$serviceFeature->feature->title] = [
                    'title' => $featureValue->title,
                ];
            }
        }
        if (isset($data['addons']) && is_array($data['addons'])) {
            $extraPreview['addons'] = [];
            foreach ($data['addons'] as $addon) {
                $addonExist = $this->serviceObject->addons()->where('id', $addon)->first();
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
        $extraPreview['formatted_amount_to_pay'] = core()->formatBasePrice($amount ?? 0);
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
        $metaFields = $data['meta'];
        $packageKey = $data['package_key'];
        $servicePrice = $this->serviceObject->price;
        $addonPrices = 0;
        $orderItems = [];
        $serviceFeatures = $this->serviceObject->serviceFeatures;
        $testFeature = [];
        if ($serviceFeatures->count()) {
            foreach ($serviceFeatures as $serviceFeature) {
                $requestHasFeature = $data->has($serviceFeature->feature->slug);
                if (!$requestHasFeature || ($requestHasFeature && is_null($featureValue = $serviceFeature->featureValues()->where('id',
                            $data->input($serviceFeature->feature->slug))->first()))) {
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
        if (isset($data['addons']) && is_array($data['addons'])) {
            foreach ($data['addons'] as $addonKey => $addon) {
                $addonExist = $this->serviceObject->addons()->where('id', $addon)->first();
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
        $creditingSuccessful = false;
        if ($processOtherData) {
            DB::beginTransaction();
            try {
                $order = $this->saveOrder($this->serviceObject, $amount, $orderItems, $modeOfPayment,
                    ($data['custom_fields'] ?? []));
                $transaction = $user->transactions()
                    ->create([
                        'transactionable_type' => $order->getMorphClass(),
                        'transactionable_id' => $order->getKey(),
                        'amount' => $amount,
                        'type' => 2,
                    ]);
                if (isset($data['design_document'])) {
                    $order->addMultipleMediaFromRequest(['design_document'])
                        ->each(function ($fileAdder) {
                            $fileAdder->toMediaCollection('design_document');
                        });
                }
                $creditingSuccessful = 'Your order for service (' . ucfirst($this->serviceObject->title) . ') was successful';
                $transaction->update(['status' => 1]);
                if ($modeOfPayment === 'wallet') {
                    $this->walletRepository->withDraw($user, $amount);
                }
                //$order->syncMeta($metaFields);

                DB::commit();
                return $creditingSuccessful;
            } catch (Exception $exception) {
                logger()->error($exception->getMessage());
                DB::rollBack();
                throw $exception;
                //throw new Exception($exception);
            }
        }

        return PaymentException::paymentUnverified();
    }
}
