<?php

namespace App\Http\Controllers\Control\Services;

use App\Abstracts\Http\Controllers\ControlController;
use App\Repositories\Common\ServiceRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ServiceListController extends ControlController
{
    public function __construct(protected ServiceRepository $serviceRepository)
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
