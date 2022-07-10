<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           PaymentException.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     14/08/2021, 8:12 PM
 */

namespace App\Exceptions;


use Exception;

/**
 * Class PaymentException
 * @package App\Exceptions
 */
class PaymentException extends Exception
{
    /**
     * @return static
     */
    public static function invalidTransaction(): self
    {
        return new static('Transaction is invalid');
    }

    /**
     * @return static
     */
    public static function paymentUnverified(): self
    {
        return new static('Payment could not be verified');
    }

    /**
     * @param string $message
     * @return static
     */
    public static function transactionExist(string $message = ''): self
    {
        return new static($message ?? 'Payment already exist');
    }
}
