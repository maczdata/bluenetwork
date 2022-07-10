<?php
namespace App\Repositories\Common;

use App\Eloquent\Repository;
use App\Models\Common\Setting;

/**
 * Class SettingRepository
 * @package App\Repositories\Common
 */
class SettingRepository extends Repository
{
    /**
     * @return string
     */
    public function model()
    {
        return Setting::class;
    }
}
