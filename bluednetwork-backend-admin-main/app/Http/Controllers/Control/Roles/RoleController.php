<?php

namespace App\Http\Controllers\Control\Roles;

use App\Abstracts\Http\Controllers\ControlController;
use App\Http\Controllers\Controller;
use App\Repositories\Common\RoleRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class RoleController extends ControlController
{
    public function __construct(protected RoleRepository $roleRepository)
    {
        parent::__construct();
    }

    /**
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {
        return view($this->_config['view']);
    }

    /**
     * @return Application|Factory|View
     */
    public function create(): View|Factory|Application
    {
        return view($this->_config['view'], [
            'permissions' => Permission::get(),
        ]);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $role = $this->roleRepository->create(['name' => str_replace(" ", "_", strtolower($request->name)), 'guard_name' => $request->guard_type]);
            $role->givePermissionTo($request->permissions);

            flash('Role created successfully')->success();
            DB::commit();
        } catch (\Throwable $exception) {
            DB::rollBack();
            flash($exception->getMessage())->error();
        }

        return redirect()->route('control.roles.list');
    }


    public function edit(Request $request)
    {
        $role = $this->roleRepository->findByHashidOrFail($request->role_id);
        return view($this->_config['view'], [
            'role' => $role,
            'permissions' => Permission::get(),
        ]);
    }

    public function update(Request $request)
    {
        $role = $this->roleRepository->findByHashidOrFail($request->role_id);
        $role->permissions()->detach();
        $role->givePermissionTo($request->permissions);

        flash('Permission added to role successfully')->success();
        return back();
    }

    public function createPermission(Request $request)
    {
        $permission = str_replace(" ", "_", strtolower($request->name));
        if (!Permission::where('name', $permission)->exists()) {
            Permission::create([
                'name' => $permission,
                'guard_name' => $request->guard_type,
            ]);
        }

        flash('Permission created successfully')->success();
        return back();
    }
}
