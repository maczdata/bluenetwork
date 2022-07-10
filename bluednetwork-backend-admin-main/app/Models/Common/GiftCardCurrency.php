<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           GiftCardCurrency.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Models\Common;

use App\Abstracts\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class GiftCardCurrency
 * @package App\Models\Common
 */
class GiftCardCurrency extends BaseModel
{
    protected $table = 'gift_card_currencies';
    /**
     * @var string[]
     */
    protected $fillable = [
        'gift_card_id',
        'currency_id',
    ];

    /**
     * @return BelongsTo
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    /**
     * @return BelongsTo
     */
    /*public function giftCardForm(): BelongsTo
    {
        return $this->belongsTo(GiftCardForm::class);
    }*/
    /**
     * @return HasMany
     */
    public function currencyRates(): HasMany
    {
        return $this->hasMany(GiftCardCurrencyRate::class);
    }

    /**
     * @return BelongsTo
     */
    public function giftCard(): BelongsTo
    {
        return $this->belongsTo(GiftCard::class);
    }
}
