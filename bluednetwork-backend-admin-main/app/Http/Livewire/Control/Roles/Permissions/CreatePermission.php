<?php

namespace App\Http\Livewire\Control\Roles\Permissions;

use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class CreatePermission extends ModalComponent
{
    public function render()
    {
        return view('control.livewire.roles.permissions.create-permission');
    }
}
