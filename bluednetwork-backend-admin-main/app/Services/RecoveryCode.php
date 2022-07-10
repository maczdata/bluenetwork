<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           RecoveryCode.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     23/04/2021, 4:31 AM
 */

namespace App\Services;

use Illuminate\Support\Str;

class RecoveryCode
{
	/**
	 * Generate a new recovery code.
	 *
	 * @return string
	 */
	public static function generate()
	{
		return Str::random(10) . '-' . Str::random(10);
	}
}
