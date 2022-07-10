<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           WithdrawalSetupRepository.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     23/04/2021, 4:31 AM
 */

namespace App\Repositories\Finance;

use App\Eloquent\Repository;
use App\Models\Finance\WithdrawalSetup;

/**
 * Class WithdrawalSetupRepository
 * @package App\Repositories\Finance
 */
class WithdrawalSetupRepository extends Repository
{
   /**
    * @return string
    */
   public function model()
   {
      return WithdrawalSetup::class;
   }
}
