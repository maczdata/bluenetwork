<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           AuthTransformer.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 2:40 PM
 */

namespace App\Transformers\Users;

use App\Models\Users\User;
use App\Transformers\Common\VirtualTransformer;
use League\Fractal\TransformerAbstract;

/**
 * Class AuthTransformer
 * @package App\Transformers\Users
 */
class AuthTransformer extends TransformerAbstract
{

    /**
     * AuthTransformer constructor.
     * @param $authorization
     */
    public function __construct(protected $authorization)
    {
    }

    /**
     * @param User $user
     * @return array
     */
    public function transform(User $user)
    {

        if($user?->profile?->proof_of_identity_number !=  null && $user?->profile?->identity_verified_at !=null )
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
        }elseif( $user?->profile?->address !=  null && $user?->profile?->proof_of_address_verified_at !=null )
        {
            $address_status = 'pending';
        }else{
            $address_status = 'unverified';
        }


        $virtualTransform = new VirtualTransformer();
        return [
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'phone_number' => $user->phone_number,
            'intl_phone_number' => $user->intl_phone_number,
            'email_verified_at' => $user->email_verified_at,
            'phone_verified_at' => $user->phone_verified_at,
            'wallet_balance' => $user->wallet_balance ?? 0,
            'formatted_wallet_balance' => core()->formatBasePrice($user->wallet_balance),
            'referred_count' => $user->referrals->count(),
            'authorization' => $this->authorization->toArray(),
            'role' => $user->roles->pluck('name')->first(),
            'email_verified' => $user->email_verified_at ? "verified" : "unverified",
            'phone_verified' => $user->phone_verified_at ? "verified" : "unverified",
            'bvn_verified' => $user?->profile?->bvn_verified_at ? "verified" : "unverified",
            'proof_of_address_verified' => $address_status,
            'identity_verified' => $identity,
            'withdrawal_pin_set' => $user?->profile?->withdrawal_pin ? "verified" : "unverified",
            'virtual_account' => $user?->virtual?->order_ref ? $virtualTransform->transform($user->virtual) : null
        ];
    }
}
