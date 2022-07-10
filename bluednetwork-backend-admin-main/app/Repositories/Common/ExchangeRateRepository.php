<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           ExchangeRateRepository.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Repositories\Common;

use App\Eloquent\Repository;
use App\Models\Common\CurrencyExchangeRate;

class ExchangeRateRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return CurrencyExchangeRate::class;
    }
}
