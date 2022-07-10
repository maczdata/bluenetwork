<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           FileHandler.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     21/08/2021, 10:46 AM
 */

namespace App\Services\DataType;

use App\Contracts\DataTypeInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Handle serialization of plain objects.
 */
class FileHandler implements DataTypeInterface
{
    /**
     * {@inheritdoc}
     */
    public function getDataType(): string
    {
        return 'file';
    }

    /**
     * {@inheritdoc}
     */
    public function canHandleValue($value): bool
    {
        return (($value instanceof UploadedFile && $value->isValid()) || $value instanceof File);
    }

    /**
     * {@inheritdoc}
     */
    public function serializeValue($value): string
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function unserializeValue(string $value)
    {
        //return json_decode($value, false);
    }
}
