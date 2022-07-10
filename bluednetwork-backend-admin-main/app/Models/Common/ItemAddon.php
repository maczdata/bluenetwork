<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           ItemAddon.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Models\Common;

use App\Abstracts\BaseModel;
use App\Models\Sales\OrderItem;
use App\Traits\Common\HasSlug;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\Sluggable\SlugOptions;

class ItemAddon extends BaseModel
{
    use HasSlug;

    protected $table = 'item_addons';

    protected $fillable = [
        'enabled',
        'slug',
        'title',
        'description',
        'price'
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
     * @return MorphTo
     */
    public function itemableaddonable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return MorphOne
     */
    public function orderitem(): MorphOne
    {
        return $this->morphOne(OrderItem::class, 'orderitemable');
    }
}
