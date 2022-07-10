<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           SocialAuthController.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     20/08/2021, 7:23 PM
 */

namespace App\Http\Controllers\Control\Auth;

use App\Abstracts\Http\Controllers\FrontController;
use App\Repositories\Users\UserRepository;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Common\ConnectedAccount;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
use Laravel\Socialite\Two\User;

/**
 * Class SocialAuthController
 * @package App\Http\Controllers\Front\Portal\Auth
 */
class SocialAuthController extends FrontController
{
    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;

    /**
     * SocialAuthController constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        parent::__construct();

        $this->userRepository = $userRepository;
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function socialLogin(Request $request): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        return Socialite::driver($request->provider)->redirect();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function callBack(Request $request): RedirectResponse
    {
        $provider = $request->provider;
        try {
            $providerUser = Socialite::driver($provider)->user();
            //if ($providerUser instanceof User) {
            $linkedSocialAccount = ConnectedAccount::where('provider_name', $provider)
                ->where('provider_id', $providerUser->getId())
                ->first();
            if (!is_null($linkedSocialAccount)) {
                $socialable = $linkedSocialAccount->connectable;
            } else {
                $linkedSocialAccount = $this->userRepository->addUser($request, $providerUser);
                if ($linkedSocialAccount) {
                    $linkedSocialAccount->assignRole('user');
                }
                $socialable = $linkedSocialAccount;
            }

            if (!is_null($linkedSocialAccount) && !is_null($socialable)) {
                if (!is_null($linkedAvatar = $providerUser->getAvatar()) && !empty($linkedAvatar) && is_null($socialable->talent_avatar)) {
                    $socialable->addMediaFromUrl($linkedAvatar)->toMediaCollection('profile_images');
                }
                auth('frontend')->login($socialable);

                return redirect()->route('portal.account.dashboard');
            }
            flash('Social account could not be linked')->error();
            return redirect()->route('portal.login.form');
            //}
        } catch (InvalidStateException $exception) {
            logger()->error("user social login exception error : " . $exception);
            flash('There was an issue from ' . $provider . ', please contact support')->error();
            return redirect()->route('portal.login.form');
        } catch (Exception $ex) {
            logger()->error("user social login exception error : " . $ex);
            flash('There was an issue linking account, please contact support')->error();
            return redirect()->route('portal.login.form');
        }
    }
}
