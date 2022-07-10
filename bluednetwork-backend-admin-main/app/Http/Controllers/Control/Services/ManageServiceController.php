<?php

namespace App\Http\Controllers\Control\Services;

use App\Abstracts\Http\Controllers\ControlController;
use App\Models\Common\CustomField;
use App\Models\Common\Feature;
use App\Models\Common\Service;
use App\Models\Common\ServiceType;
use App\Models\Common\ServiceVariant;
use App\Repositories\Common\FeaturizeRepository;
use App\Repositories\Common\GiftCardRepository;
use App\Repositories\Common\Localisation\CurrencyRepository;
use App\Repositories\Common\ServiceRepository;
use App\Repositories\Common\ServiceTypeRepository;
use App\Repositories\Common\ServiceVariantRepository;
use App\Traits\Common\Fillable;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use stdClass;
use Throwable;

class ManageServiceController extends ControlController
{
    use Fillable;

    public function __construct(
        protected ServiceRepository $serviceRepository,
        protected ServiceTypeRepository $serviceTypeRepository,
        protected ServiceVariantRepository $serviceVariantRepository,
        protected FeaturizeRepository $featurizeRepository,
        protected GiftCardRepository $giftCardRepository,
        protected CurrencyRepository $currencyRepository
    ) {
        parent::__construct();
    }


    public function create(): View|Factory|Application
    {
        $serviceTypes = $this->serviceTypeRepository->all();
        $services = $this->serviceRepository->whereNull('parent_id')->get();
        return view($this->_config['view'], [
            'serviceTypes' => $serviceTypes,
            'services' => $services,
        ]);
    }

    public function editServiceVariant(string $serviceId, string $serviceVariantId): View|Factory|Application
    {
        $service = $this->serviceRepository->findByHashidOrFail($serviceId);
        $serviceVariant = $this->serviceVariantRepository->findByHashidOrFail($serviceVariantId);
        return view($this->_config['view'], [
            'service' => $service,
            'serviceVariant' => $serviceVariant,
        ]);
    }

    public function editServiceFeature(string $serviceId, string $featureId, string $type): View|Factory|Application
    {
        if ($type === "service") {
            $service = $this->serviceRepository->findByHashidOrFail($serviceId);
        } else {
            $service = $this->serviceVariantRepository->findByHashidOrFail($serviceId);
        }

        $featurize = $this->featurizeRepository->findByHashidOrFail($featureId);

        return view($this->_config['view'], [
            'service' => $service,
            'featurize' => $featurize,
        ]);
    }

    public function editServiceVariantFeature(
        string $serviceId,
        string $serviceVariantId,
        string $featureId
    ): View|Factory|Application {
        $service = $this->serviceRepository->findByHashidOrFail($serviceId);
        $serviceVariant = $this->serviceVariantRepository->findByHashidOrFail($serviceVariantId);

        $featurize = $this->featurizeRepository->findByHashidOrFail($featureId);

        return view($this->_config['view'], [
            'service' => $service,
            'serviceVariant' => $serviceVariant,
            'featurize' => $featurize,
        ]);
    }

    public function editGiftCard(
        string $serviceId,
        string $giftCardId
    ): View|Factory|Application {
        $service = $this->serviceRepository->findByHashidOrFail($serviceId);
        $giftCard = $this->giftCardRepository->findByHashidOrFail($giftCardId);

        return view($this->_config['view'], [
            'service' => $service,
            'giftCard' => $giftCard,
        ]);
    }

    public function store(Request $request)
    {
        $type = $request->request_type;
        $modelType = $request->model_type;
        if ($type === "meta") {
            return $this->createServiceMeta($request, $modelType);
        }

        if ($type === "field") {
            return $this->createServiceFields($request, $modelType);
        }

        if ($type === "feature") {
            return $this->createServiceFeatures($request, $modelType);
        }

        if ($type === "addon") {
            return $this->createServiceAddons($request, $modelType);
        }

        if ($type === "variant") {
            return $this->storeServiceVariant($request);
        }

        return $this->createService($request);
    }

    public function storeServiceVariant(Request $request)
    {
        $rules = [
            'service_id' => 'required',
            'title' => 'required|string',
            'description' => 'required',
            'price' => 'nullable',
            'ready_duration' => 'nullable',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            flash('Unable to create variant')->error();
            return back()->withErrors($validator);
        }
        $service = $this->serviceRepository->findByHashidOrFail($request->service_id);
        DB::beginTransaction();
        try {
            if (is_null($service)) {
                throw new \Exception('Service not found');
            }

            $serviceVariant = $service->variants()->updateOrCreate([
                'title' => $request->title,
                'price' => $request->price ?? null,
                'description' => $request->description ?? null,
                'ready_duration' => $request->ready_duration ?? null,
            ]);


            if ($request->icon) {
                $this->createServiceVariantIcon($serviceVariant, $request->icon);
            }

            DB::commit();
            flash('Service variant created successfully')->success();
            return redirect()->route('control.service.manage', ['service_id' => $request->service_id]);
        } catch (Throwable $exception) {
            DB::rollBack();
            logger()->error('Service variant create error : ' . $exception);
            flash('Unable to create service variant: ' . $exception->getMessage())->error();

            return back();
        }
    }
    public function updateServiceVariant(Request $request, string $serviceId, string $serviceVariantId)
    {
        DB::beginTransaction();
        try {
            $serviceVariant = $this->serviceVariantRepository->findByHashidOrFail($serviceVariantId);

            if (is_null($serviceVariant)) {
                throw new \Exception('Service not found');
            }

            $this->serviceVariantRepository->update($this->filled($request->toArray()), $serviceVariant->id);

            if ($request->icon) {
                $this->createServiceVariantIcon($serviceVariant, $request->icon);
            }

            DB::commit();
            flash('Service variant updated successfully')->success();
            return redirect()->route('control.service.edit-variant', [
                'service_id' => $serviceId,
                'service_variant_id' => $serviceVariantId,
            ]);
        } catch (Throwable $exception) {
            DB::rollBack();
            logger()->error('Service updated create error : ' . $exception);
            flash('Unable to updated service variant: ' . $exception->getMessage())->error();

            return back();
        }
    }

    public function createService(Request $request)
    {
        $rules = [
            'service_type' => 'required',
            'parent_id' => 'nullable',
            'title' => 'required|string',
            'description' => 'required',
            'price' => 'nullable',
            'enabled' => 'nullable',
            'child_service' => 'required',
            'discount_type' => 'nullable|in:fixed,percentage',
            'discount_value' => 'nullable',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            flash('Unable to create service')->error();
            return back()->withErrors($validator);
        }
        DB::beginTransaction();
        try {
            $serviceType = $this->serviceTypeRepository->findByHashidOrFail($request->service_type);
            if (is_null($serviceType)) {
                throw new \Exception('Service type not found');
            }
            $payload = [
                'title' => $request->title,
                'description' => $request->description ?? null,
                'price' => $request->price ?? null,
                'discount_type' => $request->discount_type ?? null,
                'discount_value' => $request->discount_value ?? null,
                'enabled' => $request->enabled ?? 1,
            ];
            $childService = $request->child_service == '1' ? true : false;

            $parentService = $request->parent_id ? $this->serviceRepository->findByHashidOrFail($request->parent_id) : null;
            if (is_null($parentService) && $childService) {
                throw new \Exception('Kindly select a valid parent service');
            }

            $mService = $this->createNewService($serviceType, $payload, $parentService, $childService);

            $this->createServiceIcon($mService, $request->icon);
            DB::commit();
            flash('Service created successfully')->success();
            return redirect()->route('control.service.list');
        } catch (Throwable $exception) {
            DB::rollBack();
            logger()->error('Service creation error : ' . $exception);
            flash('Unable to create service: ' . $exception->getMessage())->error();
            return back();
        }
    }

    /**
     * @return Application|Factory|View
     */
    public function edit(string $serviceId): View|Factory|Application
    {
        $service = $this->serviceRepository->findByHashidOrFail($serviceId);
        $serviceTypes = $this->serviceTypeRepository->all();
        $services = $this->serviceRepository->whereNull('parent_id')->get();
        $giftCards = $this->giftCardRepository->all();

        return view($this->_config['view'], [
            'serviceTypes' => $serviceTypes,
            'services' => $services,
            'service' => $service,
            'giftCards' => $giftCards,
        ]);
    }

    public function update(Request $request, string $serviceHashId)
    {
        //Validation
        $rules = [
            'service_type' => 'required',
            'parent_id' => 'nullable',
            'title' => 'required|string',
            'description' => 'required',
            'price' => 'nullable',
            'child_service' => 'required',
            'discount_type' => 'nullable|in:fixed,percentage',
            'discount_value' => 'nullable',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            flash('Unable to update service')->error();
            return back()->withErrors($validator);
        }
        DB::beginTransaction();
        try {
            $serviceType = $this->serviceTypeRepository->findByHashidOrFail($request->service_type);
            if (is_null($serviceType)) {
                throw new \Exception('Service type not found');
            }

            $service = $this->serviceRepository->findByHashidOrFail($serviceHashId);
            $payload = $request->except(
                ['service_type', 'parent_id', 'child_service']
            );

            $this->serviceRepository->update($this->filled($payload), $service->id);
            if ($request->parent_id) {
                $parentService = $this->serviceRepository->findByHashidOrFail($request->parent_id);
                $parentService->children()->updateOrCreate($this->filled($payload));
            }
            if ($request->icon) {
                $this->createServiceIcon($service, $request->icon);
            }

            DB::commit();
            flash('Service updated successfully')->success();
            return redirect()->route('control.service.manage', ['service_id' => $serviceHashId]);
        } catch (Throwable $exception) {
            DB::rollBack();
            logger()->error('Service update error : ' . $exception);
            flash('Unable to update service: ' . $exception->getMessage())->error();

            return back();
        }
    }

    public function createServiceFeatureValue(Request $request, string $featurizeId)
    {
        $featurize = $this->featurizeRepository->findByHashidOrFail($featurizeId);
        if (is_null($featurize)) {
            return;
        }

        $mainServiceFeatureValueSaved = $featurize->featureValues()->create([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
        ]);
        if (isset($request->metas) && count($request->metas) > 0) {
            foreach ($request->metas as $valueMetaKey => $valueMetaValue) {
                $mainServiceFeatureValueSaved->setMeta(
                    $valueMetaKey,
                    $valueMetaValue
                );
            }
        }
    }

    private function createNewService(
        ServiceType $serviceType,
        array $payload,
        ?Service $parentService,
        bool $childService
    ): Service {
        if ($parentService && $childService) {
            return $parentService->children()->updateOrCreate([
                'title' => $payload['title'],
                'service_type_id' => $serviceType->id,
                'description' => $payload['description'] ?? null,
                'discount_type' => $payload['discount_type'] ?? null,
                'discount_value' => $payload['discount_value'] ?? null,
                'price' => $payload['price'] ?? null
            ]);
        } elseif (is_null($parentService) && !$childService) {
            return $serviceType->services()->updateOrCreate([
                'title' => $payload['title'],
                'description' => $payload['description'] ?? null,
                'discount_type' => $payload['discount_type'] ?? null,
                'discount_value' => $payload['discount_value'] ?? null,
                'price' => $payload['price'] ?? null
            ]);
        } else {
            return $parentService;
        }
    }

    private function createServiceMeta(Request $request, ?string $type = "service")
    {
        if ($type === "service") {
            $service = $this->serviceRepository->findByHashidOrFail($request->service_id);
        } elseif ($type === "feature") {
            $service = $this->featurizeRepository->findByHashidOrFail($request->service_id);
        } else {
            $service = $this->serviceVariantRepository->findByHashidOrFail($request->service_id);
        }
        $service->setMeta(strtolower($request->key), $request->value);
        flash("Meta added to " .  ucfirst($type) . " successfully")->success();

        return redirect()->back();
    }

    private function createServiceAddOns(Request $request, ?string $type = "service")
    {
        if ($type === "service") {
            $service = $this->serviceRepository->findByHashidOrFail($request->service_id);
        } else {
            $service = $this->serviceVariantRepository->findByHashidOrFail($request->service_id);
        }

        $service->addons()->updateOrCreate([
            'title' => $request->title,
            'description' => $request->description ?? null,
            'price' => $request->price ?? 0,
            'enabled' => $request->enabled,
        ]);
        flash("Addon added to " .  ucfirst($type) . " successfully")->success();
        return redirect()->back();
    }

    private function createServiceFeatures(Request $request, ?string $type = "service")
    {
        DB::beginTransaction();
        try {
            if ($type === "service") {
                $service = $this->serviceRepository->findByHashidOrFail($request->service_id);
            } else {
                $service = $this->serviceVariantRepository->findByHashidOrFail($request->service_id);
            }
            $feature = Feature::firstOrCreate([
                'title' => $request->title,
            ]);
            $mServiceFeature = $service->serviceFeatures()->create([
                'feature_id' => $feature->id,
            ]);

            DB::commit();
            flash("Feature added to " .  ucfirst($type) . " successfully")->success();
            return redirect()->back();
        } catch (Throwable $exception) {
            DB::rollBack();
            logger()->error('Service feature create error : ' . $exception);
            flash('Unable to create service feature: ' . $exception->getMessage())->error();
            return back();
        }
    }

    private function createServiceFeatureMeta(string $featurizeId, $metas)
    {
        $featurize = $this->featurizeRepository->findByHashidOrFail($featurizeId);
        if (isset($metas) && count($metas) > 0) {
            foreach ($metas as $featureMetaKey => $featureMetaValue) {
                $featurize->setMeta($featureMetaKey, $featureMetaValue);
            }
        }
    }

    private function createServiceIcon(Service $service, $icon): void
    {
        if (isset($icon) && file_exists($icon)) {
            $service->clearMediaCollection('service_image');
            $service->addMedia($icon)
                ->preservingOriginal()->toMediaCollection('service_image');
        }
    }

    private function createServiceVariantIcon(ServiceVariant $serviceVariant, $icon): void
    {
        if (isset($icon) && file_exists($icon)) {
            $serviceVariant->clearMediaCollection('variant_image');
            $serviceVariant->addMedia($icon)
                ->preservingOriginal()->toMediaCollection('variant_image');
        }
    }

    private function createServiceFields(Request $request, ?string $type = "service")
    {
        if ($type === "service") {
            $service = $this->serviceRepository->findByHashidOrFail($request->service_id);
        } else {
            $service = $this->serviceVariantRepository->findByHashidOrFail($request->service_id);
        }

        $mainServiceField = CustomField::updateOrCreate([
            'title' => $request->title,
            'type' => $request->type,
        ], [
            'description' => $request->description,
            'required' => $request->required,
            'has_values' => $request->has_values ?? 0,
            'answers' => json_encode($request->answers) ?? json_encode([]),
            'default_value' => $request->default_value ?? "",
            'enabled' => $request->enabled ?? 1,
            'validation_rules' => $request->validation_rules ?? '',
        ]);
        $service->attachFields($mainServiceField->id);

        flash("Field added to " .  ucfirst($type) . " successfully")->success();
        return redirect()->back();
    }
}
