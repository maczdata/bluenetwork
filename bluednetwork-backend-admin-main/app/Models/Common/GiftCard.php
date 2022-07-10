<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           GiftCard.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     14/08/2021, 9:38 AM
 */

namespace App\Models\Common;

use App\Abstracts\BaseModel;
use App\Models\Sales\Order;
use App\Traits\Common\HasSlug;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\SlugOptions;

/**
 * Class GiftCard
 * @package App\Models\Common
 */
class GiftCard extends BaseModel implements HasMedia
{
    use InteractsWithMedia, HasSlug, SoftDeletes;

    protected $table = 'gift_cards';
    /**
     * @var string[]
     */
    protected $fillable = [
        'title',
        'enabled',
        'description',
        'slug',
    ];

    /**
     * @var string[]
     */
    protected $appends = [
        'gift_card_image'
    ];
    /**
     * @return string|null
     */
    public function getGiftCardImageAttribute(): ?string
    {
        $mediaItem = $this->getFirstMediaUrl('giftcard_logos');
        if (!is_null($mediaItem) && !empty($mediaItem)) {
            return $mediaItem;
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->title) . '&color=7F9CF5&background=EBF4FF';
    }

    /**
     * @return MorphMany
     */
    public function orders(): MorphMany
    {
        return $this->morphMany(Order::class, 'orderable');
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

     /**
     * @return MorphMany
     */
    public function coupons(): MorphMany
    {
        return $this->morphMany(Coupon::class, 'couponable');
    }

    /**
     * @return HasMany
     */
    public function currencies(): HasMany
    {
        return $this->hasMany(GiftCardCurrency::class);
    }

    /**
     * @return HasMany
     */
    public function categories(): HasMany
    {
        return $this->hasMany(GiftCardCategory::class);
    }
}
