<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           Referral.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Models\Common;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Abstracts\BaseModel;

/**
 * Class Referral
 * @package App\Models\Common
 */
class Referral extends BaseModel
{
   /**
    * @var string
    */
   protected $table = 'referrals';
   /**
    * @var string[]
    */
   protected $fillable = [
      'user_id',
      'referral_paid',
      'referral_id',
      'referral_balance',
      'ip_address'
   ];

   /**
    * @return BelongsTo
    */
   public function user(): BelongsTo
   {
      return $this->belongsTo(User::class, 'referral_id', 'id');
   }

   /**
    * @return BelongsTo
    */
   public function referred(): BelongsTo
   {
      return $this->belongsTo(User::class, 'user_id', 'id');
   }

}
