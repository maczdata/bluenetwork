<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           AuthenticatedController.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Http\Controllers\Front\Auth;

use App\Abstracts\Http\Controllers\FrontController;
use App\Services\Authorization;
use App\Services\Response\Builder;
use App\Transformers\Users\AuthTransformer;
use Illuminate\Support\Facades\Auth;
use App\Transformers\Users\UserTransformer;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class AuthenticatedController extends FrontController
{

    /**
     * @OA\Delete(
     *      path="/account/logout",
     *      operationId="user-logout",
     *      tags={"Authentication"},
     *       security={{"bearerAuth":{}}},
     *      summary="Logs out current logged in user session",
     *       @OA\Response(response=204,description="successfully loggedout")
     *     )
     * Log the user out (Invalidate the token).
     *
     * @param Request $request
     * @return Application|ResponseFactory|Response
     */
    public function logout(Request $request): Response|Application|ResponseFactory
    {
        auth('frontend')->logout();

        return response('', 204);
    }

    /**
     * @OA\Get(
     *      path="/account/me",
     *      operationId="retrieve-user",
     *      tags={"Authentication"},
     *      summary="Retrieve user",
     *       description="get current logged in user",
     *       @OA\Parameter(
     *          name="token",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *           type="string"
     *          )
     *        ),
     *       security={{"bearerAuth":{}}},
     *       @OA\Response(response=200,description="user sucessfully retrieved"),
     *       @OA\Response(response=401, description="invalid_token/token_expired"),
     *       @OA\Response(response=404,description="user_invalid"),
     *       @OA\Response(response=500, description="server error")
     *     )
     *
     * Get the authenticated User.
     */
    public function me()
    {
        set_time_limit(300);
       
        try {
            $user =  Auth::guard('frontend')->user();
            return api()->status(200)->data(fractal($user, UserTransformer::class)->toArray())->respond();
        } catch (AuthenticationException $authex) {
            return api()->status(404)->message('User not found')->respond();
        } catch (TokenExpiredException $e) {
            return api()->status(401)->message('Token expired')->respond();
        } catch (TokenInvalidException $e) {
            return api()->status(401)->message('Token Invalid')->respond();
        } catch (JWTException $e) {
            return api()->status(500)->message('Technical error');
        }
    }

    /**
     * @OA\Get(
     *      path="/account/refresh",
     *      operationId="refresh-user-token",
     *      tags={"Authentication"},
     *      summary="Refresh token ",
     *       @OA\Response(response=200,description="token is refreshed"),
     *       @OA\Response(response=500, description="Bad request")
     *     )
     * Refreshes a jwt (ie. extends it's TTL)
     *
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        set_time_limit(300);
        $authorization = new Authorization('frontend', auth('frontend')->refresh());
        return api()->status(200)->data(
            fractal(
                auth('frontend')->user(),
                new AuthTransformer($authorization)
            )->toArray()
        )->respond();
    }
}
