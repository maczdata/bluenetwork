<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           ServiceType.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Models\Common;

use App\Abstracts\BaseModel;
use App\Traits\Common\HasCustomFields;
use App\Traits\Common\HasSlug;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kalnoy\Nestedset\NestedSet;
use Kalnoy\Nestedset\NodeTrait;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\Sluggable\SlugOptions;

/**
 * Class ServiceType
 * @package App\Models\Common
 */
class ServiceType extends BaseModel
{
    use HasSlug, SoftDeletes;

    protected $table = 'service_types';

    protected $fillable = [
        'title',
        'slug',
        'enabled',
    ];

    public const R_SERVICES = "services";

    public static function boot()
    {
        parent::boot();

        static::deleting(function (ServiceType $serviceType) {
            $serviceType->services()->delete();
        });
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
     * @return HasMany
     */
    public function services(): HasMany
    {
        return $this->hasMany(Service::class, 'service_type_id');
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
            AllowedFilter::exact("slug"),
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
            AllowedSort::field("slug"),
            AllowedSort::field("id"),
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
            AllowedInclude::relationship(self::R_SERVICES),
            AllowedInclude::relationship(self::R_SERVICES . '.' . Service::R_SERVICE_FEATURES),
            AllowedInclude::relationship(self::R_SERVICES . '.' . Service::R_ORDERS),
            AllowedInclude::relationship(self::R_SERVICES . '.' . Service::R_ADDONS),
            AllowedInclude::relationship(self::R_SERVICES . '.' . Service::R_SIZES),
            AllowedInclude::relationship(self::R_SERVICES . '.' . Service::R_ORDER_ITEM),
            AllowedInclude::relationship(self::R_SERVICES . '.' . Service::R_META),
            AllowedInclude::relationship(self::R_SERVICES . '.' . Service::R_PARENT),
            AllowedInclude::relationship(self::R_SERVICES . '.' . Service::R_CHILDREN),
            AllowedInclude::relationship(self::R_SERVICES . '.' . Service::R_CUSTOM_FIELDS),
            AllowedInclude::relationship(self::R_SERVICES . '.' . Service::R_MEDIA),
            AllowedInclude::relationship(self::R_SERVICES . '.' . Service::R_VARIANTS),
            AllowedInclude::relationship(self::R_SERVICES . '.' . Service::R_VARIANTS . '.' . ServiceVariant::R_SERVICE_FEATURES),
            AllowedInclude::relationship(self::R_SERVICES . '.' . Service::R_VARIANTS . '.' . ServiceVariant::R_ORDERS),
            AllowedInclude::relationship(self::R_SERVICES . '.' . Service::R_VARIANTS . '.' . ServiceVariant::R_ADDONS),
            AllowedInclude::relationship(self::R_SERVICES . '.' . Service::R_VARIANTS . '.' . ServiceVariant::R_ORDER_ITEM),
            AllowedInclude::relationship(self::R_SERVICES . '.' . Service::R_VARIANTS . '.' . ServiceVariant::R_SERVICE),
            AllowedInclude::relationship(self::R_SERVICES . '.' . Service::R_VARIANTS . '.' . ServiceVariant::R_META),
            AllowedInclude::relationship(self::R_SERVICES . '.' . Service::R_VARIANTS . '.' . ServiceVariant::R_PARENT),
            AllowedInclude::relationship(self::R_SERVICES . '.' . Service::R_VARIANTS . '.' . ServiceVariant::R_CHILDREN),
            AllowedInclude::relationship(self::R_SERVICES . '.' . Service::R_VARIANTS . '.' . ServiceVariant::R_CUSTOM_FIELDS),
            AllowedInclude::relationship(self::R_SERVICES . '.' . Service::R_VARIANTS . '.' . ServiceVariant::R_MEDIA),
        ];
    }
}
