<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           GiftCardCurrencyTransformer.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     14/08/2021, 11:06 PM
 */

namespace App\Transformers\Common;

use App\Models\Common\GiftCardCurrency;
use League\Fractal\TransformerAbstract;

/**
 * Class GiftCardCurrencyTransformer
 * @package App\Transformers\Common
 */
class GiftCardCurrencyTransformer extends TransformerAbstract
{
    protected array $availableIncludes = [


    ];

    /**
     * @param GiftCardCurrency $giftCardCurrency
     * @return array
     */
    public function transform(GiftCardCurrency $giftCardCurrency): array
    {
        return [
            'id' => $giftCardCurrency->id,
            'name' => $giftCardCurrency->currency->name,
        ];
    }
}
