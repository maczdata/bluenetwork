<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           UserCreated.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Events\Users;

use Illuminate\Queue\SerializesModels;

class UserCreated
{
   use SerializesModels;

   public $user;

   public $token;

   /**
    * UserCreated constructor.
    * @param $user
    * @param $token
    */
   public function __construct($user, $token)
   {
      $this->user = $user;

      $this->token = $token;
   }
}
