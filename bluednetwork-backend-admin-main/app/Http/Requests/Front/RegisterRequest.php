<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           RegisterRequest.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Http\Requests\Front;

use App\Abstracts\Http\FormRequest;


class RegisterRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'email' => 'required|email|unique:users,email',
            //'full_name' => 'required'
            'first_name' => 'required',
            'last_name' => 'required',
        ];
        if (!array_key_exists('provider', $this->toArray()) && !array_key_exists('provider_id', $this->toArray())) {
            $rules['password'] = ['required', 'confirmed', 'min:6'];
            $rules['password_confirmation'] = ['required'];
        }
        return $rules;
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'email.required' => 'Email address is required',
            'email.email' => 'Email address is invalid',
            'email.unique' => 'Email address is already in use',
            //'full_name.required' => 'Full name is required',
            'first_name.required' => 'Fist name is required',
            'last_name.required' => 'Last name is required',
            'password.required' => 'Password is required',
            'password_confirmation.required' => 'Confirmation password is required',
            'password_confirmation.confirmed' => 'Please confirm password'
        ];
    }
}
