<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           FormError.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     30/08/2021, 11:28 AM
 */

namespace App\Traits\Common;

use Illuminate\Contracts\Validation\Validator;

/**
 * Trait FormError
 * @package App\Traits\Common
 */
trait FormError
{
    /**
     * @param $validator
     * @return array
     */
    public function errorFormRequest($validator): array
    {
        $result = [];
        $messages = $validator->errors()->toArray();
        if ($messages) {
            foreach ($messages as $field => $errors) {
                foreach ($errors as $error) {
                    $result[] = [
                        'field' => $field,
                        'code' => $error,
                    ];
                }
            }
        }
        return $result;
    }

    public function failedValidation(Validator $validator)
    {
        $message = $validator->errors()->all();
        $error  = collect($message)->unique()->first();
        return ['status' => 'error', 'data' => $message, 'message' => $error];
    }
}
