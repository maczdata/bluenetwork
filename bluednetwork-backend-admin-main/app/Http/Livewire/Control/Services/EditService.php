<?php

namespace App\Http\Livewire\Control\Services;

use App\Models\Common\Service;
use App\Repositories\Common\ServiceRepository;
use App\Repositories\Common\ServiceTypeRepository;
use LivewireUI\Modal\ModalComponent;

class EditService extends ModalComponent
{
    private ServiceRepository $serviceRepository;
    private ServiceTypeRepository $serviceTypeRepository;
    public $serviceTypes;
    public $service;
    public $childService = '0';
    public $services;

    public function mount(Service $service)
    {
        $this->service = $service;
        $this->serviceTypeRepository = app(ServiceTypeRepository::class);
        $this->serviceRepository = app(ServiceRepository::class);
        $this->serviceTypes = $this->serviceTypeRepository->all();
        $this->services = $this->serviceRepository->whereNull('parent_id')->get();
        $this->childService = $service->parent_id ? "1" : "0";
    }

    public function render()
    {
        return view('control.livewire.services.edit');
    }
}
