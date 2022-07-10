<?php

namespace App\Http\Livewire\Control\Services\Properties;

use App\Models\Common\Service;
use App\Models\Common\ServiceVariant;
use App\Repositories\Common\FeaturizeRepository;
use App\Repositories\Common\ServiceRepository;
use App\Repositories\Common\ServiceVariantRepository;
use LivewireUI\Modal\ModalComponent;

class CreateMeta extends ModalComponent
{
    private ServiceRepository $serviceRepository;
    private ServiceVariantRepository $serviceVariantRepository;
    private FeaturizeRepository $featurizeRepository;
    public $service;
    public $modelType;

    public function mount(string $serviceId, string $modelType)
    {
        if ($modelType === "service") {
            $this->serviceRepository = app(ServiceRepository::class);
            $this->service = $this->serviceRepository->findByHashidOrFail($serviceId);
        } elseif ($modelType === "feature") {
            $this->featurizeRepository = app(FeaturizeRepository::class);
            $this->service = $this->featurizeRepository->findByHashidOrFail($serviceId);
        } else {
            $this->serviceVariantRepository = app(ServiceVariantRepository::class);
            $this->service = $this->serviceVariantRepository->findByHashidOrFail($serviceId);
        }

        $this->modelType = $modelType;
    }
    public function render()
    {
        return view('control.livewire.services.properties.create-meta');
    }
}
