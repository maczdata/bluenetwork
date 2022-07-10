<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           FieldDoesNotBelongToModelException.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Exceptions;

use Exception;

/**
 * Class FieldDoesNotBelongToModelException
 * @package App\Exceptions
 */
class FieldDoesNotBelongToModelException extends Exception
{
    /**
     * FieldDoesNotBelongToModelException constructor.
     * @param $field
     * @param $model
     */
    public function __construct($field, $model)
    {
        $class = get_class($model);

        parent::__construct("Field {$field} does not belong to {$class} with id {$model->id}.");
    }
}
