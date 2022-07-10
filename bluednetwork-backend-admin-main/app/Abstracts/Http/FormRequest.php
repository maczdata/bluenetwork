<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           FormRequest.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Abstracts\Http;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest as BaseFormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

abstract class FormRequest extends BaseFormRequest
{
   /**
    * Determine if the given offset exists.
    *
    * @param string $offset
    * @return bool
    */
   public function offsetExists($offset)
   {
      return Arr::has(
         $this->route() ? $this->all() + $this->route()->parameters() : $this->all(),
         $offset
      );
   }

    /**
     * Returns validations errors.
     *
     * @param Validator $validator
     * @throws ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        if ($this->expectsJson()) {
            throw new HttpResponseException(response()->json($validator->errors(), 422));
        }
        parent::failedValidation($validator);
    }
}
