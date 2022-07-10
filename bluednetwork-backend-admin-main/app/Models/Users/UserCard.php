<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           UserCard.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Models\Users;

use App\Abstracts\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class UserCard
 * @package App\Models\Users
 */
class UserCard extends BaseModel
{
    protected $table = 'user_cards';
    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'enabled',
        'is_default',
        'name',
        'email',
        'last_four_digit',
        'card_brand',
        'card_expire'
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
