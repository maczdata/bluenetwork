<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           ServiceTransformer.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     17/08/2021, 1:27 AM
 */

namespace App\Transformers\Common;

use App\Models\Users\VirtualAccount;
use App\Services\Virtual\VirtualAccountService;
use League\Fractal\TransformerAbstract;

/**
 * Class ServiceTransformer
 *
 * @package App\Transformers\Common
 */
class VirtualTransformer extends TransformerAbstract
{
    /**
     * @param VirtualAccount $virtual
     * @return array
     */
    public function transform(VirtualAccount $virtual): array
    {
        $vService = new VirtualAccountService();
        return [
            'data' => $vService->getVirtualAccount($virtual->order_ref),
        ];
    }
}
