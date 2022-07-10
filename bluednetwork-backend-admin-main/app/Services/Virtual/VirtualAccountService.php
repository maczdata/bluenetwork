<?php

namespace App\Services\Virtual;

use App\Models\Users\User;
use App\Models\Users\VirtualAccount;
use Illuminate\Support\Str;


class VirtualAccountService
{
    // public function createCustomer($request)
    // {
    //   $user = User::where(['email' => $request->email])->first();
    //   $url = "https://api.paystack.co/customer";
    //   $fields = [
    //     "email" => $user->email,
    //     "first_name" => $user->first_name,
    //     "last_name" => $user->last_name,
    //     "phone" => $user->phone_number
    //   ];
    //   $fields_string = http_build_query($fields);
    //   //open connection
    //   $ch = curl_init();

    //   //set the url, number of POST vars, POST data
    //   curl_setopt($ch,CURLOPT_URL, $url);
    //   curl_setopt($ch,CURLOPT_POST, true);
    //   curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
    //   curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    //     "Authorization: Bearer ". config('services.paystack.secret_key'),
    //     "Cache-Control: no-cache",
    //   ));

    //   //So that curl_exec returns the contents of the cURL; rather than echoing it
    //   curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);

    //   //execute post
    //   $result = curl_exec($ch);
    //   curl_close($ch);

    //   return $result;
    // }

    // public function getPaytackCustomer($email)
    // {
    //   $curl = curl_init();

    //   curl_setopt_array($curl, array(
    //     CURLOPT_URL => "https://api.paystack.co/customer/".$email,
    //     CURLOPT_RETURNTRANSFER => true,
    //     CURLOPT_ENCODING => "",
    //     CURLOPT_MAXREDIRS => 10,
    //     CURLOPT_TIMEOUT => 30,
    //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //     CURLOPT_CUSTOMREQUEST => "GET",
    //     CURLOPT_HTTPHEADER => array(
    //       "Authorization: Bearer ". config('services.paystack.secret_key'),
    //       "Cache-Control: no-cache",
    //     ),
    //   ));

    //   $response = curl_exec($curl);

    //   curl_close($curl);

    //   return $response;

    // }

    // public function getPaytackCustomerByCode($code)
    // {
    //   $curl = curl_init();

    //   curl_setopt_array($curl, array(
    //     CURLOPT_URL => "https://api.paystack.co/customer/:".$code,
    //     CURLOPT_RETURNTRANSFER => true,
    //     CURLOPT_ENCODING => "",
    //     CURLOPT_MAXREDIRS => 10,
    //     CURLOPT_TIMEOUT => 30,
    //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //     CURLOPT_CUSTOMREQUEST => "GET",
    //     CURLOPT_HTTPHEADER => array(
    //       "Authorization: Bearer ". config('services.paystack.secret_key'),
    //       "Cache-Control: no-cache",
    //     ),
    //   ));

    //   $response = curl_exec($curl);

    //   curl_close($curl);

    //   return $response;

    // }

    public function authenticateMonifyDetails()
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => config('services.monnify.monnify_url') . '/api/v1/auth/login/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => [
                'Authorization: Basic ' . base64_encode(config('services.monnify.api_key') . ':' . config('services.monnify.secret_key')),
            ],
        ]);

        $response = curl_exec($curl);

        curl_close($curl);

        return json_decode($response, false)->responseBody->accessToken;
    }

    public function createVirtualAccount($request)
    {
        $accessToken = $this->authenticateMonifyDetails();

        $user = User::where(['email' => $request->email])->first();

        $fields =
            '{
        "accountReference": "' . Str::Random(10) . '",
        "accountName": "' . $user->first_name . ' ' . $user->last_name . '",
        "currencyCode": "NGN",
        "contractCode": "' . config('services.monnify.monnify_contract_code') . '",
        "customerEmail": "' . $user->email . '",
        "customerName": "' . $user->first_name . ' ' . $user->last_name . '"
     }';

        $fields_string = json_encode($fields);

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => config('services.monnify.monnify_url') . '/api/v1/bank-transfer/reserved-accounts',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $fields,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json', "Authorization: Bearer $accessToken"],
        ]);

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
    }

    public function saveAccount($data)
    {
        return VirtualAccount::create($data);
    }

    public function getVirtualAccount($order_ref)
    {
        $accessToken = $this->authenticateMonifyDetails();
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => config('services.monnify.monnify_url') . "/api/v1/bank-transfer/reserved-accounts/" . $order_ref,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer $accessToken",
            ],
        ]);

        $response = curl_exec($curl);

        curl_close($curl);
      
        $response = json_decode($response);

        if ($response->responseMessage == "success") {
            return $response->responseBody;
        }

        return null;
    }

    public function getBankAccountData($request, $bank)
    {
        $accessToken = $this->authenticateMonifyDetails();

        $fields =
            '{
                "account_number": "' . $request['account_number']. '",
                "bank_code": "' . $bank->code . '",
             }';

        $fields_string = json_encode($fields);

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => config('services.monnify.monnify_url') . "/api/v1/disbursements/account/validate?accountNumber=" . $request['account_number'] ."&bankCode=". $bank->code ,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer $accessToken",
            ],
        ]);

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
    }

    // public function verifyBvn($request)
    // {
    //   $user = User::where(['email' => $request->email])->first();
    //   $url = "https://api.paystack.co/bvn/match";

    //   $fields = [
    //     'bvn' => $request->bvn,
    //     'account_number' => $request->account_name,
    //     'bank_code' =>  $request->bank_code,
    //     'first_name' => $user->first_name,
    //     'last_name' => $user->last_name,

    //   ];

    //   $fields_string = http_build_query($fields);

    //   //open connection
    //   $ch = curl_init();

    //   //set the url, number of POST vars, POST data
    //   curl_setopt($ch,CURLOPT_URL, $url);
    //   curl_setopt($ch,CURLOPT_POST, true);
    //   curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
    //   curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    //     "Authorization: Bearer ". config('services.paystack.secret_key'),
    //     "Cache-Control: no-cache",
    //   ));

    //   //So that curl_exec returns the contents of the cURL; rather than echoing it
    //   curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);

    //   //execute post
    //   $result = curl_exec($ch);

    //   return  $result;
    // }

}
