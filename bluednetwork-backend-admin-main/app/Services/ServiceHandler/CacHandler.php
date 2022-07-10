<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           CacHandler.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     23/08/2021, 2:38 AM
 */

namespace App\Services\ServiceHandler;

use App\Models\Common\ServiceVariant;
use App\Repositories\Finance\OrderCacDirectorRepository;
use Exception;
use App\Abstracts\Services;
use App\Exceptions\PaymentException;
use App\Exceptions\WalletException;
use App\Repositories\Common\ServiceRepository;
use App\Repositories\Common\TransactionRepository;
use App\Repositories\Common\WalletRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Prettus\Repository\Exceptions\RepositoryException;

class CacHandler extends Services
{
    /**
     * @var Application|mixed
     */
    private mixed $serviceRepository;
    /**
     * @var Application|mixed
     */
    private mixed $orderCacDirectorRepository;

    private ?ServiceVariant $variant;

    /**
     * @param $serviceObject
     * @param WalletRepository $walletRepository
     * @param TransactionRepository $transactionRepository
     */
    public function __construct(
        protected                       $serviceObject,
        protected WalletRepository      $walletRepository,
        protected TransactionRepository $transactionRepository
    ) {
        parent::__construct($serviceObject, $walletRepository, $transactionRepository);
        $this->serviceRepository = app(ServiceRepository::class);

        $this->orderCacDirectorRepository = app(OrderCacDirectorRepository::class);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return "Cac";
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
     * @return Validator|bool
     * @throws Exception
     */
    public function validate(array $data = [], array $rules = [], array $rulesMessages = []): Validator|bool
    {
        $requireDirectors = $this->serviceObject->variants()->where(function ($query) use ($data) {
            return $query->where('key', $data['variant_key'] ?? '')->whereMeta('requires_directors', true);
        })->count();

        $rules = [
            /*'variant_key' => [
                'required',
                Rule::exists('service_variants', 'key')->where(function ($query) {
                    $query->where('service_id', $this->serviceObject->id);

                }),
            ],*/];

        $rulesMessages = [
            'package.required' => 'Package is required',
            'package.exists' => 'Package does not exist',
            'variant_key.required' => 'Cac package is required',
            'variant_key.exists' => ''
        ];
        if ($requireDirectors) {
            $rules['directors'] = "required|array|min:1";
            $rules['directors.*.designation'] = "required";
            $rules['directors.*.full_name'] = "required";
            $rules['directors.*.email'] = "required|email";
            $rules['directors.*.phone_number'] = "required";
            $rules['directors.*.address'] = "required";
            $rules['directors.*.passport'] = "required|image";
            $rules['directors.*.valid_id'] = "required|image";
            $rules['directors.*.signature'] = "required|image";

            $rulesMessages['directors.required'] = 'At least one director data is required';
            $rulesMessages['directors.*.designation.required'] = 'Director designation is required';
            $rulesMessages['directors.*.full_name.required'] = 'Director fullname is required';
            $rulesMessages['directors.*.email.required'] = 'Director email is required';
            $rulesMessages['directors.*.email.email'] = 'Director email is invalid';
            $rulesMessages['directors.*.address.required'] = 'Director address is required';
            $rulesMessages['directors.*.passport.required'] = 'Director passport is required';
            $rulesMessages['directors.*.passport.image'] = 'Director passport must be an image';
            $rulesMessages['directors.*.valid_id.required'] = 'Director ID card is required';
            $rulesMessages['directors.*.valid_id.image'] = 'Director ID card must be an image';
            $rulesMessages['directors.*.signature.required'] = 'Director signature is required';
            $rulesMessages['directors.*.signature.image'] = 'Director signature must be an image';
        } else {
            $rules['passport'] = "required|image";
            $rules['valid_id'] = "required|image";
            $rules['signature'] = "required|image";

            $rulesMessages['passport.required'] = 'Passport is required';
            $rulesMessages['passport.image'] = 'Passport must be an image';
            $rulesMessages['valid_id.required'] = 'ID card is required';
            $rulesMessages['valid_id.image'] = 'ID card must be an image';
            $rulesMessages['signature.required'] = 'Signature is required';
            $rulesMessages['signature.image'] = 'Signature must be an image';
        }
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
        $extraPreview = [];
        $extraPreview['Service'] = $this->serviceObject->title;
        /*$variant = $this->serviceObject->variants()->where(['key' => $data['variant_key']])->first();
        if (!$variant) {
            throw new Exception('Variant not available on this provider');
        }*/
        $requireDirectors = $this->variant->getMeta('requires_directors');
        $servicePrice = ($this->variant->price ?? 0);
        $extraPreview['Variant'] = $this->variant->title;
        if ($this->variant->addons->count() && isset($data['addons']) && is_array($data['addons'])) {
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
        if ($requireDirectors && isset($data['directors']) && count($data['directors'])) {
            $extraPreview['directors'] = [];
            foreach ($data['directors'] as $directorKey => $director) {
                $extraPreview['directors'][$directorKey] = [
                    'designation' => $director['designation'],
                    'full_name' => $director['full_name'],
                    'email' => $director['email'],
                    'phone_number' => $director['phone_number'],
                    'address' => $director['address']
                ];
            }
        }

        $amount = ($servicePrice + $addonPrices);
        $extraPreview['amount_to_pay'] = $amount;
        $extraPreview['formatted_amount_to_pay'] = core()->formatBasePrice($amount ?? 0);
        return array_merge($data['custom_fields'] ?? [], $extraPreview ?? []);
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
        $addonPrices = 0;
        $orderItems = [];
        $requireDirectors = $this->variant->getMeta('requires_directors');
        $servicePrice = $this->variant->price;
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
                $order = $this->saveOrder($this->variant, $amount, $orderItems, $modeOfPayment, ($data['custom_fields'] ?? []));
                if ($requireDirectors && isset($data['directors'])) {
                    $this->orderCacDirectorRepository->saveDirectors($order->id, $data['directors']);
                } else {
                    if (isset($data['passport'])) {
                        $order->addMediaFromRequest('passport')
                            ->preservingOriginal()->toMediaCollection('cac_passport');
                    }
                    if (isset($data['valid_id'])) {
                        $order->addMediaFromRequest('valid_id')
                            ->preservingOriginal()->toMediaCollection('cas_valid_id');
                    }
                    if (isset($data['signature'])) {
                        $order->addMediaFromRequest('signature')
                            ->preservingOriginal()->toMediaCollection('cac_signature');
                    }
                }
                $user->transactions()
                    ->create([
                        'transactionable_type' => $order->getMorphClass(),
                        'transactionable_id' => $order->getKey(),
                        'amount' => $amount,
                        'type' => 2,
                        'status' => 1
                    ]);
                if ($modeOfPayment === 'wallet') {
                    $this->walletRepository->withDraw($user, $amount);
                }
                $creditingSuccessful = 'Your order for service (' . ucfirst($this->serviceObject->title) . ' ' . ucfirst(optional($this->variant)->title) . ') was successful';
                DB::commit();
                return $creditingSuccessful;
            } catch (Exception $exception) {
                logger()->error('Cac handle execute error : ' . $exception);
                DB::rollBack();
                throw new Exception($exception);
            }
        }

        return PaymentException::paymentUnverified();
    }
}
