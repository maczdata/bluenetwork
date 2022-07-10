<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           UserVerificationEmail.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Notifications\Users;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;
use App\Models\Common\Token;
use App\Models\Users\User;
use App\Notifications\BaseNotification;

class UserVerificationEmail extends BaseNotification
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
     * Get the notification's channels.
     *
     * @param mixed $notifiable
     * @return array|string
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
    public function toMail($notifiable): MailMessage
    {
        $token = $this->token;
        $user = $this->user;
        $link = config('bds.frontend_urls.verify_email') . '?token='.$this->token->token . '&verification_source=email&email=' . $user->email;

        return (new MailMessage)
         ->subject('Verification Mail')
         ->view('mails.user.verification-email', compact('token', 'user', 'link'));
    }
}
