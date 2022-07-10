<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           SendToken.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Events\Common;

use Illuminate\Queue\SerializesModels;

class SendToken
{
    use SerializesModels;

    /**
     * @var
     */
    public $token;

    /**
     * SendToken constructor.
     * @param $token
     */
    public function __construct($token)
    {
        $this->token = $token;
    }
}
