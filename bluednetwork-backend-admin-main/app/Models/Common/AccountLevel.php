<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  bluediamondbackend
 * @file                           AccountLevel.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     03/06/2022, 6:02 PM
 */

namespace App\Models\Common;

use App\Abstracts\BaseModel;
use App\Traits\Common\HasSlug;
use Illuminate\Database\Eloquent\SoftDeletes;
use Plank\Metable\Metable;
use Spatie\Sluggable\SlugOptions;

class AccountLevel extends BaseModel
{
    use HasSlug;
    use Metable;
    use SoftDeletes;

    protected $fillabe = [
        "name",
        "withdrawal_limit",
        "daily_limit",
        "transaction_limit",
    ];

    protected $guarded = [
        'id',
        'updated_at',
        '_token',
        '_method',
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }
}
