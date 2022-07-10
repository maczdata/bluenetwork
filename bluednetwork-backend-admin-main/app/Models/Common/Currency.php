<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           Currency.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Models\Common;

use App\Abstracts\BaseModel;

class Currency extends BaseModel
{
   /**
    * Sortable columns.
    *
    * @var array
    */
   public $sortable = ['name', 'code', 'rate', 'enabled'];
   /**
    * @var string
    */
   protected $table = 'currencies';


   /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
   protected $fillable = [
      'code',
      'name',
      'symbol',
   ];

   /**
    * Set currency code in capital
    *
    * @param $code
    * @return void
    */
   public function setCodeAttribute($code)
   {
      $this->attributes['code'] = strtoupper($code);
   }

   /**
    * Get the exchange rate associated with the currency.
    */
   public function exchangeRate()
   {
      return $this->hasOne(CurrencyExchangeRate::class, 'target_currency');
   }
}
