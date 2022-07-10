<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           FieldCollection.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 2:06 PM
 */

namespace App\Collections;

use App\Models\Common\CustomField;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class FieldCollection
 *
 * @package App\Collections
 */
class FieldCollection extends Collection
{

    public function onlyImages()
    {
        $fileFields = $this->where('type', 'file');

        return $fileFields->filter(function ($field) {

            //return $field->responses->count() && $file->isImage() && is_resizable($file);
        });
    }

    /**
     * Returns only those fields that match a particular type.
     *
     * @param string $type
     * @return FieldCollection
     */
    public function ofType(string $type)
    {
        return $this->filter(fn(CustomField $field) => $field->type === $type);
    }

    /**
     * Retrieves an array of stringified slugs
     *
     * @return array
     */
    public function slugs() : array
    {
        return $this->map(fn(CustomField $field) => (string) $field->slug)->toArray();
    }
}
