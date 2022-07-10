<?php

namespace App\Http\Livewire\Control\AccountLevels;

use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class DeleteAccountLevel extends ModalComponent
{
    public function render()
    {
        return view('control.livewire.account-levels.delete-account-level');
    }
}
