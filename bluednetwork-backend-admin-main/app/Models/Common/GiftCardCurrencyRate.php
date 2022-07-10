<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           GiftCardCurrencyRate.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     19/08/2021, 1:35 PM
 */

namespace App\Models\Common;

use App\Abstracts\BaseModel;
use App\Models\Sales\OrderItem;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * Class GiftCardCurrencyRate
 * @package App\Models\Common
 */
class GiftCardCurrencyRate extends BaseModel
{
    protected $table = 'gift_card_currency_rates';
    /**
     * @var string[]
     */
    protected $fillable = [
        'gift_card_id',
        'gift_card_currency_id',
        'rate_type',
        'gift_card_category_id',
        'face_value_from',
        'face_value_to',
        'rate_value',
    ];

    /**
     * @return MorphOne
     */
    public function orderitem(): MorphOne
    {
        return $this->morphOne(OrderItem::class, 'orderitemable');
    }
    /**
     * @return BelongsTo
     */
    public function giftCardCurrency(): BelongsTo
    {
        return $this->belongsTo(GiftCardCurrency::class);
    }

    /**
     * @return BelongsTo
     */
    public function giftCard(): BelongsTo
    {
        return $this->belongsTo(GiftCard::class);
    }

    /**
     * @return BelongsTo
     */
    public function giftCardCategory(): BelongsTo
    {
        return $this->belongsTo(GiftCardCategory::class);
    }
}
