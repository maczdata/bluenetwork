<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           UserVerificationPhone.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Notifications\Users;

use App\Services\Laravel_Notification_Channels\AfricasTalking\AfricasTalkingChannel;
use App\Services\Laravel_Notification_Channels\AfricasTalking\AfricasTalkingMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;
use App\Models\Common\Token;
use App\Models\Users\User;
use App\Notifications\BaseNotification;

/**
 * Class UserVerificationPhone
 * @package App\Notifications\Users
 */
class UserVerificationPhone extends BaseNotification
{
    use Queueable, SerializesModels;

    /**
     * Create a notification instance.
     *
     * @param User $user
     * @param Token $token
     */
    public function __construct(public User $user, public Token $token)
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [AfricasTalkingChannel::class];
    }

    /**
     * @param $notifiable
     * @return AfricasTalkingMessage
     */
    public function toAfricasTalking($notifiable): AfricasTalkingMessage
    {
        return (new AfricasTalkingMessage())
            ->content('Your ' . config('app.name') . ' phone number verification code is ' . $this->token?->token);
    }
}
