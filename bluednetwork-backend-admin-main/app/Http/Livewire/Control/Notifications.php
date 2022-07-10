<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           Notifications.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     21/08/2021, 1:29 AM
 */

namespace App\Http\Livewire\Control;

//use App\Policies\NotificationPolicy;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Class Notifications
 * @package App\Http\Livewire\Control
 */
final class Notifications extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $notificationId;

    public function render(): View
    {
        return view('livewire.notifications', [
            'notifications' => Auth::user()->unreadNotifications()->paginate(10),
        ]);
    }

    /**
     *
     */
    public function mount(): void
    {
        abort_if(auth('frontend')->guest(), 403);
    }

    /**
     * @return DatabaseNotification
     */
    public function getNotificationProperty(): DatabaseNotification
    {
        return DatabaseNotification::findOrFail($this->notificationId);
    }

    /**
     * @param string $notificationId
     */
    public function markAsRead(string $notificationId): void
    {
        $this->notificationId = $notificationId;

        //$this->authorize(NotificationPolicy::MARK_AS_READ, $this->notification);

        $this->notification->markAsRead();

        $this->emit('NotificationMarkedAsRead', \auth('frontend')->user()->unreadNotifications()->count());
    }
}
