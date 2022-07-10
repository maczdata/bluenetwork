<?php

namespace App\Http\Livewire\Control\Services\Properties;

use App\Models\Common\CustomField;
use Illuminate\Support\Facades\Log;
use LivewireUI\Modal\ModalComponent;

class EditField extends ModalComponent
{
    public CustomField $field;
    public $title;
    public $description;
    public $type;
    public $required;
    public $has_values;
    public $default_value;
    public $enabled;
    public $validation_rules;

    public function mount(CustomField $field)
    {
        $this->field = $field;
        $this->title = $this->field->title;
        $this->description = $this->field->description;
        $this->type = $this->field->type;
        $this->required = $this->field->required;
        $this->has_values = $this->field->has_values;
        $this->default_value = $this->field->default_value;
        $this->enabled = $this->field->enabled;
        $this->validation_rules = is_array($this->field->validation_rules) ?
            implode(', ', $this->field->validation_rules) : $this->field->validation_rules;
    }
   
    public function isJson($string): bool
    {
        return (json_decode($string, true) == null) ? false : true;
    }

    public function render()
    {
        return view('control.livewire.services.properties.edit-field');
    }
}
