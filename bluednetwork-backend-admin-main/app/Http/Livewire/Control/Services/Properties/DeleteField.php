<?php

namespace App\Http\Livewire\Control\Services\Properties;

use App\Models\Common\CustomField;
use LivewireUI\Modal\ModalComponent;

class DeleteField extends ModalComponent
{
    public CustomField $customField;

    public function mount(CustomField $customField)
    {
        $this->customField = $customField;
    }

    public function delete()
    {
        $deleted = CustomField::destroy($this->customField->id);
        if ($deleted) {
            flash('Custom Field deleted successfully')->success();
        } else {
            flash('Unable to delete custom field')->error();
        }
        $this->closeModal();
        return redirect(request()->header('Referer'));
    }
    public function render()
    {
        return view('control.livewire.services.properties.delete-field');
    }
}
