<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           EventServiceProvider.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Providers;

use App\Events\Common\EntityCreated;
use App\Events\Common\SendToken;
use App\Listeners\Common\AttachWallet;
use App\Listeners\Common\ResendVerificationEmail;
use App\Listeners\Common\SendVerificationEmail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        EntityCreated::class => [
            SendVerificationEmail::class,
            AttachWallet::class
        ],
        Registered::class => [
            //SendEmailVerificationNotification::class,
        ],
        SendToken::class => [
            ResendVerificationEmail::class
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
