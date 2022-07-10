<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           StateRepository.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     23/04/2021, 4:31 AM
 */

namespace App\Repositories\Common\Localisation;

use App\Eloquent\Repository;
use App\Models\Location\State;

/**
 * Class StateRepository
 * @package App\Repositories\Location
 */
class StateRepository extends Repository
{
   /**
    * @return string
    */
   public function model()
   {
      return State::class;
   }
}
