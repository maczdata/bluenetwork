<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           SocialAuthController.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 2:40 PM
 */

namespace App\Http\Controllers\Front\Auth;

use App\Abstracts\Http\Controllers\FrontController;
use App\Repositories\Users\UserRepository;
use App\Services\Authorization;
use App\Services\Response\Builder;
use App\Transformers\Users\AuthTransformer;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
use Laravel\Socialite\Two\User;
use App\Models\Common\ConnectedAccount;
use Throwable;

class SocialAuthController extends FrontController
{
    /**
     * SocialAuthController constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(protected UserRepository $userRepository)
    {
        parent::__construct();
    }

    /**
     * @OA\Post(
     *      path="/auth/{provider}/social-login",
     *      operationId="user-social-login",
     *      tags={"Authentication"},
     *      summary="login via socia platforms",
     *      description="login",
     *       @OA\Parameter(
     *          name="provider",
     *          description="social provider",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *       ),
     *       @OA\Response(response=200,description="linking successfull"),
     *       @OA\Response(response=422, description="daa/form validation request"),
     *       @OA\Response(response=500, description="bad request")
     *     )
     *
     * Handle Social login request
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function socialLogin(Request $request): JsonResponse
    {
        return response()->json([
            'url' => Socialite::driver($request->provider)->stateless()->redirect()->getTargetUrl(),
        ]);
    }

    /**
     * @OA\Get(
     *      path="/auth/social-login/callback",
     *      operationId="user-social-login-callback",
     *      tags={"Authentication"},
     *      summary="login via social callback",
     *      description="login",
     *       @OA\Parameter(
     *          name="provider",
     *          description="social provider",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *       ),
     *       @OA\Parameter(
     *          name="code",
     *          description="code",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *       ),
     *       @OA\Response(response=200,description="linking successfull"),
     *       @OA\Response(response=422, description="data/form validation request"),
     *       @OA\Response(response=500, description="bad request")
     *     )
     *
     * Handle Social login request
     *
     * @param Request $request
     * @return Builder
     */
    public function callBack(Request $request): Builder
    {
        $request->validate([
            'code' => 'required',
            'provider' => 'required',
        ]);
        $socialable = null;
        $oauthToken = $request->code;
        $provider = $request->provider;
        $jwtToken = NULL;
        if (in_array($provider, ['facebook'])) {
            //$oauthResponse = Socialite::driver($provider)->stateless()->getAccessTokenResponse($oauthToken);
            //$oauthToken = Arr::get($oauthResponse, 'access_token');
        }
        
        try {
            $providerUser = Socialite::driver($provider)->stateless()->userFromToken($oauthToken);
            if ($providerUser instanceof User) {
                $linkedSocialAccount = ConnectedAccount::where('provider_name', $provider)
                    ->where('provider_id', $providerUser->getId())
                    ->first();
                if (!is_null($linkedSocialAccount)) {
                    $socialable = $linkedSocialAccount->connectable;
                
                    if(is_null($socialable)){
                        $createdUserNew = $this->userRepository->addUser($request, $providerUser);
                        $createdUserNew->assignRole('user');
                        $socialable = $createdUserNew;
                    }

                    if(is_null($socialable->roles->first()))
                    {
                        $socialUser = $this->userRepository->where('id', $socialable->id)->first();
                        $socialUser->assignRole('user');
                    }
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

                    $jwtToken = auth('frontend')->login($socialable);
                    $authorization = new Authorization('frontend', $jwtToken);
                    return api()->status(200)->data(fractal($socialable, new AuthTransformer($authorization))->toArray());

                }
                return api()->status(403)->message('Social account could not be linked');
            }
        } catch (InvalidStateException $exception) {
            logger()->error("user social login exception error : " . $exception);
            return api()->status(500)->message('There was an issue from ' . $provider . ', please contact support');
        } catch (Exception $ex) {
            logger()->error("user social login exception error : " . $ex);
            return api()->status(500)->message('There was an issue linking account, please contact support');
        }
    }

     /**
     * @OA\Get(
     *      path="/auth/social-login/callback/react",
     *      operationId="user-social-login-callback-react",
     *      tags={"Authentication"},
     *      summary="login via social callback via react",
     *      description="login with social call back",
     *       @OA\Parameter(
     *          name="provider",
     *          description="social provider",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *       ),
     *       @OA\Parameter(
     *          name="code",
     *          description="code",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *       ),
     *       @OA\Response(response=200,description="linking successfull"),
     *       @OA\Response(response=422, description="data/form validation request"),
     *       @OA\Response(response=500, description="bad request")
     *     )
     *
     * Handle Social login request
     *
     * @param Request $request
     * @return Builder
     */
    public function callBackReact(Request $request): Builder
    {
        $request->validate([
            'code' => 'required',
            'provider' => 'required',
        ]);
        $socialable = null;
        $oauthToken = $request->code;
        $provider = $request->provider;
        $jwtToken = NULL;
        $secret = config('services.google.client_secret') ;
        if (in_array($provider, ['facebook'])) {
            //$oauthResponse = Socialite::driver($provider)->stateless()->getAccessTokenResponse($oauthToken);
            //$oauthToken = Arr::get($oauthResponse, 'access_token');
        }
        
        try {
            $access_token = Socialite::driver($provider)->getAccessTokenResponse($oauthToken);
            $providerUser = Socialite::driver($provider)->stateless()->userFromToken($access_token['access_token']);
            if ($providerUser instanceof User) {
                $linkedSocialAccount = ConnectedAccount::where('provider_name', $provider)
                    ->where('provider_id', $providerUser->getId())
                    ->first();
                if (!is_null($linkedSocialAccount)) {
                    $socialable = $linkedSocialAccount->connectable;
                
                    if(is_null($socialable)){
                        $createdUserNew = $this->userRepository->addUser($request, $providerUser);
                        $createdUserNew->assignRole('user');
                        $socialable = $createdUserNew;
                    }

                    if(is_null($socialable->roles->first()))
                    {
                        $socialUser = $this->userRepository->where('id', $socialable->id)->first();
                        $socialUser->assignRole('user');
                    }
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

                    $jwtToken = auth('frontend')->login($socialable);
                    $authorization = new Authorization('frontend', $jwtToken);
                    return api()->status(200)->data(fractal($socialable, new AuthTransformer($authorization))->toArray());

                }
                return api()->status(403)->message('Social account could not be linked');
            }
        } catch (InvalidStateException $exception) {
            logger()->error("user social login exception error : " . $exception);
            return api()->status(500)->message('There was an issue from ' . $provider . ', please contact support');
        } catch (Exception $ex) {
            logger()->error("user social login exception error : " . $ex);
            return api()->status(500)->message('There was an issue linking account, please contact support');
        }
    }
}
