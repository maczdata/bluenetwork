<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           CustomValidationFactory.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 2:09 PM
 */

namespace App\Services\Validation;

use Illuminate\Validation\Factory;
use Illuminate\Validation\Validator;

/**
 * Class CustomValidationFactory
 *
 * @package App\Services\Validation
 */
class CustomValidationFactory extends Factory
{
    /**
     * @param array $data
     * @param array $rules
     * @param array $messages
     * @param array $customAttributes
     * @return CustomFieldValidator|false|Validator|mixed
     */
    protected function resolve(array $data, array $rules, array $messages, array $customAttributes)
    {
        if (is_null($this->resolver)){
            return new CustomFieldValidator($data, $rules, $messages,$customAttributes);
        }

        return call_user_func($this->resolver, $this->translator, $data, $rules, $messages, $customAttributes);
    }
}
