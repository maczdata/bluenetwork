<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Resources\Front\ServiceVariantCollection;
use App\Repositories\Common\ServiceVariantRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServiceVariantController extends Controller
{
    public function __construct(protected ServiceVariantRepository $serviceVariantRepository)
    {
    }
    public function index(): JsonResponse
    {
        $serviceVariants = $this->serviceVariantRepository->filterWhenNotPaginated()->get();
        return (new ServiceVariantCollection($serviceVariants))->additional([
            'message' => "List of service variant",
        ])->response();
    }
}
