<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           UserRepository.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Repositories\Users;

use App\Eloquent\Repository;
use App\Events\Common\EntityCreated;
use App\Models\Common\ConnectedAccount;
use App\Models\Users\User;
use App\Models\Users\UserProfile;
use App\Traits\Common\Fillable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Contracts\User as ProviderUser;
use Spatie\Permission\Models\Role;

/**
 * Class UserRepository
 * @package App\Repositories\Users
 */
class UserRepository extends Repository
{
    use Fillable;
    /**
     * @return string
     */
    public function model()
    {
        return User::class;
    }

    /**
     * @param Request $request
     * @param ProviderUser|null $providerAccount
     * @return mixed
     */
    public function addUser(Request $request, ProviderUser $providerAccount = null): mixed
    {
        return DB::transaction(function () use ($request, $providerAccount) {
            $createNew = false;
            $user = false;
            $refCode = $this->getModel()->generateReferral();
            // there's a possibility a user with same email address is already in the system,
            // so we first check that, if it does, use the user object to add the social provider
            //if (array_key_exists('provider', $request->toArray()) && array_key_exists('provider_id', $request->toArray())) {
            if (!is_null($providerAccount)) {
                $user = $this->getModel()->where("email", $providerAccount->getEmail())->first();
                if (!$user) {
                    $createNew = true;
                }
            } else {
                $createNew = true;
            }
            // if a fresh user, just create it
            if ($createNew) {
                if (!is_null($providerAccount)) {
                    $user = $this->create(
                        array_merge(
                            $request->merge([
                                'ref_code' => $refCode,
                                'email' => $providerAccount->getEmail(),
                               
                            ])->toArray(),
                            $this->getSocialProviderFirstLastName($providerAccount->getName())
                        )
                    );
                    $user->markEmailAsVerified();
                } else {
                    $user = $this->create($request->merge([
                        'ref_code' => $refCode,
                    ])->except('password_confirmation'));
                }
            }
            if ($user) {
                // save social Account
                if (!is_null($providerAccount)) {
                    $connectedAccount = new ConnectedAccount();
                    $connectedAccount->provider_id = $providerAccount->getId();
                    $connectedAccount->provider_name = strtolower($request->provider);
                    $connectedAccount->token = $providerAccount->token;
                    $connectedAccount->secret = $providerAccount->tokenSecret ?? null;
                    $connectedAccount->refresh_token = $providerAccount->refreshToken ?? null;
                    $connectedAccount->expires_at = property_exists($providerAccount, 'expiresIn') ?
                        now()->addSeconds($providerAccount->expiresIn) : null;
                    $savedConnected = $user->connectedAccounts()->save($connectedAccount);
                    $user->switchConnectedAccount($savedConnected);
                }

                if ($request->has('referral_id') && !is_null($request->referral_id)) {
                    $user->referrer()->create($request->toArray());
                }


                // create activation token
                event(new EntityCreated($user, $createNew));
                return $user;
            }
            return false;
        });
    }


    /**
     * @param $user
     * @param $request
     * @return mixed
     */
    public function updateUser($user, $request): mixed
    {
        return DB::transaction(function () use ($user, $request) {
            $data = $request->toArray();
            $user->update($this->filled($data));
            if (is_null($user->profile)) {
                UserProfile::create(['user_id' => $user->id]);
            }
            // a user is not expect to have more that one profile image.
            // so if there's a request for profile image upload,
            //first clear the old once
            if ($request->file('profile_image')) {
                $user->clearMediaCollection('profile_images');
                $user->addMedia($request->file('profile_image'))
                    ->preservingOriginal()->toMediaCollection('profile_images');
            }
            if ($request->meta != null) {
                foreach ($request->meta as $metaKey => $metaValue) {
                    $user->setMeta($metaKey, $metaValue);
                }
            }

            if(isset($request->role))
            {
                $new_role = Role::where('name', $request->role)->first();
                $currentRole = $user?->roles?->first();
                
                $user->removeRole($currentRole);
                
                $user->assignRole($new_role);
            }
           

            return $user;
        });
    }

    /**
     * Returns first and last name from name
     *
     * @param string $name
     * @return array
     */
    public function getSocialProviderFirstLastName(string $name): array
    {
        $name = trim($name);

        $lastName = (strpos($name, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $name);

        $firstName = trim(preg_replace('#' . $lastName . '#', '', $name));

        return [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'username' => $firstName.  mt_rand(1000, 9999)
        ];
    }
}
