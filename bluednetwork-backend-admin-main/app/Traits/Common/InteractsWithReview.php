<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           InteractsWithReview.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     23/04/2021, 4:31 AM
 */

namespace App\Traits\Common;


use App\Models\Common\Review;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;

trait InteractsWithReview
{
	/**
	 * Get all reviews of this model.
	 *
	 * @return MorphMany
	 */
	public function reviews()
	{
		return $this->morphMany(Review::class, 'reviewable');
	}

	/**
	 * Get the summarized score value.
	 *
	 * @return Collection
	 */
	public function getScoreAttribute()
	{
		return $this->reviews()->sum('score');
	}

	/**
	 * Get the average score value.
	 *
	 * @return int
	 */
	public function getAvgScoreAttribute()
	{
		return $this->reviews()->avg('score');
	}
}
