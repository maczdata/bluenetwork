<?php

namespace App\Http\Livewire\Control\Users;

use App\Models\Users\User;
use LivewireUI\Modal\ModalComponent;

class EditUser extends ModalComponent
{
    public User $user;

    public function mount(User $user)
    {
        $this->user = $user;
    }

    public function render()
    {
        return view('control.livewire.users.edit', [
            'user' => $this->user,
        ]);
    }
}
