<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           CollectionMixin.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     23/04/2021, 4:31 AM
 */

namespace App\Macros;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

/**
 * Class CollectionMixin
 * @package App\Macros
 */
class CollectionMixin
{
    /**
     * @return Closure
     */
    public function paginate()
    {
        return function ($perPage = 20, $page = null, $options = []) {
            $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
            return new LengthAwarePaginator(
                $this->forPage($page, $perPage), $this->count(),
                $perPage,
                $page, $options
            );
        };

    }
}
