<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           AirtimeToCash.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Services\ServiceHandler;

use App\Abstracts\Services;
use App\Exceptions\PaymentException;
use App\Exceptions\WalletException;
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

/**
 * Class ExchangeTrading
 *
 * @package App\Services\ServiceHandler
 */
class AirtimeToCash extends Services
{
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

    /**
     * @return string
     */
    public function getName(): string
    {
        return "Airtime Top Cash";
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
    public function validate(array $data = [], array $rules = [], array $rulesMessages = []): \Illuminate\Contracts\Validation\Validator|bool
    {
        $rule = [
            'custom_fields.airtime_for_cash_network' => [
                'required',
                Rule::exists('services', 'id')->where(function ($query) {
                    return $query->where(['parent_id' => $this->serviceObject->id]);
                }),
            ],
            'custom_fields.airtime_for_cash_sender_phone_number' => 'required',
            //'custom_fields.airtime_for_cash_network' => 'required',
            'custom_fields.airtime_amount_transferred' => 'required|numeric|min:1000',
            'proof_document' => 'required|mimes:jpeg,jpg,png'
        ];

        $rulesMessage = [
            'custom_fields.airtime_for_cash_network.required' => 'Network is required',
            'custom_fields.airtime_for_cash_network.exists' => 'Network does not exist',
            'custom_fields.airtime_for_cash_sender_phone_number.required' => 'Phone number is required',
            'custom_fields.airtime_amount_transferred.required' => 'Transferred amount is required',
            'custom_fields.airtime_amount_transferred.numeric' => 'Transferred amount is can be numbers only',
            'custom_fields.airtime_amount_transferred.min' => '
            Transferred amount can not be below ' . core()->formatBasePrice(1000),
        ];
        $validator = Validator::make($data, $rule, $rulesMessage);
        if ($validator->fails()) {
            return $validator;
        }
        return true;
    }

    /**
     * @param Request $data
     * @return string
     * @throws Exception
     */
    public function execute(Request $data): string
    {
        $customFields = $data['custom_fields'];
        $serviceId = $customFields['airtime_for_cash_network'];
        $senderPhoneNumber = $customFields['airtime_for_cash_sender_phone_number'];
        $transferredAmount = $customFields['airtime_amount_transferred'];
        $user = $this->currentUser;
        $serviceTo = $this->serviceRepository->scopeQuery(function ($query) use ($serviceId) {
            return $query->where(['id' => $serviceId]);
        })->first();
        $creditingSuccessful = false;
        if ($transferredAmount > $user->profile->accountLevel->transaction_limit) {
            throw new Exception(
                'Sorry, your account is currently limited to transactions equal or less than'
                . core()->formatBasePrice($user->profile->accountLevel->transaction_limit ?? 0)
            );
        }
        DB::beginTransaction();
        try {

            $percentageTaken = $serviceTo->getMeta('percentage_taken') ?? 0;
            $creditingSuccessful = false;
            $systemPercent = getPercentOfNumber((int) $transferredAmount, $percentageTaken);
            $newAmount = ($transferredAmount - $systemPercent);
            $order = $this->saveOrder($serviceTo, $transferredAmount, [], null, $customFields);

            $orderMeta = [
                'sender_phone_number' => $senderPhoneNumber,
                'amount_transferred' => $transferredAmount,
                'amount_to_give' => $newAmount,
                'percentage_taken' => $percentageTaken,

            ];
            $order->syncMeta($orderMeta);
            if (isset($data['proof_document'])) {
                $order->addMediaFromRequest('proof_document')
                    ->preservingOriginal()->toMediaCollection('proof_document');
            }
            $creditingSuccessful = 'Your airtime to cash order of ' . core()->formatBasePrice($transferredAmount) . ' has being received. ';
            $creditingSuccessful .= 'once verified, your bank account would be credited with ' . core()->formatBasePrice($newAmount);
            DB::commit();
            return $creditingSuccessful;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
