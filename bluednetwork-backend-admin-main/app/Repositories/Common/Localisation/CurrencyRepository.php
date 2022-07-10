<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           CurrencyRepository.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     24/04/2021, 5:54 AM
 */

namespace App\Repositories\Common\Localisation;

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
