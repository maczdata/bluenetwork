<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           EntityVerified.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Events\Common;

use Illuminate\Queue\SerializesModels;

class EntityVerified
{
   use SerializesModels;

   /**
    * @var
    */
   public $tokenable;

   /**
    * UserCreated constructor.
    * @param $tokenable
    */
   public function __construct($tokenable)
   {
      $this->tokenable = $tokenable;
   }
}
