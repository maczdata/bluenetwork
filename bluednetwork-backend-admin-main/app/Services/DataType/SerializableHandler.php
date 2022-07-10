<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           SerializableHandler.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     09/08/2021, 1:50 AM
 */

namespace App\Services\DataType;

use App\Contracts\DataTypeInterface;
use Serializable;

/**
 * Handle serialization of Serializable objects.
 */
class SerializableHandler implements DataTypeInterface
{
    /**
     * {@inheritdoc}
     */
    public function getDataType(): string
    {
        return 'serializable';
    }

    /**
     * {@inheritdoc}
     */
    public function canHandleValue($value): bool
    {
        return $value instanceof Serializable;
    }

    /**
     * {@inheritdoc}
     */
    public function serializeValue($value): string
    {
        return serialize($value);
    }

    /**
     * {@inheritdoc}
     */
    public function unserializeValue(string $value)
    {
        return unserialize($value);
    }
}
