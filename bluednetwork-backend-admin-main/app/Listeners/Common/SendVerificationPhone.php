<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           SendVerificationPhone.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Listeners\Common;

use App\Events\Common\EntityCreated;
use App\Models\Users\User;
use App\Notifications\Users\UserVerificationPhone;
use App\Traits\Common\TokenTrait;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendVerificationPhone
{
    use TokenTrait;

    /**
     * @param EntityCreated $event
     */
    public function handle(EntityCreated $event)
    {
        $rndno = rand(1000, 9999);

        if ($event->entity instanceof User &&
           !is_null($event->entity->intl_phone_number) &&
           !is_null($event->entity->hasVerifiedPhone())) {
            $token = $this->createToken($event->entity, 1, 'phone', $rndno);
            $event->entity->notify(new UserVerificationPhone($event->entity, $token));
        }
    }
}
