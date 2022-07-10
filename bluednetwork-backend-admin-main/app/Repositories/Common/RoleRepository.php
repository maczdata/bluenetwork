<?php
namespace App\Repositories\Common;

use App\Eloquent\Repository;
use App\Models\Common\Role;

/**
 * Class ServiceRepository
 * @package App\Repositories\Common
 */
class RoleRepository extends Repository
{
    /**
     * @return string
     */
    public function model()
    {
        return Role::class;
    }
}
