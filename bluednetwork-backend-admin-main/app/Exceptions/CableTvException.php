<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           CableTvException.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Exceptions;


use Exception;

/**
 * Class CableTvException
 * @package App\Exceptions
 */
class CableTvException extends Exception
{
    /**
     * @return static
     */
    public static function invalidSmartCard(): self
    {
        return new static('Invalid smart card number');
    }
}
