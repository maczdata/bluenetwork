<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           OrderCacDirector.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     22/08/2021, 1:38 PM
 */

namespace App\Models\Sales;

use App\Abstracts\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class OrderCacDirector extends BaseModel implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'order_cac_directors';

    protected $fillable = [
        'order_id',
        'designation',
        'full_name',
        'email',
        'phone_number',
        'address'
    ];

    /**
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
