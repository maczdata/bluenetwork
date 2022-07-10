<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           ServiceTypeTransformer.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Transformers\Common;
use App\Transformers\Common\ServiceTransformer;
use App\Models\Common\ServiceType;
use League\Fractal\TransformerAbstract;

/**
 * Class ServiceTypeTransformer
 * @package App\Transformers\Common
 */
class ServiceTypeTransformer extends TransformerAbstract
{

    public function transform(ServiceType $serviceType)
    {
        $serviceTransform = new ServiceTransformer();
        return [
            'id' => $serviceType->id,
            'title' => $serviceType->title,
            'slug'  => $serviceType->slug,
            'services' => (!is_null($serviceType->services) ?  $serviceTransform->collect($serviceType->services) : null),
        ];
    }
}
