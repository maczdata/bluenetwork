<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           HasCustomFieldResponses.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     21/08/2021, 3:15 PM
 */

namespace App\Traits\Common;

use App\Models\Common\CustomField;
use App\Models\Common\CustomFieldResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Trait HasCustomFieldResponses
 *
 * @package App\Traits\Common
 */
trait HasCustomFieldResponses
{
    private $indexedCustomFieldResponseCollection;

    /**
     * Initialize the trait.
     *
     * @return void
     */
    public static function bootHasCustomFieldResponses()
    {
        // delete all attached customFieldResponses on deletion
        static::deleted(function (self $model) {
            $model->purgecustomFieldResponses();
        });
    }

    /**
     * @return MorphMany
     */
    public function customFieldResponses(): MorphMany
    {
        return $this->morphMany(CustomFieldResponse::class, 'responseable');
    }

    /**
     * Retrieve all meta attached to the model as a key/value map.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAllResponses(): \Illuminate\Support\Collection
    {
        return $this->getCustomFieldResponseCollection()->toBase()->map(function (CustomFieldResponse $response) {
            return $response->getAttribute('value');
        });
    }

    /**
     * @param $fields
     */
    public function saveCustomFields($fields)
    {
        if (empty($fields)) {
            return;
        }
        foreach ($fields as $key => $value) {
            $customField = CustomField::where(function ($query) use ($key) {
                return $query->where('id', $key)->orWhere('key', $key);
            })->first();

            if (!$customField) {
                continue;
            }

            $customFieldResponse = $this->makeCustomFieldResponse($customField->id, $value);
            $savedResponse = $this->customFieldResponses()->save($customFieldResponse);
            if ($savedResponse->type == 'file') {

                if (!is_array($value)) {
                    $savedResponse->addMedia($value)->toMediaCollection($key);
                } else {
                    request()->request->add([$key => $value]);
                    $savedResponse->addMultipleMediaFromRequest([$key])
                        ->each(function ($fileAdder) use ($key) {
                            $fileAdder->toMediaCollection($key);
                        });
                }
                
            }
            $this->customFieldResponses[] = $customFieldResponse;
            $this->indexedCustomFieldResponseCollection[$customField->id] = $customFieldResponse;
        }
    }

    /**
     * Delete all customFieldResponse attached to the model.
     *
     * @return void
     */
    public function purgeCustomFieldResponses(): void
    {
        $this->customFieldResponses()->delete();
        $this->setRelation('customFieldResponses', $this->makeCustomFieldResponse()->newCollection([]));
    }

    /**
     * Add a scope to return only models which match the given field and value.
     *
     * @param Builder $query
     * @param CustomField $field
     * @param $value
     */
    public function scopeWhereField(Builder $query, CustomField $field, $value)
    {
        $query->whereHas('customFieldResponses', function ($query) use ($field, $value) {
            $query
                ->where('custom_field_id', $field->id)
                ->where(function ($subQuery) use ($value) {
                    $subQuery->hasValue($value);
                });
        });
    }


    /**
     * @return mixed
     */
    private function getCustomFieldResponseCollection(): mixed
    {
        if (!$this->relationLoaded('customFieldResponses')) {
            $this->setCustomFieldRelation('customFieldResponses', $this->customFieldResponses()->get());
        }

        return $this->indexedCustomFieldResponseCollection;
    }

    /**
     * @param $relation
     * @param $value
     * @return $this
     */
    public function setCustomFieldRelation($relation, $value): static
    {
        if ($relation == 'customFieldResponses') {
            // keep the response relation indexed by field_id.
            /** @var Collection $value */
            $this->indexedCustomFieldResponseCollection = $value->keyBy('custom_field_id');
        }
        $this->relations[$relation] = $value;

        return $this;
    }

    /**
     * Set the entire relations array on the model.
     *
     * @param array $relations
     * @return $this
     */
    public function setCustomFieldRelations(array $relations): static
    {
        // keep the response relation indexed by field_id.
        if (isset($relations['customFieldResponses'])) {
            $this->indexedCustomFieldResponseCollection =
                (new Collection($relations['customFieldResponses']))->keyBy('custom_field_id');
        } else {
            $this->indexedCustomFieldResponseCollection = $this->makeCustomFieldResponse()->newCollection();
        }

        $this->relations = $relations;

        return $this;
    }

    /**
     * Create a new `CustomFieldResponse` record.
     *
     * @param int|string $customFieldId
     * @param $value
     * @return CustomFieldResponse
     */
    protected function makeCustomFieldResponse(int|string $customFieldId = '', $value = ''): CustomFieldResponse
    {
        $customField = new CustomFieldResponse([
            'custom_field_id' => $customFieldId,
            'value' => $value,
        ]);
        $customField->responseable_type = $this->getMorphClass();
        $customField->responseable_id = $this->getKey();

        return $customField;
    }
}
