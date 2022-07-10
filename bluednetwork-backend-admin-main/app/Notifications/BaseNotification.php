<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           UserResetPassword.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Notifications;

use App\Services\Laravel_Notification_Channels\AfricasTalking\AfricasTalkingMessage;
use Illuminate\Notifications\Notification;

/**
 * @package App\Notifications\User
 */
class BaseNotification extends Notification
{
    public function toAfricasTalking($notifiable): AfricasTalkingMessage
    {
        return (new AfricasTalkingMessage())
            ->content("New mobile message");
    }
}
