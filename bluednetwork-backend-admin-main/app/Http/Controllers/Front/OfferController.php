<?php

namespace App\Http\Controllers\Front;

use App\Abstracts\Http\Controllers\FrontController;
use App\Models\Users\User;
use App\Repositories\Common\WalletRepository;
use App\Services\Offers\OffersService;
use App\Transformers\Common\OfferTransformer;
use App\Transformers\Common\UserOfferTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OfferController extends FrontController
{
    public function __construct(protected WalletRepository $walletRepository)
    {
        parent::__construct();
    }

    /**
     * @OA\Get(
     ** path="/offers/list",
     *   tags={"Offers"},
     *   summary="get all offers",
     *   operationId="get all offers",
     *
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="Not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
    public function list(Request $request)
    {
        try {
            $offerService = new OffersService();
            $offers = $offerService->list();

            return api()->data(fractal($offers, OfferTransformer::class)->toArray())->respond();
        } catch (\Exception $ex) {
            logger()->error('Error fetching offers : ' . $ex);
            return api()->status(500)->message('Error fetching offers')->respond();
        }
    }


    /**
     * @OA\Post(
     ** path="/offers/pay",
     *   tags={"Offers"},
     *   summary="pay for an offer",
     *   operationId="pay-for-an-offer",
     *
     * @OA\Parameter(
     *      name="user_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *
     *  @OA\Parameter(
     *      name="offer_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="Not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
    public function PayForOffer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => "required|unique:users,id",
            'offer_id' => "required|unique:offers,id",
        ]);

        DB::beginTransaction();
        try {
            $offerService = new OffersService();
            $offer = $offerService->getOfferById($request->offer_id);

            $user = User::where(['id' => $request->user_id])->first();

            if ($offer->price > $user->profile->accountLevel->transaction_limit) {
                $message = 'Sorry, your account is currently limited to transactions equal or less than '
                    . core()->formatBasePrice($user->profile->accountLevel->transaction_limit);
                return api()->status(400)->message($message)->respond();
            }


            $userOffer = $offerService->createUserOffer($user, $offer);

            $transaction = $user->transactions()
                ->create([
                    'transactionable_type' => 'Offer',
                    'transactionable_id' => $userOffer->id,
                    'amount' => $offer->price,
                    'type' => 2,
                    'status' => 1,
                ]);

            $this->walletRepository->debit($user, $offer->price);

            DB::commit();

            $transformer = new UserOfferTransformer;
            return api()->status(200)->data($transformer->transformUserOffer($userOffer))->respond();
        } catch (\Exception $ex) {
            DB::rollback();
            logger()->error('Error Paying for offers : ' . $ex);
            return api()->status(500)->message('Error Paying for offers ' . $ex->getMessage())->respond();
        }
    }

    /**
     * @OA\GET(
     ** path="/offers/users/list",
     *   tags={"Offers"},
     *   summary="get users offer list",
     *   operationId="get-users-offer-list",
     *
     * @OA\Parameter(
     *      name="user_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="Not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
    public function getUserOffers(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => "required|unique:users,id",
        ]);

        $offerService = new OffersService();
        $offers = $offerService->getUserOfferByUserId($request->user_id);
        $user = User::where(['id' => $request->user_id])->first();
        
        if (!$user->user_offers->count()) {
            $message = 'Sorry but there is no offer transaction yet.';

            return api()->status(400)->message($message)->respond();
        }
        $transformer = new UserOfferTransformer;
        return api()->status(200)->message('User offers retrieved successfully')->data($transformer->collect_user_offers($offers)->toArray())->respond();
    }

    /**
     * @OA\Post(
     ** path="/offers/users/fill/fields",
     *   tags={"Offers"},
     *   summary="users fill offer fields",
     *   operationId="users-fill-offer-fields",
     *
     *  @OA\Parameter(
     *      name="user_offer_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *
     *  @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *     mediaType="multipart/form-data",
     *     @OA\Schema(
     *       @OA\Property(
     *         property="custom_fields",
     *         description="array of objects of the form",
     *         type="array",
     *          @OA\Items(
     *                type="key",
     *                format="string",
     *           )
     *        ),
     *      ),
     *    ),
     *  ),
     *
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="Not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
    public function fillUsersOfferField(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_offer_id' => "required|exist:userOffer,id",
        ]);

        DB::beginTransaction();
        try {
            $offerService = new OffersService();

            $afilledField = $offerService->getSingleField($request->user_offer_id);

            if ($afilledField != null) {
                $message = 'Sorry but this offer\'s form has already been filled.';

                return api()->status(400)->message($message)->respond();
            }
            
            // $request->custom_fields = [
            //         'gender' =>'male',
            //         'Profile' => 'lele'
            // ]; 

            if (isset($request->custom_fields)) {
                $offerService->saveofferForm($request->user_offer_id, $request->custom_fields);
            } else {
                $message = 'Sorry but inaccurate form data sent';

                return api()->status(400)->message($message)->respond();
            }
            DB::commit();
            $afilledField = $offerService->getSingleField($request->user_offer_id);

            $transformer = new UserOfferTransformer;
            return api()->status(200)->message('User offers field saved successfully')->data($transformer->transformUserOffer($afilledField->useroffer))->respond();
        } catch (\Exception $ex) {
            DB::rollback();
            logger()->error('Error filling offers form : ' . $ex);
            return api()->status(500)->message('Error filling offers form: ' . $ex->getMessage())->respond();
        }
    }

    /**
     * @OA\Post(
     ** path="/offers/users/update/fields",
     *   tags={"Offers"},
     *   summary="users update offer fields",
     *   operationId="users-update-offer-fields",
     *
     *  @OA\Parameter(
     *      name="user_offer_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *
     *  @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *     mediaType="multipart/form-data",
     *     @OA\Schema(
     *       @OA\Property(
     *         property="custom_fields",
     *         description="array of objects of the form",
     *         type="array",
     *          @OA\Items(
     *                type="key",
     *                format="string",
     *           )
     *        ),
     *      ),
     *    ),
     *  ),
     *
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="Not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
    public function UpdateUsersOfferField(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_offer_id' => "required|exist:userOffer,id",
        ]);

        DB::beginTransaction();
        try {
            $offerService = new OffersService();

            $afilledField = $offerService->getUserOfferById($request->user_offer_id);
// dd($afilledField);
            // $request->custom_fields = [
            //         'gender' =>'male',
            //         'Profile' => 'lele'
            // ]; 

            if ($afilledField->status === "processing") {
                $message = 'Sorry but this offer has already started the processing phase, editing of the fiells are not eallowed.';

                return api()->status(400)->message($message)->respond();
            }

            if (isset($request->custom_fields)) {
                $offerService->updateOfferForm($request->user_offer_id, $request->custom_fields);
            } else {
                $message = 'Sorry but inaccurate form data sent';

                return api()->status(400)->message($message)->respond();
            }
            DB::commit();
            $afilledField = $offerService->getSingleField($request->user_offer_id);

            $transformer = new UserOfferTransformer;

            return api()->status(200)->message('User offer field saved successfully')->data($transformer->transformUserOffer($afilledField->useroffer))->respond();
        } catch (\Exception $ex) {
            DB::rollback();
            logger()->error('Error filling offers form : ' . $ex);
            return api()->status(500)->message('Error filling offers form: ' . $ex->getMessage())->respond();
        }
    }
}
