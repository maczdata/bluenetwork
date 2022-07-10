<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           UserDevice.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Models\Common;

use App\Abstracts\BaseModel;

/**
 * Class UserDevice
 * @package App\Models\Common
 */
class UserDevice extends BaseModel
{
    /**
     *
     */
    const DEVICE_TYPE = 'device_type';

    /**
     * @var string
     */
    const DEVICE_ID = 'device_id';

    /**
     * @var string
     */
    const ACCESS_TOKEN = 'access_token';

    /**
     * @var string
     */
    const ACCESS_TOKEN_SECRET = 'access_token_secret';

    /**
     * Web type
     *
     * @var string
     */
    const WEB_TYPE = 'web';
    /**
     * Validation rule of required device type.
     *
     * @var string
     */
    const DEVICE_TYPE_RULE = 'required|in:android,ios,web';

    /**
     * Validation rule of required if device type is android.
     *
     * @var string
     */
    const RULE_REQUIRED_IF_ANDROID = 'required_if:device_type,android';

    /**
     * Validation rule of required if device type is ios.
     *
     * @var string
     */
    const RULE_REQUIRED_IF_IOS = 'required_if:device_type,ios';


    protected $table = 'user_devices';

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'device_type',
        'device_token'
    ];

    protected $enums = [
        'purchase_type' => DeviceType::class
    ];


    /**
     * Fill the model with an array of attributes.
     * Override fill function
     *
     * @param array $attributes
     * @return $this
     * @access public
     */
    public function fill(array $attributes)
    {
        parent::fill($attributes);

        if (isset($attributes[static::DEVICE_ID])) {
            $this->setDeviceToken($attributes[static::DEVICE_ID]);
        }

        return $this;
    }

    /**
     * Set device token.
     *
     * @param mixed $value
     * @return $this
     */
    public function setDeviceToken($value): static
    {
        $this->device_id = $value;
        $this->hash = static::toHash($value);
        return $this;
    }

    /**
     * Get hash of given device token.
     *
     * @param string $deviceToken
     * @return string
     * @access public
     * @static
     */
    public static function toHash($deviceToken): string
    {
        return sha1($deviceToken);
    }

    /**
     * Find by device token.
     *
     * @param string $deviceToken
     * @return UserDevice
     * @access public
     * @static
     */
    public static function findByDeviceToken($deviceToken): UserDevice
    {
        return static::where('hash', '=', static::toHash($deviceToken))->first();
    }

    /**
     * Get device tokens by given user id.
     *
     * @param int $userID
     * @return array
     * @access public
     * @static
     */
    public static function getByUserID($userID): array
    {
        return static::where('user_id', '=', $userID)->get()->all();
    }

    /**
     * Get device tokens by given user id.
     *
     * @param int $userID
     * @return array
     * @access public
     * @static
     */
    public static function getDevicesForSendingPush($userID): array
    {
        $listDevices = static::getByUserID($userID);
        $listByTypes = [];
        foreach ($listDevices as $device) {
            /* @var UserDevice $device */
            $listByTypes[$device->device_type_id][] = $device->device_token;
        }
        return $listByTypes;
    }

    /**
     * Delete by device token.
     *
     * @param string $deviceToken
     * @return bool|null
     * @access public
     * @static
     */
    public static function deleteByDeviceToken($deviceToken): ?bool
    {
        return static::where('hash', '=', static::toHash($deviceToken))->delete();
    }

}
