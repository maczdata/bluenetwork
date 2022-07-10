<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           CustomFieldValidator.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 2:40 PM
 */

namespace App\Services;

use App\Models\Common\CustomField;
use Illuminate\Support\Str;
use Illuminate\Validation\Validator;

/**
 * Class CustomFieldValidator
 * @package App\Services
 */
class CustomFieldValidator extends Validator
{
    /**
     * CustomFieldValidator constructor.
     * @param $data
     * @param $rules
     * @param array $messages
     */
    public function __construct($data, $rules, array $messages = [])
    {
        parent::__construct(
            app('translator'),
            $data,
            $rules,
            $messages
        );
    }

    /**
     * @param string $message
     * @param string $value
     * @return array|string|string[]
     */
    protected function replaceAttributePlaceholder($message, $value)
    {
        $fieldId = (int)Str::after($value, 'field ');
        if ($fieldId) {
            $field = CustomField::find($fieldId);
            if ($field) {
                $replacementString = $field->title;
            } else {
                $replacementString = $value;
            }
        } else {
            $replacementString = $value;
        }
        return str_replace(
            [':attribute', ':ATTRIBUTE', ':Attribute'],
            [$replacementString, Str::upper($replacementString), Str::ucfirst($replacementString)],
            $message
        );
    }
}
