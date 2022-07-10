<?php

namespace App\Http\Livewire\Control\Services\Featurizes;

use App\Repositories\Common\FeaturizeRepository;
use App\Repositories\Common\ServiceRepository;
use App\Repositories\Common\ServiceVariantRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use LivewireUI\Modal\ModalComponent;

class CreateValue extends ModalComponent
{
    private ServiceRepository $serviceRepository;
    private ServiceVariantRepository $serviceVariantRepository;
    private FeaturizeRepository $featurizeRepository;
    public $service;
    public $featurize;
    public $modelType;
    public $valueMetas = [];
    public $title;
    public $description;

    protected $rules = [
        'valueMetas.*.key' => 'required|string',
        'valueMetas.*.value' => 'required|string',
    ];

    public function mount(string $serviceId, string $featureId, string $modelType)
    {
        if ($modelType === "service-variant") {
            $this->serviceVariantRepository = app(ServiceVariantRepository::class);
            $this->service = $this->serviceVariantRepository->findByHashidOrFail($serviceId);
        } else {
            $this->serviceRepository = app(ServiceRepository::class);
            $this->service = $this->serviceRepository->findByHashidOrFail($serviceId);
        }
        $this->featurizeRepository = app(FeaturizeRepository::class);
        $this->featurize = $this->featurizeRepository->findByHashidOrFail($featureId);

        $this->modelType = $modelType;
    }

    public function removeRow($property, $row)
    {
        if ($property === "meta") {
            unset($this->valueMetas[$row]);
        }
    }

    public function addNewRow($property)
    {
        if ($property === "meta") {
            array_push($this->valueMetas, ["key" => "new_key", "value" => "new_value"]);
        }
    }

    public function createFeatureValue()
    {
        $this->validate();
        DB::beginTransaction();
        try {
            if (is_null($this->featurize)) {
                return;
            }

            $mainServiceFeatureValueSaved = $this->featurize->featureValues()->create([
                'title' => $this->title,
                'description' => $this->description,
            ]);
            if (isset($this->valueMetas) && count($this->valueMetas) > 0) {
                foreach ($this->valueMetas as $meta) {
                    $mainServiceFeatureValueSaved->setMeta(
                        $meta['key'],
                        $meta['value']
                    );
                }
            }
            DB::commit();
            flash("Feature value saved successfully")->success();
            $this->closeModal();
            if ($this->modelType === 'service') {
                return redirect()->route('control.service.edit-feature', [
                    'service_id' => $this->service->hashId(),
                    'feature_id' => $this->featurize->hashId(),
                    'type' => 'service',
                ]);
            } else {
                return redirect(request()->header('Referer'));
            }
        } catch (\Throwable $exception) {
            DB::rollBack();
            logger()->error('Feature value create error : ' . $exception);
            flash('Unable to create value: ' . $exception->getMessage())->error();
            $this->closeModal();
            return back();
        }
    }


    public function render()
    {
        return view('control.livewire.services.create-featurize-value');
    }
}
