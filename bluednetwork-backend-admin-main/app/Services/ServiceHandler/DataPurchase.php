<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           DataPurchase.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     15/08/2021, 6:00 PM
 */

namespace App\Services\ServiceHandler;

use App\Abstracts\Services;
use App\Exceptions\PaymentException;
use App\Exceptions\WalletException;
use App\Repositories\Common\ServiceVariantRepository;
use App\Traits\Common\InteractsWithVtpass;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Exceptions\RepositoryException;

/**
 * Class DataPurchase
 *
 * @package App\Services\ServiceHandler
 */
class DataPurchase extends Services
{
    use InteractsWithVtpass;

    public function getName(): string
    {
        return "Data Purchase";
    }

    public function getDescription(): string
    {
        return "";
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
        $transactionId = $data['transaction_id'];

        // $data["custom_fields"] =  [
        //         "network" => 13,
        //         'variant' => 1,
        //         "phone_number" => "08011111111"

        // ];

        $modeOfPayment = $data['mode_of_payment'];
        $customFields = $data['custom_fields'];
     
        $serviceId = $customFields['network'];
        $variantId = $customFields['variant'];
        $phoneNumber = $customFields['phone_number'];
    
        //$amount = $customFields['amount'];
        //$amount = ((env('TEST_MODE') == true) ? 0.20 : $customFields['amount']);
        $user = $this->currentUser;
        $pin = env('SMSHOST_MTN_SMS_PIN');
        $variantIdTo = app(ServiceVariantRepository::class)->scopeQuery(function ($query) use ($serviceId, $variantId) {
            return $query->whereHas('service', function ($query) {
                $query->where('parent_id', $this->serviceObject->id);
            })->where(['id' => $variantId, 'service_id' => $serviceId]);
        })->first();
        if (!$variantIdTo) {
            throw new Exception('Variant not available on this provider');
        }
        $amount = $variantIdTo->price;
        
        if ($amount > $user->profile->accountLevel->transaction_limit) {
            throw new Exception(
                'Sorry, your account is currently limited to transactions equal or less than'
                . core()->formatBasePrice($user->profile->accountLevel->transaction_limit ?? 0)
            );
        }

        $processOtherData = $this->verifyAmountPaid($amount, $modeOfPayment, $transactionId);
        if ($processOtherData) {
            $creditingSuccessful = false;
            DB::beginTransaction();
            try {
                $thirdPartyError = false;
                $order = $this->saveOrder($variantIdTo, $amount, [], $modeOfPayment, $customFields);
                $transaction = $user->transactions()
                    ->create([
                        'transactionable_type' => $order->getMorphClass(),
                        'transactionable_id' => $order->getKey(),
                        'amount' => $amount,
                        'type' => 1,
                    ]);
                $isSuccess = false;
                //logger('service_key : ' . $variantIdTo->service->key);
                //logger('service_key : ' . $variantIdTo->key);
                if (optional(optional($variantIdTo)->service)->key != null) {
                    date_default_timezone_set("Africa/Lagos");
                //    dd($variantIdTo->key);
                    $purchaseData = $this->purchase([
                        'serviceID' => $variantIdTo->service->key,
                        'variation_code' => $variantIdTo->key,
                        'billersCode' => $user->phone_number,
                        'phone' => $phoneNumber,
                        'request_id' => date('YmdHi').$order->order_number,
                    ]);
                    logger()->info('loggg : ' . $purchaseData['code']);
                    if ($purchaseData['code'] == '000') {
                        $isSuccess = true;
                        if ($modeOfPayment === 'wallet') {
                            $this->walletRepository->withDraw($user, $amount);
                        }
                    } elseif ($purchaseData['code'] === '014') {
                        $creditingSuccessful = 'Order has previously being completed ';
                        $thirdPartyError = true;
                    } elseif ($purchaseData['code'] === '016') {
                        $creditingSuccessful = 'Transaction';
                        $thirdPartyError = true;
                    } elseif ($purchaseData['code'] === '018') {
                        $creditingSuccessful = 'Merchant wallet balance is too low to complete this transaction';
                        $thirdPartyError = true;
                    } elseif ($purchaseData['code'] === '034') {
                        $creditingSuccessful = 'The service being requested for has been suspended for the time being.';
                        $thirdPartyError = true;
                        $transaction->update(['status' => 0]);
                    } elseif ($purchaseData['code'] === '083') {
                        $creditingSuccessful = 'Oops!!! System error. Please contact tech support';
                        $thirdPartyError = true;
                    }
                } else {
                    /*$smsContent = $variantIdTo->getMeta('simhost_subscription_sms_content');
                    $smsReceiver = $variantIdTo->getMeta('simhost_sms_receiver');
                    $serverConfig = config('bds.bds_service_types.' . $variantIdTo->service->service_type->slug . '.services.' . $variantIdTo->service->parent->key . '.child_services');
                    //var_dump($serverConfig);
                    //var_dump($variantIdTo->service->key);
                    $serverId = $serverConfig[$variantIdTo->service->key]['server_id'];
                    $smsContent = Str::replaceArray('?', [$phoneNumber, $amount, $pin], $smsContent);
                    $apikey = config('bds.smshostng.api_key');
                    $requestResponse = Http::asForm()->post('https://simhostng.com/api/ussd', [
                        'apikey' => $apikey,
                        'server' => $serverId,
                        'sim' => 1,
                        'number' => $smsReceiver,
                        'message' => $smsContent
                    ]);
                    if ($requestResponse->failed()) {
                        $requestResponse->throw();
                    }
                    $resData = $requestResponse->object();
                    if ($resData->data[0]->response === 'Ok') {*/
                    $isSuccess = false;
                    //}
                }
                if ($isSuccess) {
                    $transaction->update(['status' => 1]);
                    $creditingSuccessful = 'You successfully purchased ' . ucfirst($variantIdTo->service->title) . ' ' . $variantIdTo->name . '' . core()->formatBasePrice($amount) . ' data for ' . $phoneNumber;
                } else {
                    $transaction->update(['status' => 0]);
                }
                DB::commit();
              
                return $creditingSuccessful;
            } catch (\Exception $exception) {
                DB::rollBack();
                logger()->error('Data recharge error : ' . $exception);
                throw new Exception($exception);
            }
        } else {
            return PaymentException::paymentUnverified();
        }
    }
}
