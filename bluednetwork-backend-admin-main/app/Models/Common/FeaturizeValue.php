<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           FeaturizeValue.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Models\Common;

use App\Abstracts\BaseModel;
use App\Models\Sales\OrderItem;
use App\Traits\Common\HasSlug;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Spatie\Sluggable\SlugOptions;

/**
 * Class ServiceFeatureValue
 * @package App\Models\Common
 */
class FeaturizeValue extends BaseModel
{
    use HasSlug;
    /**
     * @var string
     */
    protected $table = 'featurize_values';

    protected $fillable = [
      'featurize_id',
      'title',
      'slug',
      'description',
      //'price'
    ];

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
     * @return BelongsTo
     */
    public function serviceFeature(): BelongsTo
    {
        return $this->belongsTo(Featurize::class);
    }

    /**
     * @return MorphOne
     */
    public function orderitem(): MorphOne
    {
        return $this->morphOne(OrderItem::class, 'orderitemable');
    }
}
