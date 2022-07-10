<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           ServiceVariantTransformer.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Transformers\Common;

use App\Models\Common\ServiceVariant;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\NullResource;
use League\Fractal\TransformerAbstract;

/**
 * Class ServiceVariantTransformer
 *
 * @package App\Transformers\Common
 */
class ServiceVariantTransformer extends TransformerAbstract
{
    protected array $availableIncludes = [
        'service',
        'parent',
        'children',
        'fields',
        'addons',
        'features'
    ];

    /**
     * @param ServiceVariant $serviceVariant
     * @return array
     */
    public function transform(ServiceVariant $serviceVariant): array
    {
        return [
            'id' => $serviceVariant->id,
            'title' => $serviceVariant->title,
            'key' => $serviceVariant->key,
            'price' => $serviceVariant->price ?? 0,
            'formatted_price' => core()->formatBasePrice($serviceVariant->price ?? 0),
            'description' => $serviceVariant->description,
            'ready_duration' => $serviceVariant->ready_duration,
            'icon' => $serviceVariant->service_variant_icon,
            'has_child_variants' => (bool)$serviceVariant->children()->enabled()->count(),
            'meta' => $serviceVariant->getAllMeta()->except([
                'simhost_dispense_mode',
                'simhost_subscription_sms_content',
                'simhost_sms_receiver',
                'simhost_subscription_code'
            ])
            //'enabled' =>$serviceVariant->enabled,
        ];
    }

    public function collect($collection)
    {
        $transformer = new ServiceVariantTransformer();
        return collect($collection)->map(function ($model) use ($transformer) {
            return $transformer->transform($model);
        });
    }

    /**
     * @param ServiceVariant $serviceVariant
     * @return NullResource|Collection
     */
    public function includeFields(ServiceVariant $serviceVariant): NullResource|Collection
    {
        return !$serviceVariant->customFields->isEmpty()
            ? $this->collection($serviceVariant->customFields, new CustomFieldTransformer())
            : $this->null();
    }

    /**
     * @param ServiceVariant $serviceVariant
     * @return Collection|NullResource
     */
    public function includeChildren(ServiceVariant $serviceVariant): NullResource|Collection
    {
        return !$serviceVariant->children->isEmpty()
            ? $this->collection($serviceVariant->children, new ServiceVariantTransformer())
            : $this->null();
    }

    /**
     * @param ServiceVariant $serviceVariant
     * @return Item|NullResource
     */
    public function includeParent(ServiceVariant $serviceVariant): NullResource|Item
    {
        return !$serviceVariant->parent
            ? $this->item($serviceVariant->parent, new ServiceVariantTransformer())
            : $this->null();
    }

    /**
     * @param ServiceVariant $serviceVariant
     * @return Item|NullResource
     */
    public function includeService(ServiceVariant $serviceVariant): NullResource|Item
    {
        return !$serviceVariant->service
            ? $this->item($serviceVariant->service, new ServiceTransformer())
            : $this->null();
    }

    /**
     * @param ServiceVariant $serviceVariant
     * @return NullResource|Collection
     */
    public function includeAddons(ServiceVariant $serviceVariant): NullResource|Collection
    {
        return !$serviceVariant->addons->isEmpty()
            ? $this->collection($serviceVariant->addons, new ItemAddonTransformer())
            : $this->null();
    }

    /**
     * @param ServiceVariant $serviceVariant
     * @return NullResource|Collection
     */
    public function includeFeatures(ServiceVariant $serviceVariant): NullResource|Collection
    {
        return !$serviceVariant->serviceFeatures->isEmpty()
            ? $this->collection($serviceVariant->setFeatures, new FeaturizeTransformer())
            : $this->null();
    }
}
