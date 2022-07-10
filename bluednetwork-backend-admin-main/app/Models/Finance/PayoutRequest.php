<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           PayoutRequest.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Models\Finance;

use App\Abstracts\BaseModel;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use App\Traits\Transactions\BelongsToTransaction;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class PayoutRequest
 * @package App\Models\Finance
 */
class PayoutRequest extends BaseModel
{
    use BelongsToTransaction;

    /**
     * @var string
     */
    protected $table = 'payout_requests';
    /**
     * @var string
     */
    protected $primaryKey = 'id';
    /**
     * @var string[]
     */
    protected $fillable = [
        'ownerable_type',
        'ownerable_id',
        'withdrawal_setup_id',
        'status',
        'amount',
        'completed',
        'completed_at',
        'final_amount',
        'decline_reason',
        'approved_at',
        'declined_at',
    ];
    /**
     * @var string[]
     */
    protected $casts = [
        'completed_at' => 'datetime',
        'approved_at' => 'datetime',
        'declined_at' => 'datetime'
    ];


    public function ownerable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'ownerable_id');
    }

    /**
     * @return BelongsTo
     */
    public function withdrawal_setup()
    {
        return $this->belongsTo(WithdrawalSetup::class, 'withdrawal_setup_id', 'id');
    }
}
