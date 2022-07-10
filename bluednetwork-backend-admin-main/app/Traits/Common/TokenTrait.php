<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           TokenTrait.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     23/04/2021, 4:31 AM
 */

namespace App\Traits\Common;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\Common\Token;

/**
 * Trait TokenTrait
 * @package App\Traits\Common
 */
trait TokenTrait
{
   /**
    * @param Model $model
    * @param $tokenType
    * @param null $source
    * @param null $code
    * @return mixed
    */
   public function createToken(Model $model, $tokenType, $source = null, $code = null)
   {
      $verificationToken = $code ?? Str::random(30);
      $verificationModel = new Token();
      $verificationModel->token = $verificationToken;
      $verificationModel->type = $tokenType;
      $verificationModel->source = $source ?? null;
      return $model->tokens()->save($verificationModel);
   }

}

