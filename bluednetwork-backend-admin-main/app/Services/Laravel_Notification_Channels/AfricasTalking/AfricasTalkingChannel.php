<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           AfricasTalkingChannel.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 2:40 PM
 */

namespace App\Services\Laravel_Notification_Channels\AfricasTalking;

use AfricasTalking\SDK\AfricasTalking as AfricasTalkingSDK;
use Exception;
use Illuminate\Notifications\Notification;
use App\Exceptions\CouldNotSendNotification;
use App\Notifications\BaseNotification;

/**
 * Class AfricasTalkingChannel
 * @package App\Services\Laravel_Notification_Channels\AfricasTalking
 */
class AfricasTalkingChannel
{
    /** @var AfricasTalkingSDK */
    protected AfricasTalkingSDK $africasTalking;

    /** @param AfricasTalkingSDK $africasTalking */
    public function __construct(AfricasTalkingSDK $africasTalking)
    {
        $this->africasTalking = $africasTalking;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param Notification $notification
     * @throws CouldNotSendNotification
     */
    public function send($notifiable, BaseNotification $notification)
    {
        $message = $notification->toAfricasTalking($notifiable);

        if (!$phoneNumber = $notifiable->routeNotificationFor('africasTalking')) {
            $phoneNumber = $notifiable->phone_number;
        }

        try {
            $this->africasTalking->sms()->send([
                'to' => $phoneNumber,
                'message' => $message->getContent(),
                'from' => "BLUE D",
            ]);
        } catch (Exception $e) {
            throw CouldNotSendNotification::serviceRespondedWithAnError($e->getMessage());
        }
    }
}
