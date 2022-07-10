<?php

namespace App\Http\Controllers\Control\Settings;

use App\Abstracts\Http\Controllers\ControlController;
use App\Models\Common\Setting;
use App\Repositories\Common\SettingRepository;
use App\Repositories\Common\SettingTypeRepository;
use Illuminate\Http\Request;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SettingController extends ControlController
{

    public function __construct(
        protected SettingRepository $settingRepository,
        protected SettingTypeRepository $settingTypeRepository
    ) {
        parent::__construct();
    }

    /**
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {
        $settingTypes = $this->settingTypeRepository->get();
        return view($this->_config['view'], compact('settingTypes'));
    }

    public function storeSettingType(Request $request)
    {
        $rules = [
            'name' => 'required|string',
            'enabled' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            flash('Unable to create setting type')->error();
            return back()->withErrors($validator);
        }
        $this->settingTypeRepository->updateOrCreate(['name' => $request->name], $request->all());
        flash('Setting Type created successfully')->success();
        return back();
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string',
            'value' => 'required',
            'setting_type_id' => 'required',
            'data_type' => 'required',
            'enabled' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($this->failedValidation($validator), 422);
        }

        DB::transaction(function () use ($request) {

            $setting = $this->settingRepository->create([
                'enabled' => $request->enabled,
                'name' => $request->name,
                'value' => $request->data_type !== 'file' ? $request->value : null,
                'setting_type_id' => $request->setting_type_id,
                'data_type' => $request->data_type,
                'created_by' => Auth::user()->id,
            ]);

            if (
                $request->data_type === 'file'
                && isset($request->value) && file_exists($request->value)
            ) {
                $setting->clearMediaCollection($setting->slug);
                $setting->addMedia($request->value)
                    ->preservingOriginal()->toMediaCollection($setting->slug);

                if ($request->position) {
                    $setting->setMeta('position', $request->position);
                }

                if ($request->link) {
                    $setting->setMeta('link', $request->link);
                }
            }
        });

        flash('Settings has been saved')->success();
        return back();
    }
}
