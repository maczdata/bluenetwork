<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           SizeTransformer.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Transformers\Common;

use App\Models\Common\Size;
use League\Fractal\TransformerAbstract;

/***
 * Class SizeTransformer
 * @package App\Transformers\Common
 */
class SizeTransformer extends TransformerAbstract
{
    /**
     * @param Size $size
     * @return array
     */
    public function transform(Size $size): array
    {
        return [
            'id' => $size->id,
            'title' => $size->title,
            'slug' => $size->slug
        ];
    }
}
