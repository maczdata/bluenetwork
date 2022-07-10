<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           Relationships.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     23/04/2021, 4:31 AM
 */

namespace App\Traits\Common;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

trait Relationships
{
	/**
	 * @param $model
	 * @param array $relationships
	 * @return array
	 */
	public function countRelationships($model, array $relationships = [])
	{
		$counter = [];

		foreach ($relationships as $relationship => $text) {
			if (!$c = $model->$relationship()->count()) {
				continue;
			}

			$text = Str::contains($text, '::') ? $text : 'general.' . $text;
			$counter[] = $c . ' ' . strtolower(trans_choice($text, ($c > 1) ? 2 : 1));
		}

		return $counter;
	}

	/**
	 * Mass delete relationships with events being fired.
	 *
	 * @param  $model
	 * @param  $relationships
	 *
	 * @return void
	 */
	public function deleteRelationships($model, $relationships)
	{
		foreach ((array)$relationships as $key => $relationship) {
			if (is_string($key)){
				$newRelationship = $key;
			}else{
				$newRelationship = $relationship;
			}
			if (empty($model->$newRelationship )) {
				continue;
			}

			$items = $model->$newRelationship ->all();

			if ($items instanceof Collection) {
				$items = $items->all();
			}

			foreach ((array)$items as $item) {
				$item->delete();
			}
		}
	}
}
