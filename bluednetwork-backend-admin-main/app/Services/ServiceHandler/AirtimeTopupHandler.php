<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           AirtimePurchase.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     15/08/2021, 6:09 PM
 */

namespace App\Services\ServiceHandler;

use App\Abstracts\Services;
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
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Prettus\Repository\Exceptions\RepositoryException;
use Throwable;

/**
 * Class AirtimePurchase
 *
 * @package App\Services\ServiceHandler
 */
class AirtimeTopupHandler extends Services
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
        protected WalletRepository      $walletRepository,
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
        return "Airtime Top up";
    }

    /**
     * @return string
     */
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
        $modeOfPayment = $data['mode_of_payment'];
        $customFields = $data['custom_fields'];
        $serviceId = $customFields['network'];
        $serviceName = $customFields['network_value'];
        $phoneNumber = $customFields['phone_number'];
        $amount = $customFields['amount'];
        $user = $this->currentUser;
        $serviceTo = $this->serviceRepository->scopeQuery(function ($query) use ($serviceId) {
            return $query->where('id', $serviceId);
        })->first();

        if ($amount > $user->profile->accountLevel->transaction_limit) {
            throw new Exception(
                'Sorry, your account is currently limited to transactions equal or less than'
                . core()->formatBasePrice($user->profile->accountLevel->transaction_limit ?? 0)
            );
        }
        try {
            $processOtherData = $this->verifyAmountPaid($amount, $modeOfPayment, $transactionId);

            if ($processOtherData) {
                $creditingSuccessful = false;
                DB::beginTransaction();
                try {
                    $thirdPartyError = false;
                    $order = $this->saveOrder($serviceTo, $amount, [], $modeOfPayment, $customFields);
                    $transaction = $user->transactions()
                        ->create([
                            'transactionable_type' => $order->getMorphClass(),
                            'transactionable_id' => $order->getKey(),
                            'amount' => $amount,
                            'type' => 1,
                        ]);
                    $isSuccess = false;
                    // if ($serviceTo->key == 'airtime-topup-airtel') {
                     date_default_timezone_set("Africa/Lagos");
                    $purchaseData = $this->purchase([
                        'serviceID' => $serviceName,
                        'amount' => $amount,
                        'phone' => $phoneNumber,
                        'request_id' => date('YmdHi').$order->order_number,
                    ]);
                    
                    if ($purchaseData['code'] == '000') {
                        $isSuccess = true;
                        if ($modeOfPayment === 'wallet') {
                            $this->walletRepository->withDraw($user, $amount);
                        }
                    }
                    if ($purchaseData['code'] === '014') {
                        $creditingSuccessful = 'Order has previously being completed ';
                        $thirdPartyError = true;
                    }
                    if ($purchaseData['code'] === '016') {
                        $creditingSuccessful = 'Order could not be completed';
                        $thirdPartyError = true;
                    }
                    if ($purchaseData['code'] === '018') {
                        $creditingSuccessful = 'Merchant wallet balance is too low to complete this transaction';
                        $thirdPartyError = true;
                    }

                    if ($purchaseData['code'] === '034') {
                        $creditingSuccessful = 'The service being requested for has been suspended for the time being.';
                        $thirdPartyError = true;
                    }
                    if ($purchaseData['code'] === '083') {
                        $creditingSuccessful = 'Oops!!! System error. Please contact tech support';
                        $thirdPartyError = true;
                    }
                    // } else {
                    //     $ussdCode = $serviceTo->getMeta('simhost_subscription_code');
                    //     //$serverConfig = config('bds.smshostng.' . $serviceTo->key);
                    //     $serverConfig = config('bds.bds_service_types.' . $serviceTo->service_type->slug . '.services.' . $serviceTo->parent->key . '.child_services');
                    //     //var_dump($serverConfig[$serviceTo->key]);
                    //     //die();
                    //     $serverId = $serverConfig[$serviceTo->key]['server_id'];
                    //     if ($serviceTo->key == 'airtime-topup-glo') {
                    //         $ussdCode = Str::replaceArray('?', [$phoneNumber, $amount], $ussdCode);
                    //     } else {
                    //         $ussdCode = Str::replaceArray('?', [$amount, $phoneNumber], $ussdCode);
                    //     }
                    //     $apikey = config('bds.smshostng.api_key');
                    //     /*$requestResponse = Http::asForm()->post('https://simhostng.com/api/ussd', [
                    //         'apikey' => $apikey,
                    //         'server' => $serverId,
                    //         'sim' => 1,
                    //         'number' => $ussdCode,
                    //     ]);
                    //     if ($requestResponse->failed()) {
                    //         $requestResponse->throw();
                    //     }
                    //     $creditingSuccessful = false;
                    //     $resData = $requestResponse->object();
                    //     if ($resData->data[0]->response === 'Ok') {
                    //         $isSuccess = true;
                    //     }*/
                    // }
                    if ($isSuccess) {
                        $transaction->update(['status' => 1]);
                        $creditingSuccessful = 'You successfully purchased ' . ucfirst($serviceTo->title) . ' ' . core()->formatBasePrice($amount) . ' airtime for ' . $phoneNumber;
                    } else {
                        $transaction->update(['status' => 0]);
                    }
                    DB::commit();
                    // $creditingSuccessful = $purchaseData;
                    return $creditingSuccessful;
                } catch (Exception $exception) {
                    DB::rollBack();
                    logger()->error('Airtime topup error : ' . $exception);
                    throw new Exception($exception->getMessage());
                }
            }
            throw new Exception(PaymentException::paymentUnverified());
        } catch (Throwable $exception) {
            logger()->error('Airtime topup error : ' . $exception);
            throw new Exception($exception->getMessage());
        }
    }
}
