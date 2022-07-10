<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           HasCustomFields.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     23/08/2021, 2:30 AM
 */

namespace App\Traits\Common;

use Closure;
use ArrayAccess;
use Illuminate\Support\Arr;
use App\Models\Common\CustomField;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as BaseCollection;
use App\Exceptions\FieldDoesNotBelongToModelException;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use App\Exceptions\WrongNumberOfFieldsForOrderingException;
/**
 * Trait HasCustomFields
 *
 * @package App\Traits\Common
 */
trait HasCustomFields
{

    /**
     * Register a saved model event with the dispatcher.
     *
     * @param Closure|string $callback
     *
     * @return void
     */
    //abstract public static function saved($callback);

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param Closure|string $callback
     *
     * @return void
     */
    //abstract public static function deleted($callback);

    /**
     * Define a polymorphic many-to-many relationship.
     *
     * @param string $related
     * @param string $name
     * @param string $table
     * @param string $foreignPivotKey
     * @param string $relatedPivotKey
     * @param string $parentKey
     * @param string $relatedKey
     * @param bool   $inverse
     *
     * @return MorphToMany
     */
    abstract public function morphToMany(
        $related,
        $name,
        $table = null,
        $foreignPivotKey = null,
        $relatedPivotKey = null,
        $parentKey = null,
        $relatedKey = null,
        $inverse = false
    );

    /**
     * @return MorphToMany
     */
    public function customFields(): MorphToMany
    {
        return $this->morphToMany(CustomField::class, 'fieldable', 'custom_fieldables', 'fieldable_id', 'custom_field_id')
            ->withTimestamps();
    }

    /*public function customFields(): MorphMany
    {
        return $this->morphMany(CustomField::class, 'fieldable')
            ->orderBy('order', 'asc');
    }*/

    /**
     * Boot the fieldable trait for the model.
     *
     * @return void
     */
    public static function bootFieldable()
    {
        static::deleted(function (self $model) {
            $model->customFields()->detach();
        });
        self::saving(function (self $model) {
            $lastFieldOnCurrentModel = $model->customFields->orderBy('order', 'desc')->first();
            $model->customFields->order = ($lastFieldOnCurrentModel ? $lastFieldOnCurrentModel->order : 0) + 1;
        });
    }

    /**
     * Attach the given field(s) to the model.
     *
     * @param int|string|array|ArrayAccess|CustomField $fields
     *
     * @return void
     */
    public function setFieldsAttribute($fields): void
    {
        static::saved(function (self $model) use ($fields) {
            $model->syncFields($fields);
        });
    }

    /**
     * Scope query with all the given fields.
     *
     * @param Builder $builder
     * @param mixed $fields
     * @return Builder
     */
    public function scopeWithAllFields(Builder $builder, mixed $fields): Builder
    {
        $fields = $this->prepareFieldIds($fields);

        collect($fields)->each(fn ($category) => $builder->whereHas('customFields', function (Builder $builder) use ($category) {
            return $builder->where('id', $category);
        }));

        return $builder;
    }

    /**
     * Scope query with any of the given fields.
     *
     * @param Builder $builder
     * @param $fields
     * @return Builder
     */
    public function scopeWithAnyFields(Builder $builder, $fields): Builder
    {
        $fields = $this->prepareFieldIds($fields);

        return $builder->whereHas('customFields', function (Builder $builder) use ($fields) {
            $builder->whereIn('id', $fields);
        });
    }

    /**
     * Scope query with any of the given fields.
     *
     * @param Builder $builder
     * @param mixed $fields
     * @return Builder
     */
    public function scopeWithFields(Builder $builder, mixed $fields): Builder
    {
        return static::scopeWithAnyFields($builder, $fields);
    }

    /**
     * Scope query without any of the given fields.
     *
     * @param Builder $builder
     * @param mixed $fields
     * @return Builder
     */
    public function scopeWithoutFields(Builder $builder, mixed $fields): Builder
    {
        $fields = $this->prepareFieldIds($fields);

        return $builder->whereDoesntHave('customFields', function (Builder $builder) use ($fields) {
            $builder->whereIn('id', $fields);
        });
    }

    /**
     * Scope query without any fields.
     *
     * @param Builder $builder
     * @return Builder
     */
    public function scopeWithoutAnyFields(Builder $builder): Builder
    {
        return $builder->doesntHave('customFields');
    }

    /**
     * Determine if the model has any of the given fields.
     *
     * @param mixed $fields
     *
     * @return bool
     */
    public function hasFields(mixed $fields): bool
    {
        $fields = $this->prepareFieldIds($fields);

        return !$this->customFields->pluck('id')->intersect($fields)->isEmpty();
    }

    /**
     * Determine if the model has any the given fields.
     *
     * @param mixed $fields
     *
     * @return bool
     */
    public function hasAnyFields($fields): bool
    {
        return static::hasFields($fields);
    }

    /**
     * Determine if the model has all of the given fields.
     *
     * @param mixed $fields
     *
     * @return bool
     */
    public function hasAllFields(mixed $fields): bool
    {
        $fields = $this->prepareFieldIds($fields);

        return collect($fields)->diff($this->customFields->pluck('id'))->isEmpty();
    }

    /**
     * Sync model fields.
     *
     * @param $fields
     * @param bool $detaching
     * @return $this
     */
    public function syncFields($fields, bool $detaching = true): static
    {
        // Find fields
        $fields = $this->prepareFieldIds($fields);

        // Sync model fields
        $this->customFields()->sync($fields, $detaching);

        return $this;
    }

    /**
     * Attach model fields.
     *
     * @param mixed $fields
     *
     * @return $this
     */
    public function attachFields(mixed $fields): static
    {
        return $this->syncFields($fields, false);
    }

    /**
     * Detach model fields.
     *
     * @param mixed|null $fields
     *
     * @return $this
     */
    public function detachFields(mixed $fields = null): static
    {
        $fields = !is_null($fields) ? $this->prepareFieldIds($fields) : null;

        // Sync model fields
        $this->customFields()->detach($fields);

        return $this;
    }

    /**
     * @param $fields
     * @param array $validationRules
     * @param array $validationMessages
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validateCustomFields($fields, array $validationRules = [], array $validationMessages = []): \Illuminate\Contracts\Validation\Validator
    {
        $customValidationRules = [];
        $keyAdjustedFields = [];
        if ($this->customFields->count()) {
            $customValidationRules = $this->customFields()->whereNull('archived_at')
                ->get()
                ->mapWithKeys(fn ($field) => ['field_' . $field->key => $field->validationRules])->toArray();
         
            $keyAdjustedFields = collect($fields['custom_fields'] ?? [])
                ->mapWithKeys(fn ($field, $key) => ["field_{$key}" => $field])->toArray();
        }
        $newValidationRules = array_merge($customValidationRules, $validationRules);
        $newFields = array_merge($keyAdjustedFields, $fields);

        return Validator::make($newFields, $newValidationRules, $validationMessages);
    }

    /**
     * @param array $request
     * @param array $validationRules
     * @param array $validationMessages
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validateCustomFieldsRequest(array $request = [], array $validationRules = [], array $validationMessages = []): \Illuminate\Contracts\Validation\Validator
    {
        return $this->validateCustomFields($request, $validationRules, $validationMessages);
    }

    /**
     * @param $fields
     * @throws FieldDoesNotBelongToModelException
     * @throws WrongNumberOfFieldsForOrderingException
     */
    public function order($fields)
    {
        // Allows us to pass in either an array or collection
        $fields = collect($fields);

        if ($fields->count() !== $this->customFields()->count()) {
            throw new WrongNumberOfFieldsForOrderingException($fields->count(), $this->customFields()->count());
        }

        $fields->each(function ($id, $index) {
            $customField = $this->customFields()->find($id);

            if (!$customField) {
                throw new FieldDoesNotBelongToModelException($id, $this);
            }

            $customField->update(['order' => $index + 1]);
        });
    }


    /**
     * Prepare field IDs.
     *
     * @param mixed $fields
     * @return array
     */
    protected function prepareFieldIds(mixed $fields): array
    {
        // Convert collection to plain array
        if ($fields instanceof BaseCollection && is_string($fields->first())) {
            $fields = $fields->toArray();
        }

        // Find fields by their ids
        if (is_numeric($fields) || (is_array($fields) && is_numeric(Arr::first($fields)))) {
            return array_map('intval', (array) $fields);
        }

        // Find fields by their slugs
        if (is_string($fields) || (is_array($fields) && is_string(Arr::first($fields)))) {
            $fields = CustomField::whereIn('slug', (array) $fields)->get()->pluck('id');
        }

        if ($fields instanceof Model) {
            return [$fields->getKey()];
        }

        if ($fields instanceof Collection) {
            return $fields->modelKeys();
        }

        if ($fields instanceof BaseCollection) {
            return $fields->toArray();
        }

        return (array) $fields;
    }
}
