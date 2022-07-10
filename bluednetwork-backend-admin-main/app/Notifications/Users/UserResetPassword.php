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

namespace App\Notifications\Users;

use App\Notifications\BaseNotification;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Class UserResetPassword
 * @package App\Notifications\User
 */
class UserResetPassword extends BaseNotification
{
    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;


    /**
     * Create a notification instance.
     *
     * @param string $token
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's channels.
     *
     * @param $notifiable
     * @return string[]
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        $resetMinutes = config('auth.passwords.user.expire');
        /* $url = url(route('portal.password-reset.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
         ], false));*/
        $url = config('bds.frontend_urls.reset_password') . '?token='.$this->token .'&email='.$notifiable->getEmailForPasswordReset();
        return (new MailMessage)
            ->subject('Password Reset Request')
            ->view('mails.user.password-reset-mail', compact('url', 'resetMinutes'));
    }
}
