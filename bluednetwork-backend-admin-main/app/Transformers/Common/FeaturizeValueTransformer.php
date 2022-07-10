<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           FeaturizeValueTransformer.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Transformers\Common;

use App\Models\Common\CustomField;
use App\Models\Common\FeaturizeValue;
use App\Models\Common\ServiceType;
use League\Fractal\TransformerAbstract;

/**
 * Class FeaturizeValueTransformer
 * @package App\Transformers\Common
 */
class FeaturizeValueTransformer extends TransformerAbstract
{

    public function transform(FeaturizeValue $serviceFeatureValue)
    {
        return [
            'id' => $serviceFeatureValue->id,
            'title' => $serviceFeatureValue->title,
            'description' => $serviceFeatureValue->description,
        ];
    }
}
