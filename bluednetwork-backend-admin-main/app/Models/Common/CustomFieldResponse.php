<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           CustomFieldResponse.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     21/08/2021, 10:30 AM
 */

namespace App\Models\Common;

use App\Abstracts\BaseModel;
use App\Services\DataType\Registry;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Plank\Metable\Exceptions\DataTypeException;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * Class CustomFieldResponse
 *
 * @package App\Models\Common
 */
class CustomFieldResponse extends BaseModel implements HasMedia
{
    use InteractsWithMedia;

    /**
     * @var string
     */
    protected $table = 'custom_field_responses';

    /**
     * @var string[]
     */
    protected $guarded = ['id'];

    protected $fillable = [
        'type',
        'value',
        'custom_field_id',
        'responseable_type',
        'responseable_id',
    ];

    /**
     * Cache of unserialized value.
     *
     * @var mixed
     */
    protected mixed $cachedValue;

    /**
     * @return MorphTo
     */
    public function responseable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return BelongsTo
     */
    public function field(): BelongsTo
    {
        return $this->belongsTo(CustomField::class, 'custom_field_id');
    }

    /**
     * @param $query
     * @param $value
     * @return mixed
     */
    public function scopeHasValue($query, $value): mixed
    {
        return $query->whereNotNull('value');
    }

    /**
     * Accessor for value.
     *
     * Will unserialize the value before returning it.
     *
     * Successive access will be loaded from cache.
     *
     * @return mixed
     * @throws DataTypeException
     */
    public function getValueAttribute(): mixed
    {
        if (!isset($this->cachedValue)) {
            $this->cachedValue = $this->getDataTypeRegistry()
                ->setModel($this)
                ->getHandlerForType($this->type)
                ->unserializeValue($this->attributes['value']);
        }

        return $this->cachedValue;
    }

    /**
     * Mutator for value.
     *
     * The `type` attribute will be automatically updated to match the datatype of the input.
     *
     * @param $value
     * @throws DataTypeException
     */
    public function setValueAttribute($value): void
    {
        $registry = $this->getDataTypeRegistry();
        $registry->setModel($this);
        $this->attributes['type'] = $registry->getTypeForValue($value);
        $this->attributes['value'] = $registry->getHandlerForType($this->type)
            ->serializeValue($value);

        $this->cachedValue = null;
    }

    /**
     * Retrieve the underlying serialized value.
     *
     * @return string
     */
    public function getRawValue(): string
    {
        return $this->attributes['value'];
    }

    /**
     * Load the datatype Registry from the container.
     *
     * @return Registry
     */
    protected function getDataTypeRegistry(): Registry
    {
        return app('bds.datatype.registry');
    }

    /**
     * @return mixed
     */
    public function getValueFriendlyAttribute(): mixed
    {
        if ($this->field->type === 'checkbox') {
            return $this->value ? 'Checked' : 'Unchecked';
        }

        return $this->value;
    }

    /**
     * @param $value
     * @return bool|mixed
     */
    public function formatValue($value)
    {
        // Checkboxes send a default value of "on", so we need to booleanize the value
        if ($this->field->type === 'checkbox') {
            $value = !!$value;
        }

        return $value;
    }
}
