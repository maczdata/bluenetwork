<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           Job.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     20/08/2021, 11:27 PM
 */

namespace App\Abstracts;

use App\Abstracts\Http\FormRequest;
use App\Traits\Common\Jobs;
use App\Traits\Common\Relationships;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Class Job
 * @package App\Abstracts
 */
abstract class Job implements ShouldQueue
{
	use InteractsWithQueue, Jobs, Queueable, Relationships, SerializesModels;

	/**
	 * @param $request
	 * @return FormRequest
	 */
	public function getRequestInstance($request): FormRequest
    {
		if (!is_array($request)) {
			return $request;
		}

		$class = new class() extends FormRequest {
		};

		return $class->merge($request);
	}
}
