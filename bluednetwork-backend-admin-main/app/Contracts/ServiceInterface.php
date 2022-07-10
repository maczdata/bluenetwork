<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           ServiceInterface.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Contracts;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;

interface ServiceInterface
{
    /**
     * @param array $data
     * @return bool|Validator
     */
    public function validate(array $data = []): Validator|bool;

    /**
     * @param Request $data
     * @return Response
     * @throws RequestException
     */
    public function execute(Request $data);
}
