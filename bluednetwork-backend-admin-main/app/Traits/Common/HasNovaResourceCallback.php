<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           HasNovaResourceCallback.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     23/04/2021, 4:31 AM
 */

namespace App\Traits\Common;

use Laravel\Nova\Http\Requests\NovaRequest;

trait HasNovaResourceCallback
{
    /**
     * @param NovaRequest $request
     * @param $model
     * @return mixed
     */
    public static function fill(NovaRequest $request, $model)
    {
        if (method_exists(static::class, 'beforeSave')) {
            $model::saving(function ($model) use ($request) {
                static::beforeSave($request, $model);
            });
        }

        if (method_exists(static::class, 'beforeCreate')) {
            static::beforeCreate($request, $model);
        }

        if (method_exists(static::class, 'afterSave')) {
            $model::saved(function ($model) use ($request) {
                static::afterSave($request, $model);
            });
        }

        if (method_exists(static::class, 'afterCreate')) {
            $model::saved(function ($model) use ($request) {
                static::afterCreate($request, $model);
            });
        }

        return static::fillFields(
            $request, $model,
            (new static($model))->creationFieldsWithoutReadonly($request)
        );
    }

    /**
     * @param NovaRequest $request
     * @param $model
     * @return mixed
     */
    public static function fillForUpdate(NovaRequest $request, $model)
    {
        if (method_exists(static::class, 'beforeSave')) {
            $model::saving(function ($model) use ($request) {
                static::beforeSave($request, $model);
            });
        }

        if (method_exists(static::class, 'beforeUpdate')) {
            static::beforeUpdate($request, $model);
            //die('ssx');
        }

        if (method_exists(static::class, 'afterSave')) {
            $model::saved(function ($model) use ($request) {
                static::afterSave($request, $model);
            });
        }

        if (method_exists(static::class, 'afterUpdate')) {
            $model::saved(function ($model) use ($request) {
                static::afterUpdate($request, $model);
            });
        }
        return static::fillFields(
            $request, $model,
            (new static($model))->updateFieldsWithoutReadonly($request)
        );
        //die();
    }
}
