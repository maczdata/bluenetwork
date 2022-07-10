<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           WrongNumberOfFieldsForOrderingException.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Exceptions;

use Exception;

/**
 * Class WrongNumberOfFieldsForOrderingException
 * @package App\Exceptions
 */
class WrongNumberOfFieldsForOrderingException extends Exception
{
    /**
     * WrongNumberOfFieldsForOrderingException constructor.
     * @param $given
     * @param $expected
     */
    public function __construct($given, $expected)
    {
        parent::__construct("Wrong number of fields passed for ordering. {$given} given, {$expected} expected.");
    }
}
