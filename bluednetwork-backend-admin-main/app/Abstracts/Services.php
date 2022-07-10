<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           Services.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     22/08/2021, 11:10 PM
 */

namespace App\Abstracts;

use App\Contracts\ServiceInterface;
use App\Exceptions\PaymentException;
use App\Exceptions\WalletException;
use App\Repositories\Common\TransactionRepository;
use App\Repositories\Common\WalletRepository;
use App\Repositories\Finance\OrderRepository;
use App\Services\Generators\OrderNumberSequencer;
use App\Traits\Common\Flutterwave;
use App\Traits\Common\Paystack;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Prettus\Repository\Exceptions\RepositoryException;

/**
 * Class Services
 *
 * @package App\Abstracts
 */
abstract class Services implements ServiceInterface
{
    use Paystack;

    /** @var Authenticatable */
    protected Authenticatable $currentUser;

    /** @var array */
    private array $orderMeta;

    /** @var array  */
    private array $customFields;

    /**
     * Services constructor.
     *
     * @param $serviceObject
     * @param WalletRepository $walletRepository
     * @param TransactionRepository $transactionRepository
     */
    public function __construct(
        protected $serviceObject,
        protected WalletRepository $walletRepository,
        protected TransactionRepository $transactionRepository
    )
    {
    }

    /**
     * @return string
     */
    abstract public function getName(): string;

    /**
     * @return string
     */
    abstract public function getDescription(): string;

    /**
     * @param Authenticatable $user
     * @return $this
     */
    public function setCurrentUser(Authenticatable $user): static
    {
        $this->currentUser = $user;

        return $this;
    }

    /**
     * @param array $data
     * @param array $rules
     * @param array $rulesMessages
     * @return Validator|bool
     */
    public function validate(array $data = [], array $rules = [], array $rulesMessages = []): Validator|bool
    {
        $validator = $this->serviceObject->validateCustomFieldsRequest($data, $rules, $rulesMessages);
      
        if ($validator->fails()) {
            return $validator;
        }
        return true;
    }

    /**
     * @param Model $model
     * @param float|null $amount
     * @param array|null $orderItems
     * @param string|null $modeOfPayment
     * @param array|null $fields
     * @param array|null $meta
     * @return mixed
     */
    protected function saveOrder(Model $model, ?float $amount, ?array $orderItems, ?string $modeOfPayment = null, ?array $fields = [], ?array $meta = []): mixed
    {
        $order = $model->orders()->create([
            'user_id' => $this->currentUser->getkey(),
            'sub_total' => $amount,
            'grand_total' => $amount,
            'payment_method' => $modeOfPayment,
            'order_number' => OrderNumberSequencer::generate(),
            'status' => 'pending'
        ]);
        if (isset($meta) && count($meta)) {
            foreach ($meta as $orderMetaKey => $orderMetaValue) {
                $order->setMeta($orderMetaKey, $orderMetaValue);
            }
        }
        if (count($fields)) {
            $order->saveCustomFields($fields);
        }
        if (count($orderItems)) {
            foreach ($orderItems as $item) {
                $saveItem = $item['item']->orderitem()->create(array_merge($item, ['order_id' => $order->id]));
                if (isset($item['fields']) && count($item['fields'])) {
                    $saveItem->saveCustomFields($item['fields']);
                }
                if (isset($item['meta']) && count($item['meta'])) {
                    foreach ($item['meta'] as $itemMetaKey => $itemMetaValue) {
                        $saveItem->setMeta($itemMetaKey, $itemMetaValue);
                    }
                }
            }
        }
        return $order;
    }

    /**
     * @param array $customFields
     * @return $this
     */
    protected function setCustomFields(array $customFields = []): static
    {
        $this->customFields = $customFields;

        return $this;
    }

    /**
     * @param array $orderMeta
     * @return $this
     */
    protected function setOrderMeta(array $orderMeta = []): static
    {
        $this->orderMeta = $orderMeta;

        return $this;
    }

    /**
     * @param $amount
     * @param $modeOfPayment
     * @param $transactionId
     * @return bool
     * @throws PaymentException
     * @throws WalletException
     * @throws RepositoryException
     * @throws Exception
     */
    protected function verifyAmountPaid($amount, $modeOfPayment, $transactionId): bool
    {
        $user = $this->currentUser;
        if ($modeOfPayment === 'wallet') {
            //check if user has enough in wallet to book listing
            if (!$user->wallet->canWithdraw($amount)) {
                throw WalletException::insufficientBalance();
            }

            // $this->walletRepository->withDraw($user, $amount);
        } else {
            if (!$this->validOnlinePaymentMode($modeOfPayment)) {
                return false;
            }
            $refNumber = $transactionId;
            $transaction = $this->transactionRepository->scopeQuery(function ($query) use ($refNumber) {
                return $query->whereHasMeta('psk_transaction_id')->whereMeta('psk_transaction_id', $refNumber);
            })->count();

            if ($transaction) {
                throw PaymentException::transactionExist();
            }
            $paymentData = $this->verifyPayment($refNumber, round($amount, 2));

            if ($this->invalidPayment($user, $paymentData)) {
                throw PaymentException::paymentUnverified();
            }
        }
        return true;
    }

    /**
     * @param $modeOfPayment
     * @return bool
     */
    private function validOnlinePaymentMode($modeOfPayment): bool
    {
        return in_array($modeOfPayment, ['paystack', 'flutterwave']);
    }

    /**
     * @param $user
     * @param $paymentData
     * @return bool
     */
    private function invalidPayment($user, $paymentData): bool
    {
        return ($paymentData == false || ((strtolower($user->email) !== strtolower($paymentData['customer']['email']))));
    }
}
