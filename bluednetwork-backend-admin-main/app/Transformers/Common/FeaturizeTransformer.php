<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           FeaturizeTransformer.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Transformers\Common;


use App\Models\Common\Featurize;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\NullResource;
use League\Fractal\TransformerAbstract;

/**
 * Class FeaturizeTransformer
 * @package App\Transformers\Common
 */
class FeaturizeTransformer extends TransformerAbstract
{
    protected array $defaultIncludes = [
        'featurize_values'
    ];

    /**
     * @param Featurize $featurize
     * @return array
     */
    public function transform(Featurize $featurize): array
    {
        return [
            'id' => $featurize->id,
            'slug' => $featurize->feature->slug,
            'title' => $featurize->feature->title,
        ];
    }

    /**
     * @param Featurize $featurize
     * @return NullResource|Collection
     */
    public function includeFeaturizeValues(Featurize $featurize): NullResource|Collection
    {
        return !$featurize->featureValues->isEmpty()
            ? $this->collection($featurize->featureValues, new FeaturizeValueTransformer())
            : $this->null();
    }
}
