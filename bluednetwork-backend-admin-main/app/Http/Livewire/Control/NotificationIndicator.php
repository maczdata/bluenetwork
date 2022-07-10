<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           NotificationIndicator.php
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
 * Class NotificationIndicator
 * @package App\Http\Livewire\Control
 */
final class NotificationIndicator extends Component
{
    /**
     * @var
     */
    public $hasNotification;
    /**
     * @var string[]
     */
    protected $listeners = [
        'NotificationMarkedAsRead' => 'setHasNotification',
    ];

    public function render(): View
    {
        $this->hasNotification = $this->setHasNotification(
           \auth('frontend')->user()->unreadNotifications()->count(),
        );

        return view('livewire.portal.notification_indicator', [
            'hasNotification' => $this->hasNotification,
        ]);
    }

    /**
     * @param int $count
     * @return bool
     */
    public function setHasNotification(int $count): bool
    {
        return $count > 0;
    }
}
