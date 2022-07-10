<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           GiftCardCurrencyRateTransformer.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     19/08/2021, 1:25 PM
 */

namespace App\Transformers\Common;

use App\Models\Common\GiftCardCurrency;
use App\Models\Common\GiftCardCurrencyRate;
use App\Models\Common\GiftCardCategory;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\NullResource;
use League\Fractal\TransformerAbstract;

/**
 * Class GiftCardCurrencyRateTransformer
 * @package App\Transformers\Common
 */
class GiftCardCurrencyRateTransformer extends TransformerAbstract
{
    /**
     * @param GiftCardCurrencyRate $giftCardCurrencyRate
     * @return array
     */
    public function transform(GiftCardCurrencyRate $giftCardCurrencyRate)
    {
        return [
            'id' => $giftCardCurrencyRate->id,
            'category_id' => $giftCardCurrencyRate->gift_card_category_id,
            'rate_type' => $giftCardCurrencyRate->rate_type,
            'face_value_from' => $giftCardCurrencyRate->face_value_from,
            'face_value_to' => $giftCardCurrencyRate->face_value_to,
            'rate_value' => $giftCardCurrencyRate->rate_value,
        ];
    }
}
