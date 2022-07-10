<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           WithdrawalSetup.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Models\Finance;

use App\Abstracts\BaseModel;
use App\Models\Common\Bank;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class WithdrawalSetup
 *
 * @package App\Models\Finance
 */
class WithdrawalSetup extends BaseModel
{
    protected $table = 'withdrawal_setups';

    protected $primaryKey = 'id';

    protected $fillable = [
        'bank_id',
        'enabled',
        'provider',
        'provider_value',
        'account_name',
        'account_number',
        'withdrawable_id',
        'withdrawable_type',
        'is_default',
        'recipient'
    ];

    protected $casts = [
        'is_default' => 'boolean'
    ];

    /**
     * @return MorphTo
     */
    public function withdrawable()
    {
        return $this->morphTo();
    }

    /**
     * @return BelongsTo
     */
    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class);
    }
}
