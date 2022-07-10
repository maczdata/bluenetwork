<?php

namespace App\Repositories\Common;

use App\Eloquent\Repository;
use App\Models\Common\Feature;

/**
 * Class FeatureRepository
 * @package App\Repositories\Common
 */
class FeatureRepository extends Repository
{
    /**
     * @return string
     */
    public function model()
    {
        return Feature::class;
    }
}
