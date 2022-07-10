<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           Service.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     22/08/2021, 1:27 PM
 */

namespace App\Models\Common;

use App\Abstracts\BaseModel;
use App\Models\Sales\Order;
use App\Models\Sales\OrderItem;
use App\Traits\Common\HasCustomFields;
use App\Traits\Common\HasSlug;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kalnoy\Nestedset\NestedSet;
use Kalnoy\Nestedset\NodeTrait;
use Plank\Metable\Metable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\Sluggable\SlugOptions;

/**
 * Class Service
 * @package App\Models\Common
 */
class Service extends BaseModel implements HasMedia
{
    use NodeTrait, InteractsWithMedia, HasCustomFields, HasSlug, Metable, SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'services';

    /**
     * @var array
     */
    protected $fillable = [
        'service_type_id',
        'title',
        'key',
        'price',
        'discount_type',
        'discount_value',
        'description',
        'enabled',
        NestedSet::LFT,
        NestedSet::RGT,
        NestedSet::PARENT_ID,
    ];
    /**
     * @var string[]
     */
    protected $appends = [
        'service_icon'
    ];

    public const R_SERVICE_TYPE = "service_type";
    public const R_SERVICE_FEATURES = "serviceFeatures";
    public const R_ORDERS = "orders";
    public const R_ADDONS = "addons";
    public const R_SIZES = "sizes";
    public const R_ORDER_ITEM = "orderitem";
    public const R_VARIANTS = "variants";
    public const R_META = "meta";
    public const R_PARENT = "parent";
    public const R_CHILDREN = "children";
    public const R_CUSTOM_FIELDS = "customFields";
    public const R_MEDIA = "media";

    public static function boot()
    {
        parent::boot();

        static::deleting(function (Service $service) {
            $service->serviceFeatures()->delete();
            $service->addons()->delete();
            $service->orders()->delete();
            $service->sizes()->delete();
            $service->media()->delete();
            $service->variants()->delete();
        });
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('key');
    }

    /**
     * @return string|null
     */
    public function getServiceIconAttribute(): ?string
    {
        $iconUrl = '';
        $mediaItem = $this->getFirstMediaUrl('service_image');
        if (!is_null($mediaItem) && !empty($mediaItem)) {
            //$iconUrl = $mediaItem;
            return $mediaItem;
        }
        /*if (!empty($iconUrl) && @getimagesize($iconUrl)) {
            return $iconUrl;
        }*/
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->title) . '&color=7F9CF5&background=EBF4FF';
    }

    /**
     * @return MorphMany
     */
    public function serviceFeatures(): MorphMany
    {
        return $this->morphMany(Featurize::class, 'featureable');
    }

     /**
     * @return MorphMany
     */
    public function coupons(): MorphMany
    {
        return $this->morphMany(Coupon::class, 'couponable');
    }


    /**
     * @return MorphMany
     */
    public function addons(): MorphMany
    {
        return $this->morphMany(ItemAddon::class, 'itemaddonable');
    }

    /***
     * @return MorphMany
     */
    public function sizes(): MorphMany
    {
        return $this->morphMany(Size::class, 'sizeable');
    }

        /**
     * @return MorphMany
     */
    public function orders(): MorphMany
    {
        return $this->morphMany(Order::class, 'orderable');
    }

    /**
     * @return MorphOne
     */
    public function orderitem(): MorphOne
    {
        return $this->morphOne(OrderItem::class, 'orderitemable');
    }

    /**
     * @return HasMany
     */
    public function variants(): HasMany
    {
        return $this->hasMany(ServiceVariant::class);
    }

    /**
     * @return BelongsTo
     */
    public function service_type(): BelongsTo
    {
        return $this->belongsTo(ServiceType::class);
    }

     /**
     * The attributes that should be use for filtering.
     *
     * @var array
     */
    public static function filterFields(): array
    {
        return [
            AllowedFilter::exact("id"),
            AllowedFilter::exact("parent_id"),
            AllowedFilter::exact("service_type_id"),
            AllowedFilter::partial("title"),
            AllowedFilter::partial("key"),
            AllowedFilter::partial("price"),
            AllowedFilter::exact("enabled"),
        ];
    }

    /**
     * The attributes that should be use for sorting.
     *
     * @var array
     */
    public static function sortFields(): array
    {
        return [
            AllowedSort::field("created_at"),
            AllowedSort::field("updated_at"),
            AllowedSort::field("parent_id"),
            AllowedSort::field("service_type_id"),
            AllowedSort::field("enabled"),
            AllowedSort::field("price"),
        ];
    }

    /**
     * The attributes that should be use for including related models.
     *
     * @var array
     */
    public static function includeRelationships(): array
    {
        return [
            AllowedInclude::relationship(self::R_SERVICE_TYPE),
            AllowedInclude::relationship(self::R_SERVICE_FEATURES),
            AllowedInclude::relationship(self::R_ORDERS),
            AllowedInclude::relationship(self::R_ADDONS),
            AllowedInclude::relationship(self::R_SIZES),
            AllowedInclude::relationship(self::R_ORDER_ITEM),
            AllowedInclude::relationship(self::R_META),
            AllowedInclude::relationship(self::R_PARENT),
            AllowedInclude::relationship(self::R_CHILDREN),
            AllowedInclude::relationship(self::R_CUSTOM_FIELDS),
            AllowedInclude::relationship(self::R_MEDIA),
            AllowedInclude::relationship(self::R_VARIANTS),
            AllowedInclude::relationship(self::R_VARIANTS . '.' . ServiceVariant::R_SERVICE_FEATURES),
            AllowedInclude::relationship(self::R_VARIANTS . '.' . ServiceVariant::R_ORDERS),
            AllowedInclude::relationship(self::R_VARIANTS . '.' . ServiceVariant::R_ADDONS),
            AllowedInclude::relationship(self::R_VARIANTS . '.' . ServiceVariant::R_ORDER_ITEM),
            AllowedInclude::relationship(self::R_VARIANTS . '.' . ServiceVariant::R_SERVICE),
            AllowedInclude::relationship(self::R_VARIANTS . '.' . ServiceVariant::R_META),
            AllowedInclude::relationship(self::R_VARIANTS . '.' . ServiceVariant::R_PARENT),
            AllowedInclude::relationship(self::R_VARIANTS . '.' . ServiceVariant::R_CHILDREN),
            AllowedInclude::relationship(self::R_VARIANTS . '.' . ServiceVariant::R_CUSTOM_FIELDS),
            AllowedInclude::relationship(self::R_VARIANTS . '.' . ServiceVariant::R_MEDIA),
        ];
    }
}
