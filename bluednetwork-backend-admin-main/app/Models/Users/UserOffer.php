<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  bluediamondbackend
 * @file                           UserOffer.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     04/06/2022, 9:09 AM
 */

namespace App\Models\Users;

use App\Models\Offer;
use App\Models\UserOfferField;
use App\Traits\Transactions\BelongsToTransaction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class UserOffer extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasFactory;
    use BelongsToTransaction;

    protected $fillable = [
        'offer_id',
        'user_id',
        'amount',
        'status',
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasOne
     */
    public function offer(): HasOne
    {
        return $this->hasOne(Offer::class, 'id', 'offer_id');
    }

    /**
     * @return HasMany
     */
    public function fields(): HasMany
    {
        return $this->hasMany(UserOfferField::class, 'user_offer_id', 'id');
    }
}
