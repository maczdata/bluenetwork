<?php
namespace App\Repositories\Common;

use App\Eloquent\Repository;
use App\Models\Common\SettingType;

/**
 * Class SettingRepository
 * @package App\Repositories\Common
 */
class SettingTypeRepository extends Repository
{
    /**
     * @return string
     */
    public function model()
    {
        return SettingType::class;
    }
}
