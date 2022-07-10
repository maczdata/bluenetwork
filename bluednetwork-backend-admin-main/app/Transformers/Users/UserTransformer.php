<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           UserTransformer.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Transformers\Users;

use App\Models\Users\User;
use App\Transformers\Common\VirtualTransformer;
use League\Fractal\TransformerAbstract;

/**
 * Class UserTransformer
 *
 * @package App\Transformers\Users
 */
class UserTransformer extends TransformerAbstract
{
    protected array $availableIncludes = [];

    /**
     * Turn this item object into a generic array
     *
     * @param User $user
     * @return array
     */
    public function transform(User $user)
    {
     
        $virtualTransform = new VirtualTransformer();
        $bankingDetail = $user->withdrawal_setups()->enabled()->first();
        //$bankingData['banking_data']['bank_id'] = $bankingDetail->bank->id;
        //$bankingData['banking_data']['account_number'] = $bankingDetail->account_number;

        if($user?->profile?->proof_of_identity_number != null && $user?->profile?->identity_verified_at !=null )
        {
            $identity = 'verified';
        }elseif( $user?->profile?->proof_of_identity_number !=  null && $user?->profile?->identity_verified_at == null )
        {
            $identity = 'pending';
        }else{
            $identity = 'unverified';
        }

        if($user?->profile?->address !=  null && $user?->profile?->proof_of_address_verified_at !=null )
        {
            $address_status = 'verified';
        }elseif( $user?->profile?->address !=  null && $user?->profile?->proof_of_address_verified_at ==null )
        {
            $address_status = 'pending';
        }else{
            $address_status = 'unverified';
        }

        //sanitize profile_image

        $check = $user->getMedia('profile_images')->first()->original_url ?? null;
        $profile_image = $check;
        // if($profile_image != null)
        // {
        //     $profile_image =  str_replace('\\','/', $profile_image); 
        //     $profile_image =  str_replace('//','/', $profile_image); 

        //     if(env('CONTROL_DOMAIN') == "staging-api.bluednetwork.com")
        //     {
        //         $profile_image = str_replace('https:/bluednetwork.com','https://staging-api.bluednetwork.com', $profile_image); 

        //     }elseif(env('CONTROL_DOMAIN') == "control.bluednetwork.com")
        //     {
                
        //         $profile_image = str_replace('https:/bluednetwork.com','https://control.bluednetwork.com', $profile_image);
        //     }

        // }
       

        return [
            'id' => $user->id,
            'ref_code' => $user->ref_code,
            //'id' => $user->hashid(),
            //'full_name' => $user->full_name,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'phone_number' => $user->phone_number,
            'intl_phone_number' => $user->intl_phone_number,
            'profile_image' => $profile_image,
            'email_verified_at' => $user->email_verified_at,
            'phone_verified_at' => $user->phone_verified_at,
            'wallet_balance' => $user->wallet_balance ?? 0,
            'formatted_wallet_balance' => core()->formatBasePrice($user->wallet_balance),
            'referred_count' => $user->referrals->count(),
            'banking_data' => [
                'bank_name' => $bankingDetail->bank->name ?? '',
                'account_number' => $bankingDetail->account_number ?? '',
                'account_name'  => $bankingDetail->account_name ?? '',
            ],
            'role' => $user->roles->pluck('name')->first(),
            'email_verified' => $user->email_verified_at ? "verified" : "unverified",
            'phone_verified' => $user->phone_verified_at ? "verified" : "unverified",
            'bvn_verified' => $user?->profile?->bvn_verified_at ? "verified" : "unverified",
            'proof_of_address_verified' => $address_status,
            'identity_verified' => $identity,
            'withdrawal_pin_set' => $user?->profile?->withdrawal_pin ? "verified" : "unverified",
            'virtual_account' => $user?->virtual?->order_ref ? $virtualTransform->transform($user->virtual)  : null
        ];
    }
}
