<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           DigitalServices.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Services\ServiceHandler;

use App\Abstracts\Services;
use Illuminate\Http\Request;

/**
 * Class DigitalServices
 *
 * @package App\Services\ServiceHandler
 */
class DigitalServices extends Services
{
    public function getName(): string
    {
        return "ElectricityBill";
    }

    public function getDescription(): string
    {
        return "ElectricityBill";
    }

    public function execute(Request $data)
    {
        // TODO: Implement execute() method.
    }
}
