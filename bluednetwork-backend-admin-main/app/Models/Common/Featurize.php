<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           Featurize.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Models\Common;

use App\Abstracts\BaseModel;
use App\Models\Sales\OrderItem;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class Featurize
 * @package App\Models\Common
 */
class Featurize extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'featurizes';

    protected $fillable = [
        'enabled',
        'feature_id',
    ];

    /**
     * @return MorphTo
     */
    public function featureable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return BelongsTo
     */
    public function feature(): BelongsTo
    {
        return $this->belongsTo(Feature::class);
    }

    /**
     * @return HasMany
     */
    public function featureValues(): HasMany
    {
        return $this->hasMany(FeaturizeValue::class);
    }

    /**
     * @return MorphOne
     */
    public function orderitem(): MorphOne
    {
        return $this->morphOne(OrderItem::class, 'orderitemable');
    }
}
