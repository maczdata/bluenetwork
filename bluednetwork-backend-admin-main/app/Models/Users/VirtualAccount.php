<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  bluediamondbackend
 * @file                           VirtualAccount.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     03/06/2022, 6:02 PM
 */

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VirtualAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        "flw_ref",
        "order_ref",
        "account_number",
        "frequency",
        "bank_name",
        "created_at",
        "expiry_date",
        "note",
        "amount",
    ];

    protected $dates = [
        'expiry_date',
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
