<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           ModelHandler.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     22/08/2021, 10:47 PM
 */

namespace App\Services\DataType;

use App\Contracts\DataTypeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * Handle serialization of Eloquent Models.
 */
class ModelHandler implements DataTypeInterface
{
    /**
     * {@inheritdoc}
     */
    public function getDataType(): string
    {
        return 'model';
    }

    /**
     * {@inheritdoc}
     */
    public function canHandleValue($value): bool
    {
        return $value instanceof Model;
    }

    /**
     * {@inheritdoc}
     */
    public function serializeValue($value): string
    {
        //$alias = $value->getMorphClass();
        if ($value->exists) {
            return $value->getMorphClass() . '#' . $value->getKey();
            //return get_class($value) . '#' . $value->getKey();
        }

        return $value->getMorphClass();
        //return get_class($value);
    }

    /**
     * {@inheritdoc}
     */
    public function unserializeValue(string $serializedValue)
    {
        // Return blank instances.
        if (!str_contains($serializedValue, '#')) {
            return Relation::getMorphedModel($serializedValue);
            //return new static::getMorphedModel($serializedValue);
            //return new $serializedValue();
        }
        //$alias = $post->getMorphClass();
        // Fetch specific instances.
        list($classAlias, $id) = explode('#', $serializedValue);
        $class = Relation::getMorphedModel($classAlias);
        return with(new $class())->findOrFail($id);
    }
}
