<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           VerifyUser.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Listeners\User;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\Common\VerifyToken;
use App\Models\Users\User;
use App\Notifications\Users\UserApproved;

class VerifyUser
{
    /**
     * @param VerifyToken $event
     */
    public function handle(VerifyToken $event)
    {
        if ($event->tokenable instanceof User) {
            $event->tokenable->tokenable()->notify(new UserApproved($event->tokenable));
        }
    }
}
