<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           Token.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class VerificationToken
 * @package App\Models\Common
 */
class Token extends Model
{
   /**
    * @var string
    */
   protected $table = 'tokens';

   /**
    * @var string
    */
   protected $primaryKey = 'id';

   /**
    * @var array
    */
   protected $fillable = [
      'type',
      'token',
      'source'
   ];

   /**
    * @return MorphTo
    */
   public function tokenable()
   {
      return $this->morphTo();
   }


   /**
    * @return bool
    */
   public function tokenExpired()
   {
      if ($this->created_at->addHours(72)->isPast()) {
         return true;
      }
      return false;
   }

}
