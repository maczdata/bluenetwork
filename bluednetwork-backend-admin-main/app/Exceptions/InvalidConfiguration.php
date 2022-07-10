<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           InvalidConfiguration.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 2:40 PM
 */

namespace App\Exceptions;

use Exception;

/**
 * Class InvalidConfiguration
 * @package App\Exceptions
 */
class InvalidConfiguration extends Exception
{
    public static function configurationNotSet(): self
    {
        return new static('To send notifications via AfricasTalking you need to add credentials in the `africastalking` key of `config.services`.');
    }
}
