<?php

namespace App\Http\Controllers\Front\Settings;

use App\Abstracts\Http\Controllers\FrontController;
use App\Http\Controllers\Controller;
use App\Http\Resources\SettingType\SettingTypesCollection;
use App\Repositories\Common\SettingTypeRepository;
use Illuminate\Http\Request;

class SettingTypeController extends FrontController
{
    public function __construct(
        protected SettingTypeRepository $settingTypeRepository
    )
    {
        parent::__construct();
    }

      /**
     * @OA\Get(
     *      path="/setting-types",
     *      operationId="setting_types",
     *      tags={"Common"},
     *      summary="fetch all setting types and settings",
     *      description="return all setting types and settings",
     *     @OA\Response(response=200,description="fetched all setting types and settings"),
     *     @OA\Response(response=500, description="Bad request"),
     *     )
     * @param Request $request
     * @return JsonResponse
     */

    public function index()
    {
        $settingTypes = $this->settingTypeRepository->filterWhenNotPaginated()->get();
        return (new SettingTypesCollection($settingTypes))->additional([
            'message' => "Setting Types",
        ])->response();
    }
}
