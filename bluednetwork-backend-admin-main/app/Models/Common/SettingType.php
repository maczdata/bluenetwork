<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  bluediamondbackend
 * @file                           SettingType.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     03/06/2022, 6:02 PM
 */

namespace App\Models\Common;

use App\Abstracts\BaseModel;
use App\Traits\Common\HasSlug;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\Sluggable\SlugOptions;

class SettingType extends BaseModel
{
    use HasSlug;
    use SoftDeletes;

    protected $guarded = [
        'id',
        'updated_at',
        '_token',
        '_method',
    ];

    protected $with = [
        "settings"
    ];

    public const R_SETTINGS = "settings";

    public function settings()
    {
        return $this->hasMany(Setting::class, 'setting_type_id');
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
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
            AllowedFilter::partial("name"),
            AllowedFilter::partial("settings.name"),
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
            AllowedSort::field("created_by"),
            AllowedSort::field("updated_by"),
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
            AllowedInclude::relationship(self::R_SETTINGS . '.' . Setting::R_MEDIA),
            AllowedInclude::relationship(self::R_SETTINGS),
        ];
    }
}
