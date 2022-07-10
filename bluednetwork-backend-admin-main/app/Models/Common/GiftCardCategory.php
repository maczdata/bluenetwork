<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           GiftCardCategory.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     15/08/2021, 10:27 AM
 */

namespace App\Models\Common;

use App\Abstracts\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class GiftCardForm
 *
 * @package App\Models\Common
 */
class GiftCardCategory extends BaseModel
{
    protected $table = 'gift_card_categories';
    /**
     * @var string[]
     */
    protected $fillable = [
        'gift_card_id',
        'category_of_gift_card_id',
    ];

    /**
     * @var string[]
     */
    protected $appends = [
        'requires_upload',
        'requires_code',
    ];

    /**
     * @return BelongsTo
     */
    public function giftCard(): BelongsTo
    {
        return $this->belongsTo(GiftCard::class);
    }

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
    public function categoryOfGiftCard(): BelongsTo
    {
        return $this->belongsTo(CategoryOfGiftCard::class);
    }

    /**
     * @return bool
     */
    public function getRequiresCodeAttribute(): bool
    {
        return $this->categoryOfGiftCard->getMeta('requires_code') === true;
    }

    /**
     * @return bool
     */
    public function getRequiresUploadAttribute(): bool
    {
        return $this->categoryOfGiftCard->getMeta('requires_upload') === true;
    }
}
