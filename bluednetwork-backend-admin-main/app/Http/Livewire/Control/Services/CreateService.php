<?php

namespace App\Http\Livewire\Control\Services;

use Livewire\Component;

class CreateService extends Component
{
    public $serviceTypes;
    public $services;
    public $childService = '0';
    
    public function render()
    {
        return view('control.livewire.services.create');
    }
}
