<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           CustomFieldTransformer.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Transformers\Common;

use App\Models\Common\CustomField;
use App\Models\Common\ServiceType;
use League\Fractal\TransformerAbstract;

/**
 * Class CustomFieldTransformer
 * @package App\Transformers\Common
 */
class CustomFieldTransformer extends TransformerAbstract
{

    public function transform(CustomField $customField)
    {
        return [
            //'id' => $customField->id,
            'key'=> $customField->key,
            'title' => $customField->title,
            'type' => $customField->type,
            'required' => $customField->required
        ];
    }
}
