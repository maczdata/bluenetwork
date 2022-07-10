<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           GiftCardTransformer.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     14/08/2021, 9:38 AM
 */

namespace App\Transformers\Common;

use App\Models\Common\GiftCard;
use Illuminate\Support\Collection;
use League\Fractal\Resource\NullResource;
use League\Fractal\TransformerAbstract;

/**
 * Class GiftCardTransformer
 * @package App\Transformers\Common
 */
class GiftCardTransformer extends TransformerAbstract
{
    protected array $availableIncludes = [
        'currencies',
        'categories'
    ];
    /**
     * @param GiftCard $giftCard
     * @return array
     */
    public function transform(GiftCard $giftCard)
    {
        return [
            'id' => $giftCard->id,
            'title' => $giftCard->title,
            'slug' => $giftCard->slug,
            'gift_card_image'=> $giftCard->gift_card_image,
        ];
    }

    /**
     * @param GiftCard $giftCard
     * @return \League\Fractal\Resource\Collection|NullResource
     */
    public function includeCurrencies(GiftCard $giftCard): NullResource|\League\Fractal\Resource\Collection
    {
        return !$giftCard->currencies->isEmpty()
            ? $this->collection($giftCard->currencies, new GiftCardCurrencyTransformer())
            : $this->null();
    }

    /**
     * @param GiftCard $giftCard
     * @return \League\Fractal\Resource\Collection|NullResource
     */
    public function includeCategories(GiftCard $giftCard): NullResource|\League\Fractal\Resource\Collection
    {
        return !$giftCard->categories->isEmpty()
            ? $this->collection($giftCard->categories, new GiftCardCategoryTransformer())
            : $this->null();
    }
}
