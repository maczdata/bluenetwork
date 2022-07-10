<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           Size.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Models\Common;

use App\Abstracts\BaseModel;
use App\Traits\Common\HasSlug;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\Sluggable\SlugOptions;

class Size extends BaseModel
{
    use HasSlug;

    /**
     * @var string
     */
    protected $table = 'sizes';

    protected $fillable = [
        'enabled',
        'title',
        'slug',
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
    public function sizeable(): MorphTo
    {
        return $this->morphTo();
    }
}
