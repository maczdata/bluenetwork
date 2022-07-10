<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           ResendVerificationEmail.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Listeners\User;

use App\Events\Common\SendToken;
use App\Notifications\Users\ActivationRequestMail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResendVerificationEmail
{
    /**
     * @param SendToken $event
     */
    public function handle(SendToken $event)
    {
        $event->token->tokenable->notify(new ActivationRequestMail($event->token));
    }
}
