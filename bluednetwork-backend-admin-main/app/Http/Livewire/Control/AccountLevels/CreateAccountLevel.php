<?php

namespace App\Http\Livewire\Control\AccountLevels;

use App\Repositories\Common\AccountLevelRepository;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class CreateAccountLevel extends ModalComponent
{
    protected AccountLevelRepository $accountLevelRepository;

    public function render()
    {
        return view('control.livewire.account-levels.create-account-level');
    }
}
