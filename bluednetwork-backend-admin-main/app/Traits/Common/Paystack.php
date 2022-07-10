<?php

/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           Paystack.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Traits\Common;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\HigherOrderCollectionProxy;
use Yabacon\Paystack as BasePaystack;

/**
 * Trait Paystack
 * @package App\Traits\Common
 */
trait Paystack
{
    protected $secretKey;


    /**
     * @param string|null $secretKey
     */
    public function setPaystackKey(string $secretKey = null)
    {
        if (is_null($secretKey)) {
            $this->secretKey = config('services.paystack.secret_key');
        }
        $this->secretKey = $secretKey;
    }

    /**
     * @return BasePaystack
     */
    private function initiatePaystack()
    {
        if (is_null($this->secretKey)) {
            $this->secretKey = config('services.paystack.secret_key');
        }
        return new BasePaystack($this->secretKey);
    }


    /**
     * @param $refrenceNumber
     * @param $price
     * @return HigherOrderCollectionProxy|mixed|BasePaystack\Helpers\Router
     * @throws Exception
     */
    protected function verifyPayment($referenceNumber, $price) //Todo fix this
    {
        if (!$referenceNumber) {
            throw new Exception('transaction reference is required');
        }
        // initiate the Library's Paystack Object
        $paystack = $this->initiatePaystack();
        $tranx = $paystack->transaction->verify([
            'reference' => $referenceNumber, // unique to transactions
        ]);
        if (array_key_exists('data', collect($tranx)->toArray()) && array_key_exists('status', collect($tranx->data)->toArray()) && ($tranx->data->status === 'success')) {
            $amount_paid = ($tranx->data->amount / 100);
            // check if the amount paid is equal to the order amount.
            if ($amount_paid < $price) {
                throw new Exception('Your payment transaction was successful,
                but the amount paid is not the same as the total order amount.');
            }
            // transaction was successful...
            return $tranx->data;
        }

        if (array_key_exists('data', collect($tranx)->toArray())
            && array_key_exists('status', collect($tranx->data)->toArray()) && ('failed' === $tranx->data->status)) {
            logger()->error('paystack response error : ' . $tranx->data->gateway_response);
            throw new Exception($tranx->data->gateway_response);
        }

        throw new Exception('An Error Occurred, please try again');
    }

    public function resolveBankAccountAndBvn($payload): object
    {
        $url = "https://api.paystack.co/transferrecipient";

        $response = Http::withToken($this->secretKey)->post($url, $payload);
        if ($response->failed()) {
            return $this->paymentGatewayDto(false, $response->json(), 'Error creating transfer recipient');
        }
        return $this->paymentGatewayDto(true, $response->json(), 'Transfer recipient created');
    }

    /**
     * @param array $arg
     * @return bool
     * @throws Exception
     */
    protected function verifyRecipientAccount(array $arg): bool
    {
        $paystack = $this->initiatePaystack();
        try {
            $recipientAccount = $paystack->bank->resolve($arg);
            return (!$recipientAccount->status) ? false : true;
        } catch (\Yabacon\Paystack\Exception\ApiException $e) {
            throw new Exception($e->getMessage());
        }
    }

    protected function paymentGatewayDto(bool $status, array $data, string $message)
    {
        return (object) [
            "status" => $status,
            "data" => $data,
            "message" => $message,
        ];
    }

    /**
     * @param array $arg
     * @return bool
     * @throws Exception
     */
    protected function verifyBvn(array $arg): bool
    {
        $paystack = $this->initiatePaystack();
        try {
            $recipientAccount = $paystack->bank->resolveBvn($arg);

            return (!$recipientAccount->status) ? false : true;
        } catch (\Yabacon\Paystack\Exception\ApiException $e) {
            throw new Exception($e);
        }
    }

    /**
     * @param array $arg
     * @return mixed
     * @throws Exception
     */
    protected function createRecipient(array $arg): mixed
    {
        $paystack = $this->initiatePaystack();
        try {
            $tranx = $paystack->transferrecipient->create($arg);
            if (
                array_key_exists(
                    'data',
                    collect($tranx)->toArray()
                ) && array_key_exists('status', collect($tranx)->toArray())
                && ($tranx->status == true)
            ) {
                $data = $tranx->data;
                if ($data->active == false || $data->is_deleted == true) {
                    throw new Exception('Inactive or deleted recipient');
                }
                return $tranx->data;
            }
            throw new Exception('Failed to create recipient');
        } catch (\Yabacon\Paystack\Exception\ApiException $e) {
            logger()->error('paystack transfer error : ' . $e);
            throw new Exception($e);
        }
    }

    protected function deleteRecipient($recipientCode): bool
    {
        try {
            $response = Http::withToken(env('PAYSTACK_SECRET_KEY'))->delete('https://api.paystack.co/transferrecipient', [
                'id_or_code' => $recipientCode,
            ]);
            $jsonData = $response->json();
            return $jsonData['status'] == true;
        } catch (Exception $exception) {
            return false;
        }
    }


    /**
     * @param array $arg
     * @return mixed
     * @throws Exception
     */
    protected function transfer(array $arg): mixed
    {
        $paystack = $this->initiatePaystack();

        try {
            $transfer = $paystack->transfer->initiate($arg);
            if ($transfer->status == true) {
                return $transfer->data;
            }
            throw new Exception('Transfer failed.');
        } catch (\Yabacon\Paystack\Exception\ApiException $e) {
            logger()->error('paystack transfer error : ' . $e);
            throw new Exception($e);
        }
    }

    /**
     * @throws Exception
     */
    protected function verifyTransfer(array $arg)
    {
        $paystack = $this->initiatePaystack();

        try {
            $transfer = $paystack->transfer->fetch($arg);
            if ($transfer->status == true) {
                return $transfer->data;
            }
            throw new Exception('Transfer verification failed.');
        } catch (\Yabacon\Paystack\Exception\ApiException $e) {
            logger()->error('paystack transfer fetch error : ' . $e);
            throw new Exception($e);
        }
    }

    /**
     * @param $bvnNumber
     * @return mixed
     * @throws Exception
     */
    protected function bvnVerification($bvnNumber): mixed
    {
        try {
            $response = Http::withToken(config('services.paystack.secret_key'))->get('https://api.paystack.co/bank/resolve_bvn/' . $bvnNumber);
            $content = json_decode($response->body());
            if ($content['status'] == true) {
                return $content;
            }
            return false;
        } catch (\Exception $e) {
            logger()->error('paystack bvn verification error : ' . $e);
            throw new Exception($e);
        }
    }
}
