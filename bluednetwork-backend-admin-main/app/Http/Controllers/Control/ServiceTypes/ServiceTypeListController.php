<?php

namespace App\Http\Controllers\Control\ServiceTypes;

use App\Abstracts\Http\Controllers\ControlController;
use App\Http\Controllers\Controller;
use App\Repositories\Common\ServiceTypeRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class ServiceTypeListController extends ControlController
{
    public function __construct(protected ServiceTypeRepository $serviceTypeRepository)
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
}
