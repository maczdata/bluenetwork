<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           InvoiceRepository.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     23/04/2021, 4:31 AM
 */

namespace App\Repositories\Finance;

use App\Eloquent\Repository;
use App\Models\Finance\Invoice;

/**
 * Class InvoiceRepository
 * @package App\Repositories\Finance
 */
class InvoiceRepository extends Repository
{
	/**
	 * @return string
	 */
	public function model()
	{
		return Invoice::class;
	}
}
