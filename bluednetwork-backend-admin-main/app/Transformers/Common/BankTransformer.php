<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           BankTransformer.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Transformers\Common;

use App\Models\Common\Bank;
use League\Fractal\TransformerAbstract;

/**
 * Class BankTransformer
 * @package App\Transformers\Common
 */
class BankTransformer extends TransformerAbstract
{
    /**
     * @param Bank $bank
     * @return array
     */
   public function transform(Bank $bank)
   {
      return [
         'id' => $bank->id,
         'name' => $bank->name,
         'code' => $bank->code
      ];
   }
}
