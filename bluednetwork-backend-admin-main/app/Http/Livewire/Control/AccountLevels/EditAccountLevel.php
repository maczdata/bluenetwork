<?php

namespace App\Http\Livewire\Control\AccountLevels;

use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class EditAccountLevel extends ModalComponent
{
    public function render()
    {
        return view('control.livewire.account-levels.edit-account-level');
    }
}
