<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           OrderItemRepository.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     23/04/2021, 4:31 AM
 */

namespace App\Repositories\Finance;

use App\Eloquent\Repository;
use App\Models\Finance\OrderItem;

/**
 * Class OrderItemRepository
 * @package App\Repositories\Finance
 */
class OrderItemRepository extends Repository
{
	/**
	 * @return string
	 */
	public function model()
	{
		return OrderItem::class;
	}


	/**
	 * @param OrderItem $orderItem
	 * @return OrderItem
	 */
	public function collectTotals(OrderItem $orderItem)
	{
		$qtyShipped = $qtyInvoiced = $qtyRefunded = 0;

		$totalInvoiced = $baseTotalInvoiced = 0;
		$taxInvoiced = $baseTaxInvoiced = 0;

		$totalRefunded = $baseTotalRefunded = 0;
		$taxRefunded = $baseTaxRefunded = 0;

		/*foreach ($orderItem->invoice_items as $invoiceItem) {
			$qtyInvoiced += $invoiceItem->qty;

			$totalInvoiced += $invoiceItem->total;
			$baseTotalInvoiced += $invoiceItem->base_total;
		}*/

		$orderItem->qty_invoiced = $qtyInvoiced;
		$orderItem->qty_refunded = $qtyRefunded;

		$orderItem->total_invoiced = $totalInvoiced;

		$orderItem->save();

		return $orderItem;
	}
}
