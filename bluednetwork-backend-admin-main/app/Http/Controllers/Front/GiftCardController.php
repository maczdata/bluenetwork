<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           GiftCardController.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     18/08/2021, 12:19 PM
 */

namespace App\Http\Controllers\Front;

use App\Abstracts\Http\Controllers\FrontController;
use App\Repositories\Common\FormOfGiftCardRepository;
use App\Repositories\Common\GiftCardCurrencyRateRepository;
use App\Repositories\Common\GiftCardCurrencyRepository;
use App\Repositories\Common\GiftCardRepository;
use App\Transformers\Common\CategoryOfGiftCardTransformer;
use App\Transformers\Common\GiftCardCategoryTransformer;
use App\Transformers\Common\GiftCardCurrencyRateTransformer;
use App\Transformers\Common\GiftCardTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class GiftCardController
 *
 * @package App\Http\Controllers\Front
 */
class GiftCardController extends FrontController
{
    /**
     * GiftCardController constructor.
     *
     * @param GiftCardRepository $giftCardRepository
     * @param FormOfGiftCardRepository $formOfGiftCardRepository
     * @param GiftCardCurrencyRateRepository $giftCardCurrencyRateRepository
     */
    public function __construct(protected GiftCardRepository $giftCardRepository, protected FormOfGiftCardRepository $formOfGiftCardRepository, protected GiftCardCurrencyRateRepository $giftCardCurrencyRateRepository)
    {
        parent::__construct();
    }

    /**
     * @OA\Get(
     *      path="/gift_card/list",
     *      operationId="all_gift_card",
     *      tags={"Gift Card"},
     *      summary="all gift card",
     *      description="return all gift card",
     *     @OA\Response(response=200,description="fetched all gift card"),
     *     @OA\Response(response=500, description="Bad request"),
     *     )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function allGiftCards(Request $request): JsonResponse
    {
        try {
            $giftCards = $this->giftCardRepository->scopeQuery(function ($query) {
                return $query->enabled();
            })->get();

            return api()->data(fractal($giftCards, GiftCardTransformer::class)->toArray())->respond();
        } catch (\Exception $ex) {
            logger()->error('Error fetching items : ' . $ex);
            return api()->status(500)->message('Error fetching items')->respond();
        }
    }

    /**
     * @OA\Get(
     *      path="/gift_card/form_of",
     *      operationId="all_gift_card_type",
     *      tags={"Gift Card"},
     *      summary="all forms of gift card",
     *      description="return all forms of gift card",
     *     @OA\Response(response=200,description="fetched all forms of gift card"),
     *     @OA\Response(response=500, description="Bad request"),
     *     )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function formOfGiftCards(): JsonResponse
    {
        try {
            $giftCards = $this->formOfGiftCardRepository->scopeQuery(function ($query) {
                return $query->whereNull('parent_id')->enabled();
            })->get();

            return api()->data(fractal(
                $giftCards,
                CategoryOfGiftCardTransformer::class
            )->parseIncludes(['children'])->toArray())->respond();
        } catch (\Exception $ex) {
            logger()->error('Error fetching categories of giftcard : ' . $ex);
            return api()->status(500)->message('Error fetching categories items')->respond();
        }
    }


    /**
     * @OA\Get(
     *      path="/gift_card/single/{gift_card_id}",
     *      operationId="single_gift_card_detail",
     *      tags={"Gift Card"},
     *      summary="single gift card detail",
     *      description="return single gift card detail",
     *      @OA\Parameter(
     *          name="gift_card_id",
     *          description="gift_card_id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *       ),
     *     @OA\Response(response=200,description="fetched gift card"),
     *     @OA\Response(response=500, description="Bad request"),
     *     )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function serviceGiftCard(Request $request): JsonResponse
    {
        $giftCardId = $request->gift_card_id ?? null;
        try {
            $giftCard = $this->giftCardRepository->scopeQuery(function ($query) use ($giftCardId) {
                return $query->where(['id' => $giftCardId])->enabled();
            })->first();
            return api()->status(200)->data(fractal($giftCard, GiftCardTransformer::class)
                ->parseIncludes([
                    'currencies',
                    'categories',
                ])->toArray())->respond();
        } catch (\Exception $ex) {
            logger()->error('Error fetching gift card : ' . $ex);
            return api()->status(500)->message('Error fetching items')->respond();
        }
    }

    /**
     * @OA\Get(
     *      path="/gift_card/single/{gift_card_id}/{gift_card_currency_id}/{gift_card_category_id}/currency_rates",
     *      operationId="single_gift_card_currency_rates",
     *      tags={"Gift Card"},
     *      summary="single gift card currency rates",
     *      description="return single gift card currency rates",
     *      @OA\Parameter(
     *          name="gift_card_id",
     *          description="The id of the selected giftcard",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *       ),
     *     @OA\Parameter(
     *          name="gift_card_currency_id",
     *          description="The id of the selected gift card currency",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *       ),
     *     @OA\Parameter(
     *          name="gift_card_category_id",
     *          description="The id of the selected gift card category",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *       ),
     *     @OA\Response(response=200,description="fetched gift card"),
     *     @OA\Response(response=500, description="Bad request"),
     *     )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function giftCurrencyRates(Request $request): JsonResponse
    {
        $giftCardId = $request->gift_card_id;
        $currencyId = $request->gift_card_currency_id;
        $categoryId = $request->gift_card_category_id;

        try {
            $giftCardCurrencies = $this->giftCardCurrencyRateRepository->scopeQuery(function ($query) use ($giftCardId, $currencyId, $categoryId) {
                return $query->where(['gift_card_currency_id' => $currencyId, 'gift_card_category_id' => $categoryId])->whereHas('giftCard', function ($query) use ($giftCardId) {
                    return $query->where('id', $giftCardId)->enabled();
                })->whereHas('giftCardCurrency');
            })->get();
            return api()->status(200)->data(fractal(
                $giftCardCurrencies,
                GiftCardCurrencyRateTransformer::class
            )->toArray())->respond();
        } catch (\Exception $ex) {
            logger()->error('Error fetching gift card : ' . $ex);
            return api()->status(500)->message('Error fetching items')->respond();
        }
    }
}
