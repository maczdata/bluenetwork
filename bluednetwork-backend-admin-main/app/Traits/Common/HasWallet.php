<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           HasWallet.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Traits\Common;

use App\Models\Common\Wallet;

trait HasWallet
{
   /**
    * Retrieve the balance of this user's wallet
    * @return mixed
    */
   public function getWalletBalanceAttribute(): mixed
   {
      return !is_null($this->wallet) ? $this->wallet->refresh()->balance :0;
   }
   /**
    * Retrieve the balance of this user's wallet
    * @return mixed
    */
   public function getBalanceAttribute(): mixed
   {
      return $this->wallet->refresh()->balance;
   }
   /**
    * Retrieve the wallet of this user
    * @return mixed
    */
   public function wallet(): mixed
   {
      return $this->morphOne(Wallet::class, 'owner')->withDefault();
   }
}
