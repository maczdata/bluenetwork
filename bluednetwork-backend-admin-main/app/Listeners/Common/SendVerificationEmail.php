<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           SendVerificationEmail.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Listeners\Common;

use App\Events\Common\EntityCreated;
use App\Models\Users\User;
use App\Traits\Common\TokenTrait;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\Users\UserVerificationEmail;

class SendVerificationEmail
{
    use TokenTrait;

    /**
     * @param EntityCreated $event
     */
    public function handle(EntityCreated $event)
    {
        if (($event->entity instanceof User) && !is_null($event->entity->email) && is_null($event->entity->email_verified_at)) {
            $token = $this->createToken($event->entity, 1, 'email');
            $event->entity->notify(new UserVerificationEmail($event->entity, $token));
        }
    }
}
