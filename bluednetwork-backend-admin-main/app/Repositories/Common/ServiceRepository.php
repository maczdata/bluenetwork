<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           ServiceRepository.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Repositories\Common;

use App\Eloquent\Repository;
use App\Models\Common\Service;
use App\Repositories\Payout\PayoutRequestRepository;

/**
 * Class ServiceRepository
 * @package App\Repositories\Common
 */
class ServiceRepository extends Repository
{
    /**
     * @return string
     */
    public function model()
    {
        return Service::class;
    }
}
