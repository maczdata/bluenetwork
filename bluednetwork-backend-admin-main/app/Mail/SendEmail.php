<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmail extends Mailable
{
    use Queueable, SerializesModels;

  
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $otp;
    public $user;

    public function __construct($otp, $user)
    {
        $this->otp = $otp;
        $this->user = $user;
       
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $otp = $this->otp;
        $user = $this->user;
        $from = env('MAIL_FROM_ADDRESS');

        return $this->from("$from")
         ->subject('Otp-access')
         ->view('mails.user.bank-otp-email', compact('otp', 'user'));
    }
}
