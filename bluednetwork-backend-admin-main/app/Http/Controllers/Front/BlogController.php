<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           BlogController.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     01/09/2021, 4:45 AM
 */

namespace App\Http\Controllers\Front;

use App\Abstracts\Http\Controllers\ControlController;
use App\Repositories\Common\BlogPostRepository;
use App\Transformers\Common\BlogPostTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BlogController extends ControlController
{
    /**
     * @param BlogPostRepository $blogPostRepository
     */
    public function __construct(protected BlogPostRepository $blogPostRepository)
    {
        parent::__construct();
    }

    /**
     * @OA\Get(
     *      path="/blog/post/list",
     *      operationId="blog_post_all",
     *      tags={"Blog"},
     *      summary="All blog post",
     *      description="Get blog posts",
     *      @OA\Parameter(
     *          name="keyword",
     *          description="sesrch keyword (optional)",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *       ),
     *     @OA\Response(response=200,description="fetched all blogpost"),
     *     @OA\Response(response=500, description="Bad request"),
     *     )
     *
     *     @OA\Response(response=200,description="fetched all service type  services"),
     *     @OA\Response(response=500, description="Bad request"),
     *     )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        try {
            $blogPosts = $this->blogPostRepository->getBlogPosts($request->toArray());

            return api()->data(fractal($blogPosts, BlogPostTransformer::class)->toArray())->respond();
        } catch (\Exception $ex) {
            logger()->error('Error fetching blog posts : ' . $ex);
            return api()->status(500)->message('Error fetching blog posts')->respond();
        }
    }

    /**
     * @OA\Get(
     *      path="/blog/post/{slug}/single",
     *      operationId="blog_post_single",
     *      tags={"Blog"},
     *      summary="single blog post ",
     *      description="return single service detail",
     *      @OA\Parameter(
     *          name="slug",
     *          description="blog post slug",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *       ),
     *     @OA\Response(response=200,description="fetched blog post"),
     *     @OA\Response(response=500, description="Bad request"),
     *     )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function singleBlogPost(Request $request): JsonResponse
    {
        try {
            $blogPost = $this->blogPostRepository->findOneWhere(['slug' => $request->slug]);
            return api()->status(200)->data(fractal($blogPost, BlogPostTransformer::class)->toArray())->respond();
        } catch (\Exception $ex) {
            logger()->error('Error fetching single blogpost : ' . $ex);
            return api()->status(500)->message('Error getting blog post')->respond();
        }
    }
}
