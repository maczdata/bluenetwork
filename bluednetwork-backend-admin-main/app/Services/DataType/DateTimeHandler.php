<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           DateTimeHandler.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     09/08/2021, 1:51 AM
 */

namespace App\Services\DataType;

use App\Contracts\DataTypeInterface;
use Carbon\Carbon;
use DateTimeInterface;

/**
 * Handle serialization of DateTimeInterface objects.
 */
class DateTimeHandler implements DataTypeInterface
{
    /**
     * The date format to use for serializing.
     *
     * @var string
     */
    protected $format = 'Y-m-d H:i:s.uO';

    /**
     * {@inheritdoc}
     */
    public function getDataType(): string
    {
        return 'datetime';
    }

    /**
     * {@inheritdoc}
     */
    public function canHandleValue($value): bool
    {
        return $value instanceof DateTimeInterface;
    }

    /**
     * {@inheritdoc}
     */
    public function serializeValue($value): string
    {
        return $value->format($this->format);
    }

    /**
     * {@inheritdoc}
     */
    public function unserializeValue(string $value)
    {
        return Carbon::createFromFormat($this->format, $value);
    }
}
