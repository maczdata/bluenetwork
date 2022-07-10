<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           GiftCardCategoryTransformer.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     18/08/2021, 12:09 PM
 */

namespace App\Transformers\Common;

use App\Models\Common\GiftCardCurrency;
use App\Models\Common\GiftCardCategory;
use App\Models\Common\Service;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\NullResource;
use League\Fractal\Resource\Primitive;
use League\Fractal\TransformerAbstract;

/**
 * Class GiftCardFormTransformer
 * @package App\Transformers\Common
 */
class GiftCardCategoryTransformer extends TransformerAbstract
{
    protected array $defaultIncludes = [
        'category',
        //'currency_rates',
        //'children'
    ];

    /**
     * @param GiftCardCategory $giftCardCategory
     * @return array
     */
    public function transform(GiftCardCategory $giftCardCategory): array
    {
        return [
            'id' => $giftCardCategory->id,
            //'title' => $giftCardCategory->categoryOfGiftCard->title,
        ];
    }

    /**
     * @param GiftCardCategory $giftCardForm
     * @return NullResource|Collection
     */
    public function includeCurrencyRates(GiftCardCategory $giftCardForm): NullResource|Collection
    {
        return !$giftCardForm->currencyRates->isEmpty()
            ? $this->collection($giftCardForm->currencyRates, new GiftCardCurrencyRateTransformer())
            : $this->null();
    }

    /**
     * @param GiftCardCategory $giftCardCategory
     * @return Item
     */
    public function includeCategory(GiftCardCategory $giftCardCategory): Item
    {
        return $this->item($giftCardCategory->categoryOfGiftCard, (new CategoryOfGiftCardTransformer)->setDefaultIncludes(['children']));
    }
}
