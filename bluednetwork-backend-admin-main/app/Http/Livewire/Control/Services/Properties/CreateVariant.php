<?php

namespace App\Http\Livewire\Control\Services\Properties;

use App\Models\Common\Service;
use App\Models\Common\ServiceVariant;
use App\Repositories\Common\ServiceRepository;
use App\Repositories\Common\ServiceVariantRepository;
use LivewireUI\Modal\ModalComponent;

class CreateVariant extends ModalComponent
{
    private ServiceRepository $serviceRepository;
    private ServiceVariantRepository $serviceVariantRepository;
    public $service;
    public $modelType;

    public function mount(string $serviceId, string $modelType)
    {
        if ($modelType === "service") {
            $this->serviceRepository = app(ServiceRepository::class);
            $this->service = $this->serviceRepository->findByHashidOrFail($serviceId);
        } else {
            $this->serviceVariantRepository = app(ServiceVariantRepository::class);
            $this->service = $this->serviceVariantRepository->findByHashidOrFail($serviceId);
        }

        $this->modelType = $modelType;
    }

    public function render()
    {
        return view('control.livewire.services.properties.create-variant');
    }
}
