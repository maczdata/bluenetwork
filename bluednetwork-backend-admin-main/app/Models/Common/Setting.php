<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  bluediamondbackend
 * @file                           Setting.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     04/06/2022, 9:26 AM
 */

namespace App\Models\Common;

use App\Abstracts\BaseModel;
use App\Traits\Common\HasCustomFields;
use App\Traits\Common\HasSlug;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Plank\Metable\Metable;
use Spatie\Image\Exceptions\InvalidManipulation;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\Sluggable\SlugOptions;

class Setting extends BaseModel implements HasMedia
{
    use SoftDeletes;
    use InteractsWithMedia;
    use HasCustomFields;
    use HasSlug;
    use Metable;

    protected $guarded = [
        'id',
        'updated_at',
        '_token',
        '_method',
    ];

    protected $hidden = [
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public const R_MEDIA = "media";

    protected $with = [
        "meta",
        "media",
    ];

    protected static function boot()
    {
        parent::boot();

        // create a event to happen on creating
        static::creating(static function ($table) {
            $table->created_by = Auth::id();
            $table->created_at = Carbon::now();
        });

        // create a event to happen on updating
        static::updating(static function ($table) {
            $table->updated_by = Auth::id();
        });

        // create a event to happen on saving
        static::saving(static function ($table) {
            $table->updated_by = Auth::id();
        });

        // create a event to happen on deleting
        static::deleting(static function ($table) {
            $table->deleted_by = Auth::id();
            $table->save();
        });
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
     * @param Media|null $media
     */
    public function registerMediaConversions(Media $media = null): void
    {
        try {
            $this->addMediaConversion('small')
                ->width(120)
                ->height(120);

            $this->addMediaConversion('medium')
                ->width(200)
                ->height(200);
            $this->addMediaConversion('large')
                ->width(480);
            //->height(120);
        } catch (InvalidManipulation $e) {
            report($e);
        }
    }

    public function settingType()
    {
        return $this->belongsTo(SettingType::class);
    }

    /**
     * The attributes that should be use for filtering.
     *
     *
     * @return array
     */
    public static function filterFields(): array
    {
        return [
            AllowedFilter::exact("id"),
            AllowedFilter::partial("name"),
            AllowedFilter::partial("type"),
        ];
    }

    /**
     * The attributes that should be use for sorting.
     *
     *
     * @return array
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
     *
     * @return array
     */
    public static function includeRelationships(): array
    {
        return [
            AllowedInclude::relationship(self::R_MEDIA),
        ];
    }
}
