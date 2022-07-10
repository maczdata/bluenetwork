<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           ConnectedAccount.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Models\Common;

use App\Abstracts\BaseModel;
use App\Services\Connected\Credentials;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ConnectedAccount extends BaseModel
{
    use HasTimestamps;

    protected $table = 'connected_accounts';
    /**
     * @var array
     */
    protected $fillable = [
        'provider_name',
        'provider_id',
        'token',
        'secret',
        'refresh_token',
        'expires_at'
    ];

    /**
     * Get the credentials used for authenticating services.
     *
     * @return Credentials
     */
    public function getCredentials(): Credentials
    {
        return new Credentials($this);
    }


    /**
     * @return MorphTo
     */
    public function connectable(): MorphTo
    {
        return $this->morphTo();
    }
}
