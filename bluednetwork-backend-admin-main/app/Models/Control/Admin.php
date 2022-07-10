<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           Admin.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     21/08/2021, 1:34 AM
 */

namespace App\Models\Control;

use App\Abstracts\AuthenticationModel;
use Database\Factories\AdminFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Spatie\Image\Exceptions\InvalidManipulation;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Admin extends AuthenticationModel implements HasMedia
{
    use InteractsWithMedia, Notifiable;

    protected $table = 'admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone_number',
        'intl_phone_number',
        'email_verified_at',
        'phone_verified_at',
    ];


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
        'user_avatar'
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

        static::saving(function (self $admin) {
            if (!is_null($admin->password)) {
                // Hash user password, if not already hashed
                if (Hash::needsRehash($admin->password)) {
                    $admin->password = Hash::make($admin->password);
                }
            }
        });
    }


    /**
     * Create a new factory instance for the model.
     *
     * @return AdminFactory|Factory
     */
    protected static function newFactory()
    {
        return AdminFactory::new();
    }

    /**
     * Get the user's full name.
     */
    public function getFullNameAttribute(): string
    {
        return ucfirst($this->first_name) . ' ' . ucfirst($this->last_name);
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
        return 'https://ui-avatars.com/api/?name='.urlencode($this->getFullNameAttribute()).'&color=7F9CF5&background=EBF4FF';
    }
}
