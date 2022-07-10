<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           Sequencer.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     23/04/2021, 4:31 AM
 */

namespace App\Services\Generators;


interface Sequencer
{
    /**
     * create and return the next sequence number for e.g. an order
     *
     * @return string
     */
    public static function generate(): string;
}
