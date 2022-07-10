<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  bluediamondbackend
 * @file                           Role.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     03/06/2022, 6:02 PM
 */

namespace App\Models\Common;

use Mtvs\EloquentHashids\HasHashid;
use Mtvs\EloquentHashids\HashidRouting;

class Role extends \Spatie\Permission\Models\Role
{
    use HashidRouting;
    use HasHashid;
}
