<?php

namespace App\Models\Users;

use App\Models\Common\AccountLevel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProfile extends Model
{
    protected $table = 'user_profiles';
    /**
     * @var string[]
     */

    protected $guarded = [
        'id',
        'updated_at',
        '_token',
        '_method',
    ];

    protected $hidden = [
        'withdrawal_pin',
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'bvn_verified_at' => 'datetime',
        'proof_of_address_verified_at' => 'datetime',
        'identity_verified_at' => 'datetime',
    ];

    /**
     * Model's boot function
     */
    public static function boot()
    {
        parent::boot();

        static::updating(static function (self $userProfile) {
            if (
                $userProfile->user->email_verified_at &&
                $userProfile->user->phone_verified_at &&
                $userProfile->bvn_verified_at &&
                $userProfile->identity_verified_at &&
                $userProfile->proof_of_address_verified_at &&
                $userProfile->account_level_id !== 4
            ) {
                $userProfile->account_level_id = 4;
            }
            if (
                $userProfile->user->email_verified_at &&
                $userProfile->user->phone_verified_at &&
                $userProfile->bvn_verified_at &&
                is_null($userProfile->identity_verified_at) &&
                is_null($userProfile->proof_of_address_verified_at) &&
                $userProfile->account_level_id !== 3
            ) {
                $userProfile->account_level_id = 3;
            }
        });
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function accountLevel(): BelongsTo
    {
        return $this->belongsTo(AccountLevel::class);
    }
}
