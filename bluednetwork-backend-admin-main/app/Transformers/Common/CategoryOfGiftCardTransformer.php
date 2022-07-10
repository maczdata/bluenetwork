<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           CategoryOfGiftCardTransformer.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     14/08/2021, 9:46 AM
 */

namespace App\Transformers\Common;

use App\Models\Common\CategoryOfGiftCard;
use App\Models\Common\GiftCardCurrency;
use App\Models\Common\GiftCardCategory;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\NullResource;
use League\Fractal\TransformerAbstract;

/**
 * Class FormOfGiftCardTransformer
 * @package App\Transformers\Common
 */
class CategoryOfGiftCardTransformer extends TransformerAbstract
{
    /*protected $defaultIncludes = [
        'children'
    ];*/

    protected array $availableIncludes = [
        'children'
    ];

    /**
     * @param CategoryOfGiftCard $categoryOfGiftCard
     * @return array
     */
    public function transform(CategoryOfGiftCard $categoryOfGiftCard)
    {
        return [
            'id' => $categoryOfGiftCard->id,
            'title' => $categoryOfGiftCard->title,
        ];
    }

    /**
     * @param CategoryOfGiftCard $categoryOfGiftCard
     * @return NullResource|Collection
     */
    public function includeChildren(CategoryOfGiftCard $categoryOfGiftCard): NullResource|Collection
    {
        return !$categoryOfGiftCard->children->isEmpty()
            ? $this->collection($categoryOfGiftCard->children, new CategoryOfGiftCardTransformer())
            : $this->null();
    }
}
