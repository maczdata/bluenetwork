<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           OrderNumberSequencer.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Services\Generators;



use App\Models\Sales\Order;

/**
 * Class OrderNumberSequencer
 * @package App\Services\Generators
 */
class OrderNumberSequencer implements Sequencer
{
	/**
	 * @inheritDoc
	 */
	public static function generate(): string
	{
		foreach ([
						'Prefix' => 'prefix',
						'Length' => 'length',
						'Suffix' => 'suffix',
					] as
					$varSuffix => $confKey) {
			$var = "orderNumber{$varSuffix}";
			$$var = config('bds.sequencer.order.order_number_' . $confKey) ?: false;

		}
		$lastOrder = Order::query()->orderBy('id', 'desc')->limit(1)->first();
		$lastId = $lastOrder ? $lastOrder->id : 0;

		if ($orderNumberLength && ($orderNumberPrefix || $orderNumberSuffix)) {
			$orderNumber = ($orderNumberPrefix) . sprintf("%0{$orderNumberLength}d",
					0) . ($lastId + 1) . ($orderNumberLength);
		} else {
			$orderNumber = $lastId + 1;
		}

		return $orderNumber;
	}
}
