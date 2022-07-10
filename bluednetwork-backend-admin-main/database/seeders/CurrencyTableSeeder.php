<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  hailatutor
 * @file                           CurrencyTableSeeder.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     20/12/2020, 9:23 AM
 */

namespace Database\Seeders;

use App\Models\Common\Currency;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class CurrencyTableSeeder extends Seeder
{
   public function run()
   {


      $jsonString = file_get_contents(base_path('resources/currency_symbols.json'));

      $currencies = json_decode($jsonString, true);
//dd($currencies);
      foreach ($currencies as $key => $currency) {
         if (Currency::where('code', $key)->first()) {
            continue;
         }
         Currency::create([
            "symbol" => $currency['symbol'],
            "name" => $currency['name'],
            "symbol_native" => $currency['symbol_native'],
            "decimal_digits" => $currency['decimal_digits'],
            "rounding" => $currency['rounding'],
            "code" => $currency['code'],
            "name_plural" => $currency['name_plural']
         ]);
      }
   }
}
