<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           CustomFieldValidator.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 2:09 PM
 */

namespace App\Services\Validation;

use App\Models\Common\CustomField;
use Illuminate\Support\Str;
use Illuminate\Validation\Validator;

/**
 * Class CustomFieldValidator
 *
 * @package App\Services
 */
class CustomFieldValidator extends Validator
{
    /**
     * CustomFieldValidator constructor.
     *
     * @param $data
     * @param $rules
     * @param array $messages
     * @param array $customAttributes
     */
    public function __construct($data, $rules, array $messages = [], array $customAttributes = [])
    {
        parent::__construct(
            app('translator'),
            $data,
            $rules,
            $messages,
            $customAttributes
        );
    }

    /**
     * @param string $message
     * @param string $value
     * @return array|string
     */
    protected function replaceAttributePlaceholder($message, $value): array|string
    {
        $fieldKey = Str::after($value, 'field ');
        $replacementString = $value;
        if (!is_null($fieldKey)) {
            if (!is_numeric($fieldKey)) {
                $fieldKey = Str::slug($fieldKey,'_');
            }
            $field = CustomField::where(function ($query) use ($fieldKey) {
                $query->orWhere('key', $fieldKey);
                $query->orWhere('id', $fieldKey);
            })->first();

            if ($field) {
                $replacementString = $field->title;
            }
        }
        return str_replace(
            [':attribute', ':ATTRIBUTE', ':Attribute'],
            [$replacementString, Str::upper($replacementString), Str::ucfirst($replacementString)],
            $message
        );
    }
}
