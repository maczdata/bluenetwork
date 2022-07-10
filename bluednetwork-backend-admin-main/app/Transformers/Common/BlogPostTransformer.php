<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           BlogTransformer.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     01/09/2021, 4:47 AM
 */

namespace App\Transformers\Common;

use App\Models\Common\BlogPost;
use League\Fractal\TransformerAbstract;

class BlogPostTransformer extends TransformerAbstract
{
    /**
     * @param BlogPost $blogPost
     * @return array
     */
    public function transform(BlogPost $blogPost)
    {
        return [
            //'id' => $blogPost->id,
            'title' => $blogPost->title,
            'slug' => $blogPost->slug,
            'summary' => $blogPost->summary,
            'body' => $blogPost->body,
            'published' => $blogPost->published,
        ];
    }
}
