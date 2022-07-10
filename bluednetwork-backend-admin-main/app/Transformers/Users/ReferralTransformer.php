<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           ReferralTransformer.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Transformers\Users;

use App\Models\Common\Referral;
use League\Fractal\TransformerAbstract;

class ReferralTransformer extends TransformerAbstract
{
    protected array $defaultIncludes = [
        'referred'
    ];

    public function transform(Referral $referral)
    {
        return [
            'id' => $referral->id,
            'first_name' => $referral->referred->first_name ?? '',
            'last_name' => $referral->referred->last_name ?? '',
            'created_at' => $referral->created_at->toDateString(),

        ];
    }
}
