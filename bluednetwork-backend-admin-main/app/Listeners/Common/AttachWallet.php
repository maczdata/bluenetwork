<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           AttachWallet.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Listeners\Common;

use App\Events\Common\EntityCreated;
use App\Repositories\Common\WalletRepository;

/**
 * Class AttachWallet
 * @package App\Listeners\Users
 */
class AttachWallet
{
    public function __construct(protected WalletRepository $walletRepository)
    {
        $this->walletRepository = $walletRepository;
    }

    /**
     * @param EntityCreated $event
     */
    public function handle(EntityCreated $event)
    {
        $this->walletRepository->walletInstanceOrCreate($event->entity);
    }
}
