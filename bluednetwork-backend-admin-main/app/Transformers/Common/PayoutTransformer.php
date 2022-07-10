<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           PayoutTransformer.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Transformers\Common;

use Illuminate\Support\Collection;
use App\Models\Finance\PayoutRequest;
use League\Fractal\TransformerAbstract;

/**
 * Class PayoutTransformer
 * @package App\Transformers\Common
 */
class PayoutTransformer extends TransformerAbstract
{
    protected array $availableIncludes = [

    ];

    protected array $defaultIncludes = [

    ];
    /**
     * @param PayoutRequest $payoutRequest
     * @return array
     */
    public function transform(PayoutRequest $payoutRequest)
    {
        $statusName = 'unknown';
        if ($payoutRequest->status == 0) {
            $statusName = 'pending';
        } elseif ($payoutRequest->status == 2) {
            $statusName = 'declined';
        } else {
            if ($payoutRequest->status == 1) {
                $statusName = 'approved';
            }
        }
        return [
            'id' => $payoutRequest->id,
            'amount' => $payoutRequest->amount,
            'status' => $payoutRequest->status,
            'status_name' => $statusName,
            'completed' => $payoutRequest->completed,
            'completed_at' => $payoutRequest->completed_at->toDateTimeString(),
            //'owner' => $payoutRequest->ownerable,
            'created_at' => $payoutRequest->created_at->toDateTimeString(),
            'updated_at' => $payoutRequest->updated_at->toDateTimeString()
        ];
    }

    /**
     * @param $collection
     * @return Collection
     */
    public function collect($collection): Collection
    {
        $transformer = new PayoutTransformer();
        return $collection->map(function ($model) use ($transformer) {
            return $transformer->transform($model);
        });
    }


}
