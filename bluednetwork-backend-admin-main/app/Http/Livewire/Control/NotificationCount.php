<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           NotificationCount.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     21/08/2021, 1:29 AM
 */

namespace App\Http\Livewire\Control;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;

/**
 * Class NotificationCount
 * @package App\Http\Livewire\Control
 */
final class NotificationCount extends Component
{
    public int $count;

    protected $listeners = [
        'NotificationMarkedAsRead' => 'updateCount',
    ];

    /**
     * @return View
     */
    public function render(): View
    {
        $this->count = \auth('frontend')->user()->unreadNotifications()->count();

        return view('livewire.portal.notification_count', [
            'count' => $this->count,
        ]);
    }

    /**
     * @param int $count
     * @return int
     */
    public function updateCount(int $count): int
    {
        return $count;
    }
}
