<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Resources\Front\ServiceTypeCollection;
use App\Repositories\Common\ServiceTypeRepository;
use Illuminate\Http\JsonResponse;

class ServiceTypeController extends Controller
{
    public function __construct(protected ServiceTypeRepository $serviceTypeRepository)
    {
    }
    public function index(): JsonResponse
    {
        $serviceTypes = $this->serviceTypeRepository->filterWhenNotPaginated()->get();
        return (new ServiceTypeCollection($serviceTypes))->additional([
            'message' => "List of service types",
        ])->response();
    }
}
