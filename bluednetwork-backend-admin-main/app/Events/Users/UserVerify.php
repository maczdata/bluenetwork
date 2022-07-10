<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           UserVerify.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Events\Users;

use Illuminate\Queue\SerializesModels;

class UserVerify
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
