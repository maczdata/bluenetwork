<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           AuthenticationModel.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Abstracts;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;

class AuthenticationModel extends BaseModel implements
   AuthenticatableContract,
   AuthorizableContract,
   CanResetPasswordContract
{
   use Authenticatable, Authorizable, CanResetPassword, MustVerifyEmail;


   /**
    * @return mixed
    */
   public function getJWTIdentifier()
   {
      return $this->getKey();
   }

    /**
     * @return array
     */
   public function getJWTCustomClaims(): array
   {
      return [];
   }
}
