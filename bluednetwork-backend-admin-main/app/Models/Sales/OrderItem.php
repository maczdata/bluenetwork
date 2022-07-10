<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           OrderItem.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Models\Sales;

use App\Abstracts\BaseModel;
use App\Models\Common\Service;
use App\Models\Common\ServiceVariant;
use App\Traits\Common\HasCustomFieldResponses;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * Class OrderItem
 * @package App\Models\Sales
 */
class OrderItem extends BaseModel implements HasMedia
{
    use HasCustomFieldResponses, InteractsWithMedia;
    /**
     * @var string
     */
    protected $table = 'order_items';
    /**
     * @var string[]
     */
    protected $fillable = [
        'order_id',
        'price',
        'total',
        'quantity'
    ];

    /**
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * @return MorphTo
     */
    public function orderitemable(): MorphTo
    {
        return $this->morphTo();
    }
}
