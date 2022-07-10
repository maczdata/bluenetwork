<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           ObjectHandler.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     09/08/2021, 1:50 AM
 */

namespace App\Services\DataType;

use App\Contracts\DataTypeInterface;

/**
 * Handle serialization of plain objects.
 */
class ObjectHandler implements DataTypeInterface
{
    /**
     * {@inheritdoc}
     */
    public function getDataType(): string
    {
        return 'object';
    }

    /**
     * {@inheritdoc}
     */
    public function canHandleValue($value): bool
    {
        return is_object($value);
    }

    /**
     * {@inheritdoc}
     */
    public function serializeValue($value): string
    {
        return json_encode($value);
    }

    /**
     * {@inheritdoc}
     */
    public function unserializeValue(string $value)
    {
        return json_decode($value, false);
    }
}
