<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           ItemAddonTransformer.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Transformers\Common;

use App\Models\Common\ItemAddon;
use League\Fractal\TransformerAbstract;

/**
 * Class ItemAddonTransformer
 * @package App\Transformers\Common
 */
class ItemAddonTransformer extends TransformerAbstract
{

    /**
     * @param ItemAddon $itemAddon
     * @return array
     */
    public function transform(ItemAddon $itemAddon): array
    {
        return [
            'id' => $itemAddon->id,
            'slug' => $itemAddon->slug,
            'title' => $itemAddon->title,
            'description' => $itemAddon->description,
            'price' => (float)$itemAddon->price,
            'formatted_price' => core()->formatBasePrice($itemAddon->price),
        ];
    }

}
