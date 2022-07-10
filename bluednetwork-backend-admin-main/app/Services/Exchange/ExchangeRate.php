<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           ExchangeRate.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     23/04/2021, 4:31 AM
 */

namespace App\Services\Exchange;

/**
 * Class ExchangeRate
 * @package App\Services\Exchange
 */
abstract class ExchangeRate
{
	abstract public function updateRates();
}
