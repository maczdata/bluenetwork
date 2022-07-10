<?php

/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           Flutterwave.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Traits\Common;

use Exception;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Yabacon\Paystack as BasePaystack;

trait Flutterwave
{
    protected $secretKey;

    protected $stagingUrl = 'https://api.flutterwave.com';
    protected $liveUrl = 'https://api.flutterwave.com';
    protected $baseUrl;

    /**
     * @param string|null $secretKey
     */
    public function setKey(string $secretKey = null)
    {
        if (is_null($secretKey)) {
            $this->secretKey = config('bds.flutterwave.secret_key');
        }
        $this->secretKey = $secretKey;
    }

    protected function makePaystackRequest($method, $path, $args, $secretKey = '')
    {
        try {
            return Http::retry(3, 100)->withToken(config('bds.flutterwave.secret_key'))
                ->{$method}($this->baseUrl . $path, $args)->throw()->json();
        } catch (Exception $e) {
            //logger()->error($err . $e);
            return false;
        }
    }

    /**
     * @param $transactionId
     * @param $price
     * @param string $secretKey
     * @return bool
     * @throws Exception
     */
    protected function verifyPayment($transactionId, $price, string $secretKey = '') //Todo fix this
    {
        $requeryCount = 0;
        if (is_null($secretKey) || empty($secretKey)) {
            $secretKey = config('bds.flutterwave.secret_key');
        }
        try {
            $response = Http::withToken($secretKey)->get('https://api.flutterwave.com/v3/transactions/' . $transactionId . '/verify');

            $content = $response->json();
            if (array_key_exists('data', $content) && array_key_exists('status', $content) && ($content['status'] == 'success')) {
                $amount_paid = $content['data']['charged_amount'];
                if ($amount_paid < $price) {
                    throw new Exception('Your payment transaction was successful, but the amount paid is not the same as the total order amount.');
                }
                return $content['data'];
            }
            return false;
        } catch (\Exception $e) {
            logger()->error('flutter wave payment verification error : ' . $e);
            throw new Exception($e);
        }
    }

    /**
     * @param array $arg
     * @param null $secretKey
     * @return false|mixed
     * @throws Exception
     */
    protected function verifyRecipientAccount(array $arg, $secretKey = null)
    {
        if (is_null($secretKey) || empty($secretKey)) {
            $secretKey = config('bds.flutterwave.secret_key');
        }
        try {
            $response = Http::withToken($secretKey)->post('https://api.flutterwave.com/v3/accounts/resolve', $arg);

            $content = $response->collect();
            if ($content->has('data') && $content->has('status') && ($content['status'] == 'success')) {
                logger()->info('Banking Data 1: ' . json_encode($content['data']));
                return $content['data'];
            }
            return false;
        } catch (\Exception $e) {
            logger()->error('flutter wave verify recipient account : ' . $e);
            throw new Exception($e);
        }
    }

    /**
     * @throws Exception
     */
    protected function listRecipients()
    {
        try {
            $response = Http::withToken(config('bds.flutterwave.secret_key'))->get('https://api.flutterwave.com/v3/beneficiaries');
            //logger()->info('ssssdsds : ' . json_encode($response->collect()));
            $data = $response->collect()->toArray();
            return collect($data['data']);
        } catch (\Exception $e) {
            logger()->error('flutter wave create recipient : ' . $e);
            throw new Exception($e);
        }
    }

    /**
     * @param array $arg
     * @return mixed
     * @throws Exception
     */
    protected function createRecipient(array $arg)
    {
        try {
            $response = Http::withToken(config('bds.flutterwave.secret_key'))->post('https://api.flutterwave.com/v3/beneficiaries', $arg);

            $content = $response->collect();
            logger()->info('is success : ' . json_encode($response->status()));
            if ($content->has('data') && $content->has('status') && ($content['status'] == 'success')) {
                logger()->info('Banking Data2 : ' . json_encode($content['data']));
                return $content['data'];
            }
            //return false;
        } catch (\Exception $e) {
            logger()->error('flutter wave create recipient : ' . $e);
            throw new Exception($e);
        }
    }

    /**
     * @param $beneficiaryId
     * @return bool
     * @throws Exception
     */
    protected function deleteRecipient($beneficiaryId): bool
    {
        try {
            $response = Http::withToken(config('bds.flutterwave.secret_key'))->delete('https://api.flutterwave.com/v3/beneficiaries/' . $beneficiaryId);

            $content = $response->json();
            if (array_key_exists('data', $content) && array_key_exists('status', $content) && ($content['status'] == 'success')) {
                return true;
            }
            return false;
        } catch (\Exception $e) {
            logger()->error('flutter wave delete recipient : ' . $e);
            throw new Exception($e);
        }
    }


    /**
     * @param array $arg
     * @return mixed
     * @throws Exception
     */
    protected function transfer(array $arg)
    {
        try {
            $response = Http::withToken(config('bds.flutterwave.secret_key'))->post('https://api.flutterwave.com/v3/transfers', $arg);

            $content = $response->json();
            if (array_key_exists('data', $content) && array_key_exists('status', $content) && ($content['status'] == 'success')) {
                return $content['data'];
            }
            return false;
        } catch (\Exception $e) {
            logger()->error('flutter wave initiate transfer : ' . $e);
            throw new Exception($e);
        }
    }

    protected function verifyTransfer($transferId)
    {
        try {
            $response = Http::withToken(config('bds.flutterwave.secret_key'))->get('https://api.flutterwave.com/v3/transfers/' . $transferId);

            $content = $response->json();
            if (array_key_exists('data', $content) && array_key_exists('status', $content) && ($content['status'] == 'success')) {
                return $content['data'];
            }
            return false;
        } catch (\Exception $e) {
            logger()->error('flutter wave verify transfer : ' . $e);
            throw new Exception($e);
        }
    }

    /**
     * @param $bvnNumber
     * @return false|mixed
     * @throws Exception
     */
    protected function bvnVerification($bvnNumber)
    {
        try {
            $response = Http::withToken(config('bds.flutterwave.secret_key'))->post('https://api.paystack.co/bank/bvns/' . $bvnNumber);
            $content = $response->json();
            if (array_key_exists('data', $content) && array_key_exists('status', $content) && ($content['status'] == 'success')) {
                return $content['data'];
            }
            return false;
        } catch (\Exception $e) {
            logger()->error('flutterwave bvn verification error : ' . $e);
            throw new Exception($e);
        }
    }


    protected function verifyRecipient($beneficiaryId)
    {
        try {
            $response = Http::withToken(config('bds.flutterwave.secret_key'))->get('https://api.flutterwave.com/v3/beneficiaries/' . $beneficiaryId);

            $content = $response->json();
            if (array_key_exists('data', $content) && array_key_exists('status', $content) && ($content['status'] == 'success')) {
                return $content['data'];
            }
            return false;
        } catch (\Exception $e) {
            logger()->error('flutter wave verify transfer : ' . $e);
            throw new Exception($e);
        }
    }

  
}
