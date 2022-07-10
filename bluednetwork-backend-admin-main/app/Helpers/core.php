<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           core.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     16/08/2021, 11:08 AM
 */


use App\Services\Core;
use App\Services\Response\Builder;

if (!function_exists('core')) {
    function core()
    {
        return app()->make(Core::class);
    }
}

if (!function_exists('getServiceConfig')) {
    /**
     * @param $service
     * @return mixed
     */
    function getServiceConfig($service): mixed
    {
        if (is_null($service)) {
            return false;
        }
        if (!is_null($service->parent)) {
            return config('bds.bds_service_types.' . $service->parent->service_type->slug . '.services.' . $service->parent->key);
        }
        if (config()->has('bds.bds_service_types.' . $service->service_type->slug . '.services.' . $service->key)) {
            return config('bds.bds_service_types.' . $service->service_type->slug . '.services.' . $service->key);
        }
        return config('bds.bds_service_types.' . $service->service_type->slug);
    }
}

if (!function_exists('getServiceClass')) {
    /**
     * @param $service
     * @return mixed
     */
    function getServiceClass($service): mixed
    {
        if (is_null($service)) {
            return false;
        }
        if (!is_null($service->parent) && config()->has('bds.bds_service_types.' . $service->parent->service_type->slug . '.services.' . $service->parent->key . '.class')) {
            return config('bds.bds_service_types.' . $service->parent->service_type->slug . '.services.' . $service->parent->key . '.class');
        }

        if (config()->has('bds.bds_service_types.' . $service->service_type->slug . '.services.' . $service->key . '.class')) {
            return config('bds.bds_service_types.' . $service->service_type->slug . '.services.' . $service->key . '.class');
        }
        return config('bds.bds_service_types.' . $service->service_type->slug . '.class');
    }
}

if (!function_exists('api')) {
    /**
     * Start creating a idempotent api response
     *
     * @return Builder
     */
    function api(): Builder
    {
        return new Builder();
    }
}

/**
 * returns percentage difference
 *
 * @param $previous
 * @param $current
 * @return float|int
 */
if (!function_exists('getPercentageChange')) {
    function getPercentageChange($previous, $current): float|int
    {
        if (!$previous) {
            return $current ? 100 : 0;
        }

        return ($current - $previous) / $previous * 100;
    }
}

if (!function_exists('getPercentOfNumber')) {
    /**
     * calculates the percentage of a given number.
     *
     * @param int $number The number you want a percentage of.
     * @param int $percent The percentage that you want to calculate.
     * @return float|int The final result.
     */
    function getPercentOfNumber(int $number, int $percent): float|int
    {
        return ($percent / 100) * $number;
    }
}

if (!function_exists('generateOtp')) {
    /**
     * @param $len
     * @return string
     */
    function generateOtp($len): string
    {
        $result = '';
        for ($i = 0; $i < $len; $i++) {
            $result .= mt_rand(0, 9);
        }
        return $result;
    }
}

/*
* Get or Set the Settings Values
*
* @var [type]
*/
if (!function_exists('setting')) {
    function setting($key = null, $default = null)
    {
        if (is_null($key)) {
            return new App\Models\Common\Setting();
        }

        if (is_array($key)) {
            return App\Models\Common\Setting::set($key[0], $key[1]);
        }

        $value = App\Models\Common\Setting::get($key);

        return is_null($value) ? value($default) : $value;
    }
}
