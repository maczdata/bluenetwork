<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           LocaleRepository.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     24/04/2021, 5:55 AM
 */

namespace App\Repositories\Common\Localisation;

use App\Eloquent\Repository;
use App\Models\Common\Locale;

/**
 * Class LocaleRepository
 * @package App\Repositories\Common\Localisation
 */
class LocaleRepository extends Repository
{
	/**
	 * Specify Model class name
	 *
	 * @return mixed
	 */
	function model()
	{
		return Locale::class;
	}
}
