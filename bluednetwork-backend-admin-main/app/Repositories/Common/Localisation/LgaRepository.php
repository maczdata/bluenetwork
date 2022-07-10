<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           LgaRepository.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     23/04/2021, 4:31 AM
 */

namespace App\Repositories\Common\Localisation;

use App\Eloquent\Repository;
use App\Models\Location\Lga;

/**
 * Class LgaRepository
 * @package App\Repositories\Common\Localisation
 */
class LgaRepository extends Repository
{
   /**
    * @return string
    */
   public function model()
   {
      return Lga::class;
   }
}
