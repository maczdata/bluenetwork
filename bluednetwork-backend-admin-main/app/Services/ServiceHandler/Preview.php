<?php

namespace App\Services\ServiceHandler;

use Illuminate\Support\Facades\Http;

class Preview{

    public function verify($serviceID, $billersCode, $type = null)
    {
        $username = config('bds.vtpass.username');
        $password = config('bds.vtpass.password');
        $mode = config('bds.vtpass.mode');

        $liveUrl = "https://vtpass.com/api/merchant-verify";
        $sandboxUrl = "https://sandbox.vtpass.com/api/merchant-verify";

        $url = $mode === 'live' ? $liveUrl : $sandboxUrl;

        $params= [
            "billersCode" => $billersCode,
            "serviceID"=> $serviceID
        ];

        if($type != null)
        {
            $params["type"] = $type;
        }

        // $pk_ = "PK_784370e2ca2c01ac78d1a618dddfebe39be92d516fe";
        // $sk_ = "SK_373498f085c5bfca0b562e7253028aad02a6a9fdb92";
        // $api_key = "6123bf9d235832d071e5f3b85ca3598e";

        // $res = Http::withBasicAuth($username, $password)->post(
        //     $url,
        //     $params
        // );

        // dd($res);

        $ch = curl_init();
        
        $headr[] = 'Content-type: application/json';
        curl_setopt($ch, CURLOPT_URL,"$url");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headr);
        curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
        curl_setopt($ch, CURLOPT_POST, 1);
        if(env('APP_ENV') == "local"){
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }
        
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $server_output = curl_exec($ch);
      
        curl_close($ch);

        return $server_output;
    }

}