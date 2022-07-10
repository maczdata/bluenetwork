<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           ReferralBonusRepository.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     23/04/2021, 4:31 AM
 */

namespace App\Repositories\Common;

use App\Eloquent\Repository;
use App\Models\Common\ReferralBonus;

/**
 * Class ReferralBonusRepository
 * @package App\Repositories\Common
 */
class ReferralBonusRepository extends Repository
{
   /**
    * @return string
    */
   public function model()
   {
      return ReferralBonus::class;
   }
}
