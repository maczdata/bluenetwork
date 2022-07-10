<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           VerifyToken.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Events\Common;

use Illuminate\Queue\SerializesModels;

class VerifyToken
{
   use SerializesModels;

   /**
    * @var
    */
   public $tokenable;

   public function __construct($tokenable)
   {
      $this->tokenable = $tokenable;
   }
}
