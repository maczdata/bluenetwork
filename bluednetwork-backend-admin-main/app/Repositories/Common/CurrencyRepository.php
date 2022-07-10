<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           CurrencyRepository.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Repositories\Common;

use App\Eloquent\Repository;
use App\Models\Common\Currency;

class CurrencyRepository extends Repository{

   public function model()
   {
      return Currency::class;
   }

    /**
     * Specify Model class name
     *
     * @param  int  $id
     * @return bool
     */
    public function delete($id) {
        if ($this->model->count() == 1) {
            return false;
        } else {
            if ($this->model->destroy($id)) {
                return true;
            } else {
                return false;
            }
        }
    }
}
