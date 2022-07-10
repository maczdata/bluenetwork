<?php

namespace App\Http\Controllers\Control\Featurizes;

use App\Abstracts\Http\Controllers\ControlController;
use App\Repositories\Common\FeaturizeRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeaturizeController extends ControlController
{
    public function __construct(
        protected FeaturizeRepository $featurizeRepository
    )
    {
        parent::__construct();
    }

    public function createFeatureMeta(Request $request, string $featurizeId)
    {

        $featurize = $this->featurizeRepository->findByHashidOrFail($featurizeId);
        if (is_null($featurize)) {
            return;
        }
        $featurize->setMeta($request->key, $request->value);
    }

    public function createFeatureValue(Request $request, string $featurizeId)
    {
        DB::beginTransaction();
        try {
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
                foreach ($request->metas as $meta) {
                    $mainServiceFeatureValueSaved->setMeta(
                        $meta['key'],
                        $meta['value']
                    );
                }
            }
            DB::commit();
            flash("Feature value saved successfully")->success();
            return redirect()->back();
        } catch (\Throwable $exception) {
            DB::rollBack();
            logger()->error('Feature value create error : ' . $exception);
            flash('Unable to create value: ' . $exception->getMessage())->error();

            return back();
        }
    }

    public function updateFeatureMeta(Request $request, string $featurizeId)
    {
        $featurize = $this->featurizeRepository->findByHashidOrFail($featurizeId);

        if (isset($request->metas) && count($request->metas) > 0) {
            foreach ($request->metas as $featureMetaKey => $featureMetaValue) {
                $featurize->setMeta($featureMetaKey, $featureMetaValue);
            }
        }
    }

    public function updateFeatureValue(Request $request, string $featurizeId)
    {
        $featurize = $this->featurizeRepository->findByHashidOrFail($featurizeId);
        if (is_null($featurize)) {
            return;
        }
        if (isset($request->values)) {
            foreach ($request->values as $value) {
                $mainServiceFeatureValueSaved = $featurize->featureValues()->create([
                    'title' => $value['title'],
                    'description' => $value['description'],
                    'price' => $value['price'],
                ]);
                if (isset($value['meta']) && count($value['meta']) > 0) {
                    foreach ($value['meta'] as $valueMetaKey => $valueMetaValue) {
                        $mainServiceFeatureValueSaved->setMeta(
                            $valueMetaKey,
                            $valueMetaValue
                        );
                    }
                }
            }
        }
    }
}
