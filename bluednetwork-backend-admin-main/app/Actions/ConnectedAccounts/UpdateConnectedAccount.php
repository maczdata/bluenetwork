<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           UpdateConnectedAccount.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Actions\ConnectedAccounts;

use App\Models\Common\ConnectedAccount;
use Laravel\Socialite\Contracts\User;

class UpdateConnectedAccount
{
    /**
     * Update a given connected account.
     *
     * @param mixed $user
     * @param ConnectedAccount $connectedAccount
     * @param string $provider
     * @param User $providerUser
     * @return ConnectedAccount
     */
    public function update($user, ConnectedAccount $connectedAccount, string $provider, User $providerUser)
    {
        $connectedAccount->provider_id =  $providerUser->getId();
        $connectedAccount->provider = strtolower($provider);
        $connectedAccount->token = $providerUser->token;
        $connectedAccount->secret = $providerUser->tokenSecret ?? null;
        $connectedAccount->refresh_token = $providerUser->refreshToken ?? null;
        $connectedAccount->expires_at = property_exists($providerUser, 'expiresIn') ? now()->addSeconds($providerUser->expiresIn) : null;

        $user->connectedAccounts()->save($connectedAccount);
        return $connectedAccount;
    }
}
