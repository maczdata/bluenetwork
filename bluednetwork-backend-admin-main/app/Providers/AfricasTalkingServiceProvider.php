<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           AfricasTalkingServiceProvider.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Providers;

use AfricasTalking\SDK\AfricasTalking as AfricasTalkingSDK;
use App\Services\Laravel_Notification_Channels\AfricasTalking\AfricasTalkingChannel;
use Illuminate\Support\ServiceProvider;
use App\Exceptions\InvalidConfiguration;

class AfricasTalkingServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /**
         * Bootstrap the application services.
         */
        $this->app->when(AfricasTalkingChannel::class)
            ->needs(AfricasTalkingSDK::class)
            ->give(function () {
                $userName = config('services.africastalking.username');
                $key = config('services.africastalking.key');
                if (is_null($userName) || is_null($key)) {
                    throw InvalidConfiguration::configurationNotSet();
                }
                return new AfricasTalkingSDK(
                    $userName,
                    $key
                );
            });
    }
}
