<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           ElectricityException.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Exceptions;


use Exception;

/**
 * Class ElectricityException
 * @package App\Exceptions
 */
class ElectricityException extends Exception
{
    /**
     * @return static
     */
    public static function invalidMeterCredential(): self
    {
        return new static('Meter information invalid');
    }
}
