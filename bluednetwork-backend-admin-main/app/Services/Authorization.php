<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           Authorization.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Services;

use Carbon\Carbon;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

/**
 * Class Authorization
 * @package App\Services
 */
class Authorization
{
    /**
     * @var null
     */
    protected $token;

    /**
     * @var mixed
     */
    protected $payload;
    /**
     * @var
     */
    protected $guard;

    /**
     * Authorization constructor.
     * @param $guard
     * @param null $token
     */
    public function __construct($guard, $token = null)
    {
        $this->token = $token;
        $this->guard = $guard;
    }

    /**
     * @return null
     * @throws Exception
     */
    public function getToken()
    {
        if (!$this->token) {
            throw new Exception('Please set token');
        }

        return $this->token;
    }

    /**
     * @param $token
     * @return $this
     */
    public function setToken($token): static
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getPayload(): mixed
    {
        if (!$this->payload) {
            $this->payload = Auth::guard($this->guard)->setToken($this->getToken())->getPayload();
        }

        return $this->payload;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getExpiredAt(): string
    {
        return Carbon::createFromTimestamp($this->getPayload()->get('exp'))
            ->toDateTimeString();
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getRefreshExpiredAt(): string
    {
        return Carbon::createFromTimestamp($this->getPayload()->get('iat'))
            ->addMinutes(config('jwt.refresh_ttl'))
            ->toDateTimeString();
    }

    /**
     * @return Authenticatable
     * @throws AuthenticationException
     */
    public function user(): Authenticatable
    {
        return Auth::guard($this->guard)->authenticate($this->getToken());
    }

    /**
     * @return array
     * @throws Exception
     */
    public function toArray()
    {
        return [
            'guard' => $this->guard,
            'access_token' => $this->getToken(),
            'token_type' => 'bearer',
            'expired_at' => $this->getExpiredAt(),
            'refresh_expired_at' => $this->getRefreshExpiredAt(),
        ];
    }
}
