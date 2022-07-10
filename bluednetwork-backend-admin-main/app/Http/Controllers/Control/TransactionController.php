<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           TransactionController.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/11/2021, 9:04 AM
 */

namespace App\Http\Controllers\Control;

use App\Abstracts\Http\Controllers\ControlController;
use App\Repositories\Common\TransactionRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class TransactionController extends ControlController
{
    public function __construct(protected TransactionRepository $transactionRepository)
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
        $transaction = $this->transactionRepository->findByHashidOrFail($request->transaction_id);

        return \view($this->_config['view'], compact('transaction'));
    }
}
