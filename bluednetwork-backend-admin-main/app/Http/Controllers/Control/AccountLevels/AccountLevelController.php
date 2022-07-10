<?php

namespace App\Http\Controllers\Control\AccountLevels;

use App\Abstracts\Http\Controllers\ControlController;
use App\Repositories\Common\AccountLevelRepository;
use Illuminate\Contracts\Foundation\Application;
use App\Models\Common\AccountLevel;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AccountLevelController extends ControlController
{
    public function __construct(
        protected AccountLevelRepository $accountLevelRepository
    ) {
        parent::__construct();
    }

    /**
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {
        return view($this->_config['view']);
    }

    public function createAccess()
    {
        return view('control.account-levels.create');
    }

    public function postCreateAccess(Request $request)
    {
        Validator::make($request->all(), [
            "name" => "required",
            "withdrawal_limit" => "required",
            "daily_limit" => "required",
            "transaction_limit" => "required"
        ]);

        DB::beginTransaction();
        try {

            $data = $request->all();
            unset($data['_token']);

            AccountLevel::create($data);
            DB::commit();

            flash('account level created')->success();
            return back();
        } catch (\Exception $e) {
            DB::rollback();
            flash('Something went wrong while processing your request')->error();
           dd($e);
            return back()->withInput();
        }
    }

    public function editAccess($id)
    {
        $accountLevels = AccountLevel::where('id', $id)->first();
        return view('control.account-levels.edit')->with(compact('accountLevels'));
    }

    public function updateAccess(Request $request, $id)
    {
        Validator::make($request->all(), [
            "name" => "required",
            "withdrawal_limit" => "required",
            "daily_limit" => "required",
            "transaction_limit" => "required"
        ]);

        DB::beginTransaction();
        try {

            $data = $request->all();
            unset($data['_token']);

            AccountLevel::where('id', $id)->update($data);
            DB::commit();

            flash('account level updated')->success();
            return back();
        } catch (\Exception $e) {
            DB::rollback();
            flash('Something went wrong while processing your request')->error();
           dd($e);
            return back()->withInput();
        }
    }
}
