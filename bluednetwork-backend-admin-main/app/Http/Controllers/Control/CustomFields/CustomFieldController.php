<?php

namespace App\Http\Controllers\Control\CustomFields;

use App\Abstracts\Http\Controllers\ControlController;
use App\Http\Controllers\Controller;
use App\Models\Common\CustomField;
use App\Repositories\Common\CustomFieldRepository;
use App\Traits\Common\Fillable;
use Illuminate\Http\Request;

class CustomFieldController extends ControlController
{
    use Fillable;

    public function __construct(
        protected CustomFieldRepository $customFieldRepository
    )
    {
        parent::__construct();
    }

    public function update(Request $request, string $customFieldId)
    {
        $customField = $this->customFieldRepository->findByHashidOrFail($customFieldId);
       
        $updated = $customField->update($this->filled([
            'title' => $request->title,
            'type' => $request->type,
            'description' => $request->description,
            'required' => $request->required,
            'has_values' => $request->has_values ?? 0,
            'answers' => json_encode($request->answers) ?? json_encode([]),
            'default_value' => $request->default_value ?? "",
            'enabled' => $request->enabled ?? 1,
            'validation_rules' => $request->validation_rules ?? '',
        ]));
        if ($updated) {
            flash('Custom Field updated successfully')->success();
        } else {
            flash('Unable to update custom field')->error();
        }
        return redirect()->back();
    }
}
