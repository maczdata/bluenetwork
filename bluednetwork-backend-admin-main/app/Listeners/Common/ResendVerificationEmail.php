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

namespace App\Listeners\Common;

use App\Events\Common\SendToken;
use App\Models\Users\User;
use App\Notifications\Users\UserVerificationEmail;
use App\Notifications\Users\UserVerificationPhone;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResendVerificationEmail
{
    /**
     * @param SendToken $event
     */
    public function handle(SendToken $event)
    {
        if ($event->token->tokenable instanceof User) {
            if ($event->token->source == 'email') {
                $event->token->tokenable->notify(new UserVerificationEmail($event->token->tokenable, $event->token));
            }
            if ($event->token->source == 'phone_number') {
                $event->token->tokenable->notify(new UserVerificationPhone($event->token->tokenable, $event->token));
            }
        }
    }
}
