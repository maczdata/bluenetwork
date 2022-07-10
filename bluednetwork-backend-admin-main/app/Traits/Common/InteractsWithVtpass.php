<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           InteractsWithVtpass.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Traits\Common;

use Illuminate\Support\Facades\Http;

trait InteractsWithVtpass
{
    /**
     * @param $endpoint
     * @param $params
     * @param string $method
     * @return mixed
     */
    public function request($endpoint, $params, string $method = 'get'): mixed
    {
        $username = config('bds.vtpass.username');
        $password = config('bds.vtpass.password');
        $mode = config('bds.vtpass.mode');

        $liveUrl = "https://vtpass.com/api/";
        $sandboxUrl = "https://sandbox.vtpass.com/api/";

        $url = $mode === 'live' ? $liveUrl : $sandboxUrl;
        $res = Http::withoutVerifying()->withOptions(["verify"=>false])->withBasicAuth($username, $password)->$method(
            $url . $endpoint,
            $this->merge([], $params)
        );

        if ($res->failed()) {
            return $res->throw();
        }
        return $res;
    }
    /**
     * @param $endpoint
     * @param $params
     * @return mixed
     */
    public function post($endpoint, $params): mixed
    {
        return $this->request($endpoint, $params, 'post');
    }

    /**
     * @param $endpoint
     * @param $params
     * @return mixed
     */
    public function get($endpoint, $params): mixed
    {
        return $this->request($endpoint, $params);
    }

    /**
     * @param $ar
     * @param $arr
     * @return array
     */
    private function merge($ar, $arr): array
    {
        return array_merge($ar, $arr);
    }

    /**
     * @param $params
     * @return mixed
     */
    public function status($params): mixed
    {
        return $this->post("requery", $params)->json();
    }

    /**
     * @param $params
     * @return mixed
     */
    public function purchase($params): mixed
    {
        return $this->post("pay", $params)->json();
    }

    /**
     * @param $params
     * @return mixed
     */
    public function verify($params): mixed
    {
        return $this->post("merchant-verify", $params)->json();
    }

    /**
     * @param $params
     * @return mixed
     */
    public function variations($params): mixed
    {
        return $this->get("service-variations", $params)->json();
    }
}
