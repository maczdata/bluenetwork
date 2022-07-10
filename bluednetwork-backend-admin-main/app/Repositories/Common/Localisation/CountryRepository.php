<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           CountryRepository.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     23/04/2021, 4:31 AM
 */

namespace App\Repositories\Common\Localisation;

use App\Eloquent\Repository;
use App\Jobs\Common\Localisation\Country\DeleteCountry;
use App\Jobs\Common\Localisation\Country\UpdateCountry;
use App\Models\Common\Country;
use App\Traits\Common\Jobs;

class CountryRepository extends Repository
{
    use Jobs;
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return Country::class;
    }

    public function updateCountry($country, $data)
    {
        return $this->ajaxDispatch(new UpdateCountry($country, $data));
    }

    public function deleteCountry($country)
    {
        return $this->ajaxDispatch(new DeleteCountry($country));
    }
}
