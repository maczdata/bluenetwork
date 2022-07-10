<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           CableSubscription.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     15/08/2021, 6:09 PM
 */

namespace App\Services\ServiceHandler;

use App\Abstracts\Services;
use App\Exceptions\CableTvException;
use App\Exceptions\ElectricityException;
use App\Exceptions\PaymentException;
use App\Exceptions\WalletException;
use App\Repositories\Common\ServiceRepository;
use App\Repositories\Common\ServiceVariantRepository;
use App\Repositories\Common\TransactionRepository;
use App\Repositories\Common\WalletRepository;
use App\Traits\Common\InteractsWithVtpass;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Exceptions\RepositoryException;

/**
 * Class CableSubscription
 *
 * @package App\Services\ServiceHandler
 */
class CableSubscription extends Services
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
        return "Cable Tv Subscription";
    }

    public function getDescription(): string
    {
        return "";
    }

    /**
     * @param $data
     * @return mixed
     * @throws CableTvException
     */
    public function preview($data): mixed
    {
        $customFields = $data['custom_fields'];
        $childService = $customFields['cable_tv_service'];
        $package = $customFields['cable_tv_package'];
        $smartCardNumber = $customFields['cable_tv_smart_card_number'];
        
        $verifySmartCard = $this->verify([
            'serviceID' => $childService,
            'billersCode' => $smartCardNumber,
        ]);

        if ($verifySmartCard['code'] !== '000') {
            throw CableTvException::invalidSmartCard();
        }

        return $verifySmartCard;
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
        //     "cable_tv_service" => 18,
        //     'cable_tv_package' => "gotv-jolli",
        //     "cable_tv_smart_card_number" => "1212121212",
          
        // ];
        $customFields = $data['custom_fields'];
        $childService = $customFields['cable_tv_service'];
        $package = $customFields['cable_tv_package'];
        $smartCardNumber = $customFields['cable_tv_smart_card_number'];
        $variantIdTo = app(ServiceVariantRepository::class)->scopeQuery(function ($query) use ($package, $childService) {
            return $query->whereHas('service', function ($query) use ($childService) {
                $query->where('id', $childService)->orWhere('key', $childService);
            })->where(['key' => $package]);
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
            $orderMeta = [];
            DB::beginTransaction();
            try {

                $thirdPartyError = false;
                $verifySmartCard = $this->verify([
                    'serviceID' => $variantIdTo->service->key,
                    'billersCode' => $smartCardNumber,
                ]);

                if ($verifySmartCard['code'] !== '000') {
                    throw CableTvException::invalidSmartCard();
                }
                $order = $this->saveOrder($variantIdTo, $amount, [], $modeOfPayment, $customFields);
                $transaction = $user->transactions()
                    ->create([
                        'transactionable_type' => $order->getMorphClass(),
                        'transactionable_id' => $order->getKey(),
                        'amount' => $amount,
                        'type' => 1,
                    ]);
                    date_default_timezone_set("Africa/Lagos");
                    // dd($variantIdTo->service->key);
                $purchaseData = $this->purchase([
                    'serviceID' => $variantIdTo->service->key,
                    'billersCode' => $smartCardNumber,
                    'variation_code' => $package,
                    'request_id' =>  date('YmdHi').$order->order_number,
                    'amount' => $amount,
                    'phone' => $user->phone_number,
                ]);

                $creditingSuccessful = false;

                if ($purchaseData['code'] === '000') {
                    if ($modeOfPayment === 'wallet') {
                        $this->walletRepository->withDraw($user, $amount);
                    }
                    $creditingSuccessful = 'You successfully purchased ';
                    $creditingSuccessful .= ucfirst($variantIdTo->title) . ' for ' . $this->serviceObject->title . '(' . ucfirst($variantIdTo?->service?->title) . ') smart card ' . $smartCardNumber;
                    $transaction->update(['status' => 1]);
                    $order->syncMeta($orderMeta);
                }
                if ($purchaseData['code'] === '012') {
                    $creditingSuccessful = 'Package does not exist';
                    $transaction->update(['status' => 0]);
                    $thirdPartyError = true;
                }
                if ($purchaseData['code'] === '014') {
                    $creditingSuccessful = 'Order has previously being completed ';
                    $thirdPartyError = true;
                }
                if ($purchaseData['code'] === '016') {
                    $creditingSuccessful = 'Order could not be completed';
                    $transaction->update(['status' => 0]);
                    $thirdPartyError = true;
                }
                if ($purchaseData['code'] === '018') {
                    $creditingSuccessful = 'Merchant wallet balance is too low to complete this transaction';
                    $transaction->update(['status' => 0]);
                    $thirdPartyError = true;
                }
                if ($purchaseData['code'] === '034') {
                    $creditingSuccessful = 'The service being requested for has been suspended for the time being.';
                    $transaction->update(['status' => 0]);
                    $thirdPartyError = true;
                }
                if ($purchaseData['code'] === '083') {
                    $creditingSuccessful = 'Oops!!! System error. Please contact tech support';
                    $transaction->update(['status' => 0]);
                    $thirdPartyError = true;
                }

                DB::commit();
                //  $creditingSuccessful = $purchaseData;
                return $creditingSuccessful;
            } catch (Exception $exception) {
                logger()->error('');
                DB::rollBack();
                throw new Exception($exception);
            }
        } else {
            return PaymentException::paymentUnverified();
        }
    }
}
