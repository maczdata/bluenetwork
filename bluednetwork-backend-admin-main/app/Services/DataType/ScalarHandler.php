<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           ScalarHandler.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     09/08/2021, 1:50 AM
 */

namespace App\Services\DataType;

use App\Contracts\DataTypeInterface;

/**
 * Handle serialization of scalar values.
 */
abstract class ScalarHandler implements DataTypeInterface
{
    /**
     * The name of the scalar data type.
     *
     * @var string
     */
    protected string $type;

    /**
     * {@inheritdoc}
     */
    public function getDataType(): string
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function canHandleValue($value): bool
    {
        return gettype($value) == $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function serializeValue($value): string
    {
        settype($value, 'string');

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function unserializeValue(string $value)
    {
        settype($value, $this->type);

        return $value;
    }
}
