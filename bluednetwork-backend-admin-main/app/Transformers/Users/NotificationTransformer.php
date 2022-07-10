<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           NotificationTransformer.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Transformers\Users;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Notifications\DatabaseNotification;
use League\Fractal\TransformerAbstract;

/**
 * Class NotificationTransformer
 * @package App\Transformers\Users
 */
class NotificationTransformer extends TransformerAbstract
{
    public function transform($notification)
    {
        return [
            'id' => $notification->id,
            'data' => $notification->data,
            'type' => $notification->type,
            'created_at' => $notification->created_at,
            'read_at' => $notification->read_at ? $notification->read_at : null,
        ];
    }
}
