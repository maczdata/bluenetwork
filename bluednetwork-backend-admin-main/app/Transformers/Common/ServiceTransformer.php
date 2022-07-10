<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           ServiceTransformer.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     17/08/2021, 1:27 AM
 */

namespace App\Transformers\Common;

use App\Models\Common\Service;
use App\Transformers\Common\ServiceVariantTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\NullResource;
use League\Fractal\TransformerAbstract;

/**
 * Class ServiceTransformer
 * @package App\Transformers\Common
 */
class ServiceTransformer extends TransformerAbstract
{
    protected array $availableIncludes = [
        'service_type',
        'parent',
        'children',
        'variants',
        'fields',
        'addons',
        'features',
    ];

    /**
     * @param Service $service
     * @return array
     */
    public function transform(Service $service): array
    {
        $serviceVariantTransform = new ServiceVariantTransformer();
        return [
            'id' => $service->id,
            'title' => $service->title,
            //'service_type_id',
            'key' => $service->key,
            'price' => $service->price ?? 0,
            'formatted_price' => core()->formatBasePrice($service->price ?? 0),
            'description' => $service->description,
            'icon' => $service->service_icon,
            'has_variants' => (bool) $service->variants()->enabled()->count(),
            'has_child_services' => (bool) $service->children()->enabled()->count(),
            'has_features' => (bool) $service->serviceFeatures()->count(),
            'has_addons' => (bool) $service->addons()->count(),
            'meta' => $service->getAllMeta()->except([
                'simhost_dispense_mode',
                'simhost_subscription_sms_content',
                'simhost_sms_receiver',
                'simhost_subscription_code',
            ]),
          
            'variants' => $service->variants()->enabled()->count() != 0 ? $serviceVariantTransform->collect($service->variants()->enabled()->get()) : null,
            'children' => $service->children()->enabled()->count() != 0 ?  $service->children()->enabled()->get() : null,
            /*'meta' => [
                'requires_preview' => $service->getMeta('requires_preview') ?? false
            ]*/
            //'enabled' =>$service->enabled,
        ];
    }

    public function collect($collection)
    {
        $transformer = new ServiceTransformer();
        return collect($collection)->map(function ($model) use ($transformer) {
            return $transformer->transform($model);
        });
    }

    /**
     * @param Service $service
     * @return NullResource|Collection
     */
    public function includeFields(Service $service): NullResource|Collection
    {
        return !$service->customFields->isEmpty()
            ? $this->collection($service->customFields, new CustomFieldTransformer())
            : $this->null();
    }

    /**
     * @param Service $service
     * @return Collection|NullResource
     */
    public function includeVariants(Service $service): NullResource|Collection
    {
        return !$service->variants->isEmpty()
            ? $this->collection($service->variants, new ServiceVariantTransformer())
            : $this->null();
    }

    /**
     * @param Service $service
     * @return Collection|NullResource
     */
    public function includeChildren(Service $service): NullResource|Collection
    {
        return !$service->children->isEmpty()
            ? $this->collection($service->children, new ServiceTransformer())
            : $this->null();
    }

    /**
     * @param Service $service
     * @return Item|NullResource
     */
    public function includeParent(Service $service): NullResource|Item
    {
        return !$service->parent
            ? $this->item($service->parent, new ServiceTransformer())
            : $this->null();
    }

    /**
     * @param Service $service
     * @return Item|NullResource
     */
    public function includeServiceType(Service $service): NullResource|Item
    {
        return !$service->service_type
            ? $this->item($service->service_type, new ServiceTypeTransformer())
            : $this->null();
    }

    /**
     * @param Service $service
     * @return NullResource|Collection
     */
    public function includeAddons(Service $service): NullResource|Collection
    {
        return !$service->addons->isEmpty()
            ? $this->collection($service->addons, new ItemAddonTransformer())
            : $this->null();
    }

    /**
     * @param Service $service
     * @return NullResource|Collection
     */
    public function includeFeatures(Service $service): NullResource|Collection
    {
        return !$service->serviceFeatures->isEmpty()
            ? $this->collection($service->setFeatures, new FeaturizeTransformer())
            : $this->null();
    }
}
