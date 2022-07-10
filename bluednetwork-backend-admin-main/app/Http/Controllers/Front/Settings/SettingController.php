<?php

namespace App\Http\Controllers\Front\Settings;

use App\Abstracts\Http\Controllers\FrontController;
use App\Http\Resources\Setting\SettingsCollection;
use App\Repositories\Common\SettingRepository;

class SettingController extends FrontController
{
    public function __construct(
        protected SettingRepository $settingRepository
    )
    {
        parent::__construct();
    }

    public function index()
    {
        $settings = $this->settingRepository->filterWhenNotPaginated()->get();
        return (new SettingsCollection($settings))->additional([
            'message' => "Settings",
        ])->response();
    }
}
