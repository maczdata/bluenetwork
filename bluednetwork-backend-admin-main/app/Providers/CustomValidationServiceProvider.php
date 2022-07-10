<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           CustomValidationServiceProvider.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 2:07 PM
 */

namespace App\Providers;

use App\Services\Validation\CustomValidationFactory;
use Illuminate\Validation\ValidationServiceProvider;

/**
 * Class CustomValidationServiceProvider
 *
 * @package App\Providers
 */
class CustomValidationServiceProvider extends ValidationServiceProvider
{
    /**
     * {@inheritDoc}
     *
     * @return void
     */
    public function register()
    {
        $this->registerPresenceVerifier();
        $this->registerUncompromisedVerifier();

        $this->app->singleton('validator', function ($app) {
            $validator = new CustomValidationFactory($app['translator'], $app);

            // The validation presence verifier is responsible for determining the existence
            // of values in a given data collection, typically a relational database or
            // other persistent data stores. And it is used to check for uniqueness.
            if (isset($app['validation.presence'])) {
                $validator->setPresenceVerifier($app['validation.presence']);
            }
            return $validator;
        });
    }

}
