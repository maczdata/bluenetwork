<?php

namespace App\Http\Controllers\Control\ServiceTypes;

use App\Abstracts\Http\Controllers\ControlController;
use App\Repositories\Common\ServiceTypeRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ManageServiceTypeController extends ControlController
{
    public function __construct(protected ServiceTypeRepository $serviceTypeRepository)
    {
        parent::__construct();
    }

    public function create(Request $request)
    {
        $rules = [
            'title' => 'required|string',
            'enabled' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            flash('Unable to create service type')->error();
            return back()->withErrors($validator);
        }
        $this->serviceTypeRepository->updateOrCreate(['title' => $request->title], $request->all());
        flash('Service Type created successfully')->success();
        return back();
    }

    public function update(Request $request, string $id)
    {
        $serviceType = $this->serviceTypeRepository->findByHashidOrFail($id);

        if (is_null($serviceType)) {
            flash('Unable to update service type')->error();
            return back();
        }
        $this->serviceTypeRepository->update($request->all(), $serviceType->id);
        flash('Service Type updated successfully')->success();
        return back();
    }
}
