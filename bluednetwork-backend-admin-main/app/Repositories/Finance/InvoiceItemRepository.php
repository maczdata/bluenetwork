<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           InvoiceItemRepository.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     23/04/2021, 4:31 AM
 */

namespace App\Repositories\Finance;

use App\Eloquent\Repository;
use App\Models\Finance\InvoiceItem;

/**
 * Class InvoiceItemRepository
 * @package App\Repositories\Finance
 */
class InvoiceItemRepository extends Repository
{
    /**
     * @return string
     */
    public function model()
    {
        return InvoiceItem::class;
    }
}
