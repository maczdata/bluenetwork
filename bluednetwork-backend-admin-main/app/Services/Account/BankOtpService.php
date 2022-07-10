<?php

namespace App\Services\Account;

use App\Models\BankUpdateToken;
use App\Mail\SendEmail;
use Illuminate\Support\Facades\Mail;


class BankOtpService
{

    public function generateOtp($user)
    {
        $otp = generateOtp(5);
        $check = BankUpdateToken::where('user_id',  $user->id)->first();
        if(!is_null($check))
        {
            $check->delete();
        }

        BankUpdateToken::create([
                'user_id' => $user->id,
                'token' => $otp,
        ]);

        return $otp;
    
    }

    public function sendOtp($user, $otp)
    {
        return Mail::to($user->email)->send(new SendEmail($otp, $user));
    }

    public function verifyOtp($user, $otp)
    {
        $check = BankUpdateToken::where([
                    'user_id'=> $user->id,
                    'token' => $otp
                ])->first();

        if(!is_null($check))
        {
             return true;
        }
            return false;
    }

    public function expired($user, $otp)
    {
        $check = BankUpdateToken::where([
                    'user_id'=> $user->id,
                    'token' => $otp
                ])->first();

       return $check->tokenExpired();
    }
}