<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           ServiceTypeRepository.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 2:40 PM
 */

namespace App\Repositories\Common;

use App\Eloquent\Repository;
use App\Models\Common\ServiceType;

class ServiceTypeRepository extends Repository
{
    public function model()
    {
        return ServiceType::class;
    }
}
