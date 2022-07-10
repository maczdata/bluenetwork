<?php

namespace App\Models\Finance;

use App\Abstracts\BaseModel;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Coupon extends BaseModel
{
    use SoftDeletes;

    protected $guarded = [
        'id',
        'updated_at',
        '_token',
        '_method',
    ];

    protected static function boot()
    {
        parent::boot();

        // create a event to happen on creating
        static::creating(function ($table) {
            $table->created_by = Auth::id();
        });

        // create a event to happen on updating
        static::updating(function ($table) {
            $table->updated_by = Auth::id();
        });

        // create a event to happen on saving
        static::saving(function ($table) {
            $table->updated_by = Auth::id();
        });

        // create a event to happen on deleting
        static::deleting(function ($table) {
            $table->deleted_by = Auth::id();
            $table->save();
        });
    }

      /**
     * @return MorphTo
     */
    public function couponable(): MorphTo
    {
        return $this->morphTo();
    }

    public function discount(float $total)
    {
        if ($this->type === 'fixed') {
            return $this->value;
        } elseif ($this->type === 'percentage') {
            return ($this->percentage_off / 100) * $total;
        } else {
            return 0;
        }
    }
}
