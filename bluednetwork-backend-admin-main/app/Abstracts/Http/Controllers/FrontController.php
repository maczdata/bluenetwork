<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           FrontController.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Abstracts\Http\Controllers;

use App\Services\DataSerializer;
use App\Traits\Common\FormError;
use Spatie\Fractal\Facades\Fractal;

/**
 * Class PortalController
 * @package App\Abstracts\Http\Controllers
 */

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Blue Diamond Services",
 *      description="Api for Blue Diamond Services",
 *      @OA\Contact(
 *          email="chistelbrown@gmail.com"
 *      ),
 * )
 *
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="Live host"
 * )
 * @OA\SecurityScheme(
 *   securityScheme="bearerAuth",
 *   type="http",
 *   scheme="bearer",
 *   bearerFormat="JWT"
 * )
 */
class FrontController extends SystemController
{
    use FormError;

    public function __construct()
    {
        parent::__construct();

        Fractal::create([], null, DataSerializer::class);
    }
}
