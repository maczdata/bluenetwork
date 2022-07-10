<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           FormOfGiftCardRepository.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     14/08/2021, 7:56 AM
 */

namespace App\Repositories\Common;

use App\Eloquent\Repository;
use App\Models\Common\CategoryOfGiftCard;

/**
 * Class FormOfGiftCardRepository
 * @package App\Repositories\Common
 */
class FormOfGiftCardRepository extends Repository
{
    /**
     * @return string
     */
    public function model()
    {
        return CategoryOfGiftCard::class;
    }
}
