<?php

namespace App\Http\Livewire\Control\Users;

use LivewireUI\Modal\ModalComponent;

class CreateUser extends ModalComponent
{
    public function render()
    {
        return view('control.livewire.users.create');
    }
}
