<?php

namespace App\Models;

use App\Models\Users\UserOffer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class UserOfferField extends Model implements HasMedia
{

    use InteractsWithMedia;
    use HasFactory;

    protected $fillable = [
        'user_offer_id',
        'offer_field_id',
        'filled_field',
        'type'
    ];

    /**
     * @return HasOne
     */
    public function useroffer(): HasOne
    {
        return $this->hasOne(UserOffer::class,'id', 'user_offer_id');
    }
}
