<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           OrderListController.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     23/10/2021, 8:53 AM
 */

namespace App\Http\Controllers\Control\Orders;

use App\Abstracts\Http\Controllers\ControlController;
use App\Models\Common\Service;
use App\Repositories\Finance\OrderRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class OrderListController extends ControlController
{
    public function __construct(protected OrderRepository $orderRepository)
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
     * @param Request $request
     * @return Application|Factory|View
     */
    public function single(Request $request): View|Factory|Application
    {
        $order = $this->orderRepository->findByHashidOrFail($request->order_id);
    
        return \view($this->_config['view'], compact('order'));
    }
}
