<?php

namespace App\Repositories\Common;

use App\Eloquent\Repository;
use App\Models\Common\AccountLevel;

class AccountLevelRepository extends Repository
{
    /**
     * @return string
     */
    public function model()
    {
        return AccountLevel::class;
    }
}
