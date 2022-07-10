<?php

namespace Database\Seeders;

use App\Models\Common\CustomField;
use App\Models\Common\Feature;
use App\Models\Common\ServiceType;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (config('bds.bds_service_types') as $key => $service_type) {
            $serviceType = ServiceType::updateOrCreate([
                'title' => $service_type['label'],
            ]);
            if (array_key_exists('services', $service_type)) {
                foreach ($service_type['services'] as $mainServiceKey => $mainService) {
                    $mService = $serviceType->services()->updateOrCreate([
                        'title' => $mainService['label'],
                        'key' => $mainServiceKey,
                        'description' => ($mainService['description']) ?? null,
                        'price' => $child_service['price'] ?? null
                    ]);
                    if (isset($mainService['meta']) && count($mainService['meta']) > 0) {
                        foreach ($mainService['meta'] as $metaKey => $metaValue) {
                            $mService->setMeta($metaKey, $metaValue);
                        }
                    }
                    if (array_key_exists('addons', $mainService)) {
                        if (count($mainService['addons'])) {
                            foreach ($mainService['addons'] as $msAddonKey => $msAddon) {
                                $mService->addons()->updateOrCreate([
                                    'title' => $msAddon['label'],
                                    'description' => $msAddon['description'] ?? null,
                                    'price' => $msAddon['price'] ?? 0
                                ]);
                            }
                        }
                    }
                    if (isset($mainService['features'])) {
                        foreach ($mainService['features'] as $mainServiceFeature) {
                            $feature = Feature::firstOrCreate([
                                'title' => $mainServiceFeature['label']
                            ]);
                            $mServiceFeature = $mService->serviceFeatures()->create([
                                'feature_id' => $feature->id
                            ]);
                            if (isset($mainServiceFeature['meta']) && count($mainServiceFeature['meta']) > 0) {
                                foreach ($mainServiceFeature['meta'] as $mainServiceFeatureMetaKey => $mainServiceFeatureMetaValue) {
                                    $mServiceFeature->setMeta($mainServiceFeatureMetaKey, $mainServiceFeatureMetaValue);
                                }
                            }
                            if (isset($mainServiceFeature['feature_values'])) {
                                foreach ($mainServiceFeature['feature_values'] as $mainServiceFeatureValue) {
                                    $mainServiceFeatureValueSaved = $mServiceFeature->featureValues()->create([
                                        'title' => $mainServiceFeatureValue['label'],
                                        'description' => $mainServiceFeatureValue['description'],
                                        //'price' => $mainServiceFeatureValue['price']
                                    ]);
                                    if (isset($mainServiceFeatureValue['meta']) && count($mainServiceFeatureValue['meta']) > 0) {
                                        foreach ($mainServiceFeatureValue['meta'] as $mainServiceFeatureValueMetaKey => $mainServiceFeatureValueMetaValue) {
                                            $mainServiceFeatureValueSaved->setMeta($mainServiceFeatureValueMetaKey, $mainServiceFeatureValueMetaValue);
                                        }
                                    }
                                }
                            }
                        }
                    }
                    if (isset($mainService['icon']) && file_exists($mainService['icon'])) {
                        $mService->clearMediaCollection('service_image');
                        $mService->addMedia($mainService['icon'])
                            ->preservingOriginal()->toMediaCollection('service_image');
                    }
                    if (array_key_exists('fields', $mainService)) {
                        if (count($mainService['fields'])) {
                            foreach ($mainService['fields'] as $msfKey => $msField) {
                                $mainServiceField = CustomField::updateOrCreate([
                                    'title' => $msField['title'],
                                    'type' => $msField['type']
                                ],[
                                    'required' => $msField['required'],
                                    'validation_rules' => $msField['validation_rules'] ?? ''
                                ]);
                                $mService->attachFields($mainServiceField->id);
                                //->customfields()->updateOrCreate($msField);
                                //$mService->customfields()->updateOrCreate(new CustomField($msField));
                            }
                        }
                    }

                    //$mService->customfields()->save(new CustomField(CustomField::factory()->create()));
                    if (array_key_exists('variants', $mainService)) {
                        foreach ($mainService['variants'] as $variantKey => $variant) {
                            $msVariant = $mService->variants()->updateOrCreate([
                                'title' => $variant['label'],
                                'key' => $variantKey,
                                'price' => (($variant['price']) ?? null),
                                'description' => (($variant['description']) ?? null),
                                'ready_duration' => (($variant['ready_duration']) ?? null),
                            ]);
                            if (isset($variant['meta']) && count($variant['meta']) > 0) {
                                foreach ($variant['meta'] as $metaKey => $metaValue) {
                                    $msVariant->setMeta($metaKey, $metaValue);
                                }
                            }

                            if (array_key_exists('fields', $variant)) {
                                if (count($variant['fields'])) {
                                    foreach ($variant['fields'] as $msfKey => $msVField) {
                                        $mainVField = CustomField::updateOrCreate([
                                            'title' => $msVField['title'],
                                            'type' => $msVField['type']
                                        ], [
                                            'required' => $msVField['required'],
                                            'validation_rules' => $msVField['validation_rules'] ?? ''
                                        ]);
                                        $msVariant->attachFields($mainVField->id);
                                    }
                                }
                            }
                            if (isset($variant['icon']) && file_exists($variant['icon'])) {
                                $msVariant->clearMediaCollection('variant_image');
                                $msVariant->addMedia($variant['icon'])
                                    ->preservingOriginal()->toMediaCollection('variant_image');
                            }

                            if (array_key_exists('addons', $variant)) {
                                if (count($variant['addons'])) {
                                    foreach ($variant['addons'] as $csvAddonKey => $msVAddon) {

                                        $msVariant->addons()->updateOrCreate([
                                            'title' => $msVAddon['label'],
                                            'description' => $msVAddon['description'] ?? null,
                                            'price' => $msVAddon['price'] ?? 0
                                        ]);
                                    }
                                }
                            }
                            if (isset($variant['features'])) {
                                foreach ($variant['features'] as $mainVariantFeature) {
                                    $feature = Feature::firstOrCreate([
                                        'title' => $mainVariantFeature['label']
                                    ]);
                                    $mainVariantServiceFeature = $msVariant->serviceFeatures()->create([
                                        'feature_id' => $feature->id
                                    ]);
                                    if (isset($mainVariantFeature['meta']) && count($mainVariantFeature['meta']) > 0) {
                                        foreach ($mainVariantFeature['meta'] as $mainVariantMetaKey => $mainVariantMetaValue) {
                                            $mainVariantServiceFeature->setMeta($mainVariantMetaKey, $mainVariantMetaValue);
                                        }
                                    }
                                    if (isset($mainVariantFeature['feature_values'])) {
                                        foreach ($mainVariantFeature['feature_values'] as $main_variant_feature_value) {
                                            $mainVariantFeatureValueSaved = $mainVariantServiceFeature->featureValues()->create([
                                                'title' => $main_variant_feature_value['label'],
                                                'description' => $main_variant_feature_value['description'],
                                                //'price' => $child_variant_feature_value['price']
                                            ]);
                                            if (isset($main_variant_feature_value['meta']) && count($main_variant_feature_value['meta']) > 0) {
                                                foreach ($main_variant_feature_value['meta'] as $mainVariantFtVMetaKey => $mainVariantFtVMetaValue) {
                                                    $mainVariantFeatureValueSaved->setMeta($mainVariantFtVMetaKey, $mainVariantFtVMetaValue);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    if (array_key_exists('child_services', $mainService)) {
                        foreach ($mainService['child_services'] as $childServiceKey => $child_service) {
                            $childServiceSaved = $mService->children()->updateOrCreate([
                                'title' => $child_service['label'],
                                'key' => $childServiceKey,
                                'service_type_id' => $serviceType->id,
                                'description' => ($child_service['description']) ?? null,
                                'price' => $child_service['price'] ?? null
                            ]);
                            if (isset($child_service['meta']) && count($child_service['meta']) > 0) {
                                foreach ($child_service['meta'] as $metaKey => $metaValue) {
                                    $childServiceSaved->setMeta($metaKey, $metaValue);
                                }
                            }
                            if (isset($child_service['icon']) && file_exists($child_service['icon'])) {
                                $childServiceSaved->clearMediaCollection('service_image');
                                $childServiceSaved->addMedia($child_service['icon'])
                                    ->preservingOriginal()->toMediaCollection('service_image');
                            }
                            if (array_key_exists('addons', $child_service)) {
                                if (count($child_service['addons'])) {
                                    foreach ($child_service['addons'] as $csAddonKey => $csAddon) {
                                        $childServiceSaved->addons()->updateOrCreate([
                                            'title' => $csAddon['label'],
                                            'description' => $csAddon['description'] ?? null,
                                            'price' => $csAddon['price'] ?? 0
                                        ]);
                                    }
                                }
                            }
                            if (isset($child_service['features']) && count($child_service['features']) > 0) {
                                foreach ($child_service['features'] as $childServiceFeature) {
                                    $feature = Feature::firstOrCreate([
                                        'title' => $childServiceFeature['label']
                                    ]);
                                    $childServiceFeatureSaved = $childServiceSaved->serviceFeatures()->create([
                                        'feature_id' => $feature->id
                                    ]);
                                    if (isset($childServiceFeature['meta']) && count($childServiceFeature['meta']) > 0) {
                                        foreach ($childServiceFeature['meta'] as $childServiceFeatureMetaKey => $childServiceFeatureMetaValue) {
                                            $childServiceFeatureSaved->setMeta($childServiceFeatureMetaKey, $childServiceFeatureMetaValue);
                                        }
                                    }
                                    if (isset($childServiceFeature['feature_values'])) {
                                        foreach ($childServiceFeature['feature_values'] as $childServiceFeatureValue) {
                                            $childServiceFeatureValueSaved = $childServiceFeatureSaved->featureValues()->create([
                                                'title' => $childServiceFeatureValue['label'],
                                                'description' => $childServiceFeatureValue['description'],
                                                //'price' => $childServiceFeatureValue['price']
                                            ]);
                                            if (isset($childServiceFeatureValue['meta']) && count($childServiceFeatureValue['meta']) > 0) {
                                                foreach ($childServiceFeatureValue['meta'] as $childServiceFeatureValueMetaKey => $childServiceFeatureValueMetaValue) {
                                                    $childServiceFeatureValueSaved->setMeta($childServiceFeatureValueMetaKey, $childServiceFeatureValueMetaValue);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            if (array_key_exists('fields', $child_service)) {
                                if (count($child_service['fields'])) {
                                    foreach ($child_service['fields'] as $csfKey => $childServiceField) {
                                        $childServiceSavedField = CustomField::updateOrCreate([
                                            'title' => $childServiceField['title'],
                                            'type' => $childServiceField['type']
                                        ],[
                                            'required' => $childServiceField['required'],
                                            'validation_rules' => $childServiceField['validation_rules'] ?? ''
                                        ]);
                                        $childServiceSaved->attachFields($childServiceSavedField->id);
                                        //$childService->customfields()->updateOrCreate(new CustomField($childServiceField));
                                        //$childServiceSaved->customfields()->updateOrCreate($childServiceField);
                                    }
                                }
                            }
                            if (array_key_exists('variants', $child_service)) {
                                foreach ($child_service['variants'] as $childServiceVariantKey => $childServiceVariant) {
                                    $csVariant = $childServiceSaved->variants()->updateOrCreate([
                                        'title' => $childServiceVariant['label'],
                                        'key' => $childServiceVariantKey,
                                        'price' => (($childServiceVariant['price']) ?? null),
                                        'description' => (($childServiceVariant['description']) ?? null),
                                        'ready_duration' => (($childServiceVariantKey['ready_duration']) ?? null),
                                    ]);
                                    if (isset($childServiceVariant['meta']) && count($childServiceVariant['meta']) > 0) {
                                        foreach ($childServiceVariant['meta'] as $metaKey => $metaValue) {
                                            $csVariant->setMeta($metaKey, $metaValue);
                                        }
                                    }
                                    if (isset($childServiceVariant['img']) && file_exists($childServiceVariant['img'])) {
                                        $csVariant->clearMediaCollection('variant_image');
                                        $csVariant->addMedia($childServiceVariant['img'])
                                            ->preservingOriginal()->toMediaCollection('variant_image');
                                    }
                                    /*if (isset($mainService['meta']) && count($mainService['meta']) > 0) {
                                        foreach ($mainService['meta'] as $metaKey => $metaValue) {
                                            $mService->setMeta($metaKey, $metaValue);
                                        }
                                    }*/
                                    if (array_key_exists('addons', $childServiceVariant)) {
                                        if (count($childServiceVariant['addons'])) {
                                            //var_dump('variant_addon');
                                            foreach ($childServiceVariant['addons'] as $csvAddonKey => $csvAddon) {
                                                var_dump('variant_addon');
                                                $csVariant->addons()->updateOrCreate([
                                                    'title' => $csvAddon['label'],
                                                    'description' => $csvAddon['description'] ?? null,
                                                    'price' => $csvAddon['price'] ?? 0
                                                ]);
                                            }
                                        }
                                    }
                                    if (isset($childServiceVariant['features'])) {
                                        foreach ($childServiceVariant['features'] as $childVariantFeature) {
                                            $feature = Feature::firstOrCreate([
                                                'title' => $childVariantFeature['label']
                                            ]);
                                            $childVariantServiceFeature = $csVariant->serviceFeatures()->create([
                                                'feature_id' => $feature->id
                                            ]);
                                            if (isset($childVariantFeature['meta']) && count($childVariantFeature['meta']) > 0) {
                                                foreach ($childVariantFeature['meta'] as $childVariantMetaKey => $childVariantMetaValue) {
                                                    $childVariantServiceFeature->setMeta($childVariantMetaKey, $childVariantMetaValue);
                                                }
                                            }
                                            if (isset($childVariantFeature['feature_values'])) {
                                                foreach ($childVariantFeature['feature_values'] as $child_variant_feature_value) {
                                                    $childVariantFeatureValueSaved = $childVariantServiceFeature->featureValues()->create([
                                                        'title' => $child_variant_feature_value['label'],
                                                        'description' => $child_variant_feature_value['description'],
                                                        //'price' => $child_variant_feature_value['price']
                                                    ]);
                                                    if (isset($child_variant_feature_value['meta']) && count($child_variant_feature_value['meta']) > 0) {
                                                        foreach ($child_variant_feature_value['meta'] as $childVariantFtVMetaKey => $childVariantFtVMetaValue) {
                                                            $childVariantFeatureValueSaved->setMeta($childVariantFtVMetaKey, $childVariantFtVMetaValue);
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
