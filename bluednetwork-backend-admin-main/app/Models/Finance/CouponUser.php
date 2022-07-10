<?php

namespace App\Models\Finance;

use App\Abstracts\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class CouponUser extends BaseModel
{
    use SoftDeletes;

    protected $guarded = [
        'id',
        '_token',
        '_method',
    ];

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
