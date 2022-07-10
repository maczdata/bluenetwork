<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           User.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     20/08/2021, 10:50 PM
 */

namespace App\Models\Users;

use App\Abstracts\AuthenticationModel;
use App\Models\Common\Referral;
use App\Models\Finance\PayoutRequest;
use App\Models\Finance\WithdrawalSetup;
use App\Models\Sales\Order;
use App\Notifications\Users\UserResetPassword;
use App\Traits\Common\HasConnectedAccounts;
use App\Traits\Common\HasWallet;
use App\Traits\Transactions\HasTransactions;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Image\Exceptions\InvalidManipulation;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Class User
 *
 * @package App\Models\Users
 */
class User extends AuthenticationModel implements JWTSubject, HasMedia
{
    use Notifiable;
    use HasConnectedAccounts;
    use HasWallet;
    use InteractsWithMedia;
    use HasTransactions;
    use HasRoles;
    use HasPermissions;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ref_code',
        'ref_id',
        'first_name',
        'last_name',
        'username',
        'email',
        'password',
        'phone_number',
        'intl_phone_number',
        'email_verified_at',
        'phone_verified_at',
    ];

    // public $guard_name = '*';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    /**
     * @var string[]
     */
    protected $appends = [
        'user_avatar',
        'role',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
    ];

    /**
     * Model's boot function
     */
    public static function boot()
    {
        parent::boot();

        static::saving(function (self $user) {
            if (!is_null($user->password)) {
                // Hash user password, if not already hashed
                if (Hash::needsRehash($user->password)) {
                    $user->password = Hash::make($user->password);
                }
            }
        });

        static::created(function (self $user) {
            UserProfile::create(['user_id' => $user->id]);
        });
        static::updating(function (self $user) {
            if (!is_null($user->email_verified_at) &&
                !is_null($user->phone_verified_at) &&
                is_null($user->profile->bvn_verified_at) &&
                is_null($user->profile->identity_verified_at) &&
                is_null($user->profile->proof_of_address_verified_at) &&
                $user->profile->account_level_id === 1
            ) {
                $user->profile->account_level_id = 2;
                $user->profile->save();
            }
        });
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return UserFactory
     */
    protected static function newFactory()
    {
        return UserFactory::new();
    }

    /**
     * Send the password reset notification.
     *
     * @param string $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new UserResetPassword($token));
    }

    /**
     * Get the user's full name.
     */
    public function getFullNameAttribute(): string
    {
        return ucfirst($this->first_name) . ' ' . ucfirst($this->last_name);
    }

    public function getRoleAttribute(): ?string
    {
        return $this->roles?->pluck('name')->first();
    }

    /**
     * @param Media|null $media
     */
    public function registerMediaConversions(Media $media = null): void
    {
        try {
            $this->addMediaConversion('small')
                ->width(120)
                ->height(120);

            $this->addMediaConversion('medium')
                ->width(200)
                ->height(200);
            $this->addMediaConversion('large')
                ->width(480);
            //->height(120);
        } catch (InvalidManipulation $e) {
            report($e);
        }
    }

    /**
     * @return string|null
     */
    public function getUserAvatarAttribute(): ?string
    {
        $avatarUrl = '';
        $mediaItem = $this->getFirstMediaUrl('profile_images');
        if (!is_null($mediaItem) && !empty($mediaItem)) {
            $avatarUrl = $mediaItem;
        }
        if (!empty($avatarUrl) && @getimagesize($avatarUrl)) {
            return $avatarUrl;
        }
        return $this->defaultProfilePhotoUrl();
    }

    /**
     * Get the default profile photo URL if no profile photo has been uploaded.
     *
     * @return string
     */
    protected function defaultProfilePhotoUrl(): string
    {
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->getFullNameAttribute()) . '&color=7F9CF5&background=EBF4FF';
    }


    /**
     * Route notifications for the Africas Talking channel.
     *
     * @param Notification $notification
     * @return string
     */
    public function routeNotificationForAfricasTalking($notification): string
    {
        return $this->intl_phone_number;
    }

    /**
     * @return bool
     */
    public function hasVerifiedPhone(): bool
    {
        return !is_null($this->phone_verified_at);
    }

    /**
     * @return bool
     */
    public function markPhoneAsVerified(): bool
    {
        return $this->forceFill([
            'phone_verified_at' => $this->freshTimestamp(),
        ])->save();
    }

    /**
     * @param Builder $query
     * @param $referral
     * @return bool
     */
    public function scopeReferralExists(Builder $query, $referral): bool
    {
        return $query->where(function ($query) use ($referral) {
            $query->where('ref_code', $referral)->orWhere('username', $referral);
        })->exists();
    }

    /**
     * @return string
     */
    public function generateReferral(): string
    {
        $length = 5;
        do {
            $referral = Str::random($length);
        } while ($this->referralExists($referral));

        return $referral;
    }

    /**
     * A user has a referrer.
     *
     * @return HasOne
     */
    public function referrer(): HasOne
    {
        return $this->hasOne(Referral::class, 'user_id', 'id');
    }


    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }


    public function userprofile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function virtual()
    {
        return $this->hasOne(VirtualAccount::class);
    }

    public function user_offer()
    {
        return $this->hasOne(UserOffer::class);
    }

    public function user_offers()
    {
        return $this->hasMany(UserOffer::class);
    }


    /**
     * A user has many referrals.
     *
     * @return HasMany
     */
    public function referrals(): HasMany
    {
        return $this->hasMany(Referral::class, 'referral_id', 'id');
    }

    /**
     * @return MorphMany
     */
    public function payouts(): MorphMany
    {
        return $this->morphMany(PayoutRequest::class, 'ownerable');
    }

    /**
     * @return MorphMany
     */
    public function withdrawal_setups(): MorphMany
    {
        return $this->morphMany(WithdrawalSetup::class, 'withdrawable');
    }

    /**
     * @return HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
