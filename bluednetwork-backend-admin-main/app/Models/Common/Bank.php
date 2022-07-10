<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           Bank.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Models\Common;


use App\Abstracts\BaseModel;
use Mtvs\EloquentHashids\HasHashid;
use Mtvs\EloquentHashids\HashidRouting;

/**
 * Class Bank
 *
 * @package App\Models\Common
 */
class Bank extends BaseModel
{
    use HashidRouting, HasHashid;

    protected $table = 'banks';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'code',
        'status'
    ];
}
