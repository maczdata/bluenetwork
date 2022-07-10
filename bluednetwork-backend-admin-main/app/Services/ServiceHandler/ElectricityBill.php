<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           ElectricityBill.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Services\ServiceHandler;

use App\Abstracts\Services;
use App\Exceptions\ElectricityException;
use App\Exceptions\PaymentException;
use App\Exceptions\WalletException;
use App\Repositories\Common\ServiceRepository;
use App\Repositories\Common\TransactionRepository;
use App\Repositories\Common\WalletRepository;
use App\Traits\Common\InteractsWithVtpass;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Exceptions\RepositoryException;

/**
 * Class ElectricityBill
 *
 * @package App\Services\ServiceHandler
 */
class ElectricityBill extends Services
{
    use InteractsWithVtpass;

    /**
     * @var Application|mixed
     */
    private mixed $serviceRepository;

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

    public function getName(): string
    {
        return "Data Purchase";
    }

    public function getDescription(): string
    {
        return "";
    }

    /**
     * @param $data
     * @return mixed
     * @throws ElectricityException
     */
    public function preview($data): mixed
    {
        $customFields = $data['custom_fields'];
        $variantKey = $customFields['electricity_disco'];
        $meterType = $customFields['electricity_meter_type'];
        $meterNumber = $customFields['electricity_meter_number'];
        $verifyMeter = $this->verify([
            'serviceID' => $variantKey,
            'billersCode' => $meterNumber,
            'type' => $meterType,
        ]);
        if ($verifyMeter['code'] !== '000') {
            throw ElectricityException::invalidMeterCredential();
        }

        return $verifyMeter;
    }

    /**
     * @param Request $data
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
        // $data["custom_fields"] =  [
        //     "electricity_disco" => 24,
        //     'electricity_amount' => 1000,
        //     "electricity_meter_type" => "prepaid",
        //     "electricity_meter_number" => 1111111111111
        // ];
        $customFields = $data['custom_fields'];
        $variantKey = $customFields['electricity_disco'];
        $amount = $customFields['electricity_amount'];
        $meterType = $customFields['electricity_meter_type'];
        $meterNumber = $customFields['electricity_meter_number'];
        $variantIdTo = app(ServiceRepository::class)->scopeQuery(function ($query) use ($variantKey) {
            return $query->where(['id' => $variantKey]);
        })->first();
        if (!$variantIdTo) {
            throw new Exception('Variant not available on this provider');
        }
        if ($amount < 500) {
            throw new Exception('Payable amount must be either or more than ' . core()->formatBasePrice(500), 403);
        }

        if ($amount > $user->profile->accountLevel->transaction_limit) {
            throw new Exception(
                'Sorry, your account is currently limited to transactions equal or less than'
                . core()->formatBasePrice($user->profile->accountLevel->transaction_limit ?? 0)
            );
        }

        $processOtherData = $this->verifyAmountPaid($amount, $modeOfPayment, $transactionId);
        if ($processOtherData) {
            $orderMeta = [];
            DB::beginTransaction();
            try {
                $thirdPartyError = false;
                // $verifyMeter = $this->verify([
                //     'serviceID' => $variantIdTo->key,
                //     'billersCode' => $meterNumber,
                //     'type' => $meterType,
                // ]);

                // if ($verifyMeter['code'] !== '000') {
                //     throw ElectricityException::invalidMeterCredential();
                // }
                $order = $this->saveOrder($variantIdTo, $amount, [], $modeOfPayment, $customFields);
                $transaction = $user->transactions()
                    ->create([
                        'transactionable_type' => $order->getMorphClass(),
                        'transactionable_id' => $order->getKey(),
                        'amount' => $amount,
                        'type' => 2,
                    ]);
                date_default_timezone_set("Africa/Lagos");
                $purchaseData = $this->purchase([
                    'serviceID' => $variantIdTo->key,
                    'billersCode' => $meterNumber,
                    'variation_code' => $meterType,
                    'request_id' => date('YmdHi').$order->order_number,
                    'amount' => $amount,
                    'phone' => $user->phone_number,
                ]);
                $creditingSuccessful = false;
                // dd($purchaseData);
                if ($purchaseData['code'] === '000') {
                   
                    if ($modeOfPayment === 'wallet') {
                        $this->walletRepository->withDraw($user, $amount);
                    }
                    $creditingSuccessful = 'You successfully purchased ';
                    
                    if ($meterType == 'prepaid') {
                        $orderMeta['mainToken'] = $purchaseData['mainToken'] ?? $purchaseData['Token'] ;
                        $orderMeta['bonusToken'] = $purchaseData['bonusToken'] ?? null;
                        $orderMeta['mainTokenUnits'] = $purchaseData['mainTokenUnits'] ?? $purchaseData['PurchasedUnits'];
                        $orderMeta['bonusTokenUnits'] = $purchaseData['bonusTokenUnits'] ?? null;
                        $creditingSuccessful .= $orderMeta['mainTokenUnits'] . ' unit for meter number ' . $meterNumber;
                        $creditingSuccessful .= 'Your token is ' . $orderMeta['mainToken'];
                    } else {
                        $creditingSuccessful .= core()->formatBasePrice($amount) . ' for meter number ' . $meterNumber;
                    }
                    $order->syncMeta($orderMeta);
                    $transaction->update(['status' => 1]);
                } elseif ($purchaseData['code'] === '014') {
                    $creditingSuccessful = 'Order has previously being completed ';
                    $thirdPartyError = true;
                } elseif ($purchaseData['code'] === '016') {
                    $creditingSuccessful = 'Order could not be completed';
                    $transaction->update(['status' => 0]);
                    $thirdPartyError = true;
                } elseif ($purchaseData['code'] === '018') {
                    $creditingSuccessful = 'Merchant wallet balance is too low to complete this transaction';
                    $transaction->update(['status' => 0]);
                    $thirdPartyError = true;
                } elseif ($purchaseData['code'] === '034') {
                    $creditingSuccessful = 'The service being requested for has been suspended for the time being.';
                    $transaction->update(['status' => 0]);
                    $thirdPartyError = true;
                } elseif ($purchaseData['code'] === '083') {
                    $creditingSuccessful = 'Oops!!! System error. Please contact tech support';
                    $transaction->update(['status' => 0]);
                    $thirdPartyError = true;
                }
                DB::commit();
                
                return $creditingSuccessful;
            } catch (Exception $exception) {
                logger()->error($exception);
                DB::rollBack();
                throw new Exception($exception);
            }
        } else {
            return PaymentException::paymentUnverified();
        }
    }
}
