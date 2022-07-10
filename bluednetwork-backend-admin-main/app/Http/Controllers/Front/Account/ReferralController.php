<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           ReferralController.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Http\Controllers\Front\Account;

use App\Abstracts\Http\Controllers\FrontController;
use App\Transformers\Users\ReferralTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class ReferralController
 * @package App\Http\Controllers\Front\Account
 */
class ReferralController extends FrontController
{
    /**
     * @OA\Get(
     *      path="/account/referred/list",
     *      operationId="referred",
     *      tags={"Account"},
     *      summary="user referred",
     *      description="return users referred by current loggedin user",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *          name="keyword",
     *          description="search referred user by firstname, lastname or phonnumber",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *       ),
     *     @OA\Response(response=200,description="fetched all user referrals"),
     *     @OA\Response(response=500, description="Bad request"),
     *     )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user('frontend');
        try {


            $referrals = $user->referrals()->latest()->whereHas('referred', function ($query) use ($request) {
                $query->when($request->has('keyword'), function ($q) use ($request) {
                    $q->where('first_name', 'LIKE', '%' . $request->keyword . '%')
                        ->orWhere('last_name', 'LIKE', '%' . $request->keyword . '%')
                        ->orWhere('phone_number', 'LIKE', '%' . $request->keyword . '%');
                });
            })->whereHas('user')->paginate(20);
            return api()->status(200)->data(fractal($referrals, ReferralTransformer::class)->toArray())->respond();
        }catch (\Exception $exception){
            logger()->error('Error fetching referrals : ' . $exception);
            return api()->status(500)->message('Error fetching referrals')->respond();
        }

    }
}
