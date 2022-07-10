<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           WalletException.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Exceptions;


use Exception;

/**
 * Class WalletException
 * @package App\Exceptions
 */
class WalletException extends Exception
{
    /**
     * @return static
     */
    public static function insufficientBalance(): self
    {
        return new static('You have insufficient wallet balance');
    }
}
