<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           UserOnlineTrait.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     23/04/2021, 4:31 AM
 */

namespace App\Traits\Common;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

/**
 * Trait UserOnlineTrait
 * @package App\Traits\Common
 */
trait UserOnlineTrait
{
    /**
     * @return Collection
     */
    public function allOnline()
    {
        return $this->all()->filter->isOnline();
    }

    /**
     * @return bool
     */
    public function isOnline()
    {
        return Cache::has($this->getCacheKey());
    }

    /**
     * @return Collection
     */
    public function leastRecentOnline()
    {
        return $this->allOnline()
            ->sortBy(fn ($user) => $user->getCachedAt());
    }

    /**
     * @return Collection
     */
    public function mostRecentOnline()
    {
        return $this->allOnline()
            ->sortByDesc(fn ($user) => $user->getCachedAt());
    }

    /**
     * @return int|mixed
     */
    public function getCachedAt()
    {
        if (empty($cache = Cache::get($this->getCacheKey()))) {
            return 0;
        }

        return $cache['cachedAt'];
    }

    /**
     * @param int $seconds
     * @return bool
     */
    public function setCache($seconds = 300)
    {
        $cache = Cache::put(
            $this->getCacheKey(),
            $this->getCacheContent(),
            $seconds
        );
        //if (!is_null($this->last_seen)) {
        $this->update(['last_seen' => now()]);
        //}
        return $cache;
    }

    /**
     * @return array|mixed
     */
    public function getCacheContent()
    {
        if (!empty($cache = Cache::get($this->getCacheKey()))) {
            return $cache;
        }
        $cachedAt = Carbon::now();

        return [
            'cachedAt' => $cachedAt,
            'user' => $this,
        ];
    }

    /**
     *
     */
    public function pullCache()
    {
        Cache::pull($this->getCacheKey());
    }

    /**
     * @return string
     */
    public function getCacheKey()
    {
        return sprintf('%s-%s', 'UserOnline', $this->id);
    }
}
