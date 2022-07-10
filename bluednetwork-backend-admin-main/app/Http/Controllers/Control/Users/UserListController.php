<?php

namespace App\Http\Controllers\Control\Users;

use App\Abstracts\Http\Controllers\ControlController;
use App\Repositories\Users\UserRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserListController extends ControlController
{
    public function __construct(protected UserRepository $userRepository)
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

   

    public function single(Request $request)
    {
        $user = $this->userRepository->findByHashidOrFail($request->user_id);

        return \view($this->_config['view'], [
            'user' => $user,
            'roles' => Role::all(),
            'permissions' => Permission::all(),
        ]);
    }
}
