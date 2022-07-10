<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           StringHandler.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     09/08/2021, 1:50 AM
 */

namespace App\Services\DataType;

/**
 * Handle serialization of strings.
 */
class StringHandler extends ScalarHandler
{
    /**
     * {@inheritdoc}
     */
    protected string $type = 'string';
}
