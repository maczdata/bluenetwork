<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           Order.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Models\Sales;

use App\Abstracts\BaseModel;
use App\Models\Users\User;
use App\Traits\Common\HasCustomFieldResponses;
use App\Traits\Common\HasCustomFields;
use App\Traits\Transactions\BelongsToTransaction;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * Class Order
 * @package App\Models\Sales
 */
class Order extends BaseModel implements HasMedia
{
    use HasCustomFieldResponses;
    use BelongsToTransaction;
    use InteractsWithMedia;
    use SoftDeletes;

    public const STATUS_PENDING = 'pending';
    public const STATUS_PENDING_PAYMENT = 'pending_payment';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELED = 'canceled';
    public const STATUS_CLOSED = 'closed';
    public const STATUS_FRAUD = 'fraud';
    public const STATUS_FOR_REFUND = 'for_refund';
    public const STATUS_REFUNDED = 'refunded';

    /**
     * @var array|string[]
     */
    protected array $statusLabel = [
        self::STATUS_PENDING => 'Pending',
        self::STATUS_PENDING_PAYMENT => 'Pending Payment',
        self::STATUS_PROCESSING => 'Processing',
        self::STATUS_COMPLETED => 'Completed',
        self::STATUS_CANCELED => 'Canceled',
        self::STATUS_CLOSED => 'Closed',
        self::STATUS_FRAUD => 'Fraud',
        self::STATUS_FOR_REFUND => 'For Refund',
        self::STATUS_REFUNDED => 'Refunded',
    ];

    protected $table = 'orders';

    protected $fillable = [
        'order_number',
        'user_id',
        'status',
        'total_item_count',
        'currency_code',
        'sub_total',
        'grand_total',
    ];

    /**
     * @return MorphTo
     */
    public function orderable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the order items record associated with the order.
     */
    /**
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
