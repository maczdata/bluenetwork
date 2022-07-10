<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           BlogPostRepository.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     01/09/2021, 4:43 AM
 */

namespace App\Repositories\Common;

use App\Eloquent\Repository;
use App\Models\Common\BlogPost;
use Illuminate\Database\Eloquent\Builder;

class BlogPostRepository extends Repository
{
    /**
     * @return string
     */
    public function model()
    {
        return BlogPost::class;
    }


    /**
     * @param $request
     * @return mixed
     */
    public function getBlogPosts($request): mixed
    {
        $request = array_merge([
            'per_page' => 500,
            'use_pagination' => true,
            'keyword' => null,
            'scope' => 'all',
            'type' => 'published',
            //'sort_by' => 'id',
            'sort_order' => 'asc'
        ], $request);

        return $this->scopeQuery(function ($query) use ($request) {
            return $query->select('id', 'slug', 'body', 'title', 'summary', 'published_at', 'created_at', 'updated_at')
                ->when($request['keyword'], function (Builder $query) use ($request) {
                    return $query->whereLike(['summary', 'body'], $request['keyword']);
                })
                ->when($request['type'] == 'published', function (Builder $query) {
                    return $query->published();
                }, function (Builder $query) {
                    return $query->draft();
                })
                ->latest();
        })
            ->paginate();
    }
}
