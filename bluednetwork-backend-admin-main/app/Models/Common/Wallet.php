<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           Wallet.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Models\Common;


use App\Abstracts\BaseModel;
use App\Traits\Transactions\BelongsToTransaction;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class Wallet
 * @package App\Models\Common
 */
class Wallet extends BaseModel
{
   use BelongsToTransaction;

   /**
    * @var string
    */
   protected $table = 'wallets';

   /**
    * @var array
    */
   protected $attributes = [
      'balance' => 0,
   ];

    /**
     * @var string[]
     */
   protected $fillable = [
      'owner_id',
      'owner_type',
      'balance',
   ];
    /**
     * @var string[]
     */
   protected $casts =[
      'balance' => 'float'
   ];

   /**
    * Retrieve owner
    */
   public function owner(): MorphTo
   {
      return $this->morphTo('owner', 'owner_type','owner_id');
   }


   /**
    * Determine if the user can withdraw the given amount
    * @param null $amount
    * @return boolean
    */
   public function canWithdraw($amount = NULL): bool
   {
      return $amount ? abs($this->balance) >= abs($amount) : abs($this->balance) > 0;
   }
}
