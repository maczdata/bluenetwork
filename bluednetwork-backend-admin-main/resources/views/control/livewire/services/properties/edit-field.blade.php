<div class="p-3">
    <x-form.form-section
    method="post"
        title="{{ 'Update Field'}}"
        description="{{'Update Field'}}"
    >
    <x-slot name="formencoding">
        multipart/form-data
    </x-slot>
    <x-slot name="otherMethod">
        @method('PUT')
        </x-slot>

    <x-slot name="action">
        {{ route('control.custom-fields.update', ['custom_field_id' => $field->hashId()]) }}
    </x-slot>
        <x-slot name="content">
            <x-form.validation-errors />
        </x-slot>
        <x-slot name="form">
            <div class="col-span-6 sm:col-span-6">
                <x-form.label for="title" value="{{ __('Title') }}" />
                <x-form.input id="title"  type="text" name="title" value="{{ $title }}" wire:model="title" required  autocomplete="name" />
                <x-form.input-error for="title" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-6">
                <x-form.label for="description" value="{{ __('Description') }}" />
                <x-form.textarea id="description" name="description" wire:model="description"  >
                    {{ $description }}
                </x-form.textarea>
                <x-form.input-error for="description" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-6">
                <x-form.label for="type" value="{{ __('Type') }}" />
                <x-form.select id="type" name="type" wire:model="type">
                    <x-slot name="slot">
                        <option value="text" {{ $type === 'text' ? 'selected': '' }}>Text</option>
                        <option value="textarea" {{ $type === 'textarea' ? 'selected': '' }}>Textarea</option>
                        <option value="number" {{ $type === 'number' ? 'selected': '' }}>Number</option>
                        <option value="select" {{ $type === 'select' ? 'selected': '' }}>Select</option>
                        <option value="tel" {{ $type === 'tel' ? 'selected': '' }}>Telephone</option>
                        <option value="checkbox" {{ $type === 'checkbox' ? 'selected': '' }}>Checkbox</option>
                        <option value="radio" {{ $type === 'radio' ? 'selected': '' }}>Radio</option>
                        <option value="file" {{ $type === 'file' ? 'selected': '' }}>File</option>
                        <option value="boolean" {{ $type === 'boolean' ? 'selected': '' }}>Boolean</option>
                        <option value="image" {{ $type === 'image' ? 'selected': '' }}>Image</option>
                    </x-slot>
                </x-form.select>
                <x-form.input-error for="type" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-6">
                <x-form.label for="required" value="{{ __('Field is required') }}" />
                <x-form.select id="required" name="required" wire:model="required">
                    <x-slot name="slot">
                        <option value="1" {{ $required === '1' ? 'selected': '' }}>Yes</option>
                        <option value="0" {{ $required === '0' ? 'selected': '' }}>No</option>
                    </x-slot>
                </x-form.select>
            </div>  
            <div class="col-span-6 sm:col-span-6">
                <x-form.label for="enabled" value="{{ __('Enabled') }}" />
                <x-form.select id="enabled" name="enabled" wire:model="enabled">
                    <x-slot name="slot">
                        <option value="1" {{ $required === '1' ? 'selected': '' }}>Yes</option>
                        <option value="0" {{ $required === '0' ? 'selected': '' }}>No</option>
                    </x-slot>
                </x-form.select>
            </div>  
            
            <div class="col-span-6 sm:col-span-6">
                <x-form.label for="has_values" value="{{ __('Field has Value') }}" />
                <x-form.select id="has_values" name="has_values" wire:model="has_values">
                    <x-slot name="slot">
                        <option value="1" {{ $required === '1' ? 'selected': '' }}>Yes</option>
                        <option value="0" {{ $required === '0' ? 'selected': '' }}>No</option>
                    </x-slot>
                </x-form.select>
            </div> 

            <div class="col-span-6 sm:col-span-6">
                @php
                 if (json_decode($field->answers, true) == null){
                    $answers = [];
                 }else {
                    $answers = is_array($field->answers) ? $field->answers : 
                    json_decode($field->answers, true);
                 }
                @endphp
                <x-form.label for="answers" value="{{ __('Answers') }}" />
                <div x-data="{answers: {{  str_replace(["'", '"'], ["\'", "'"], json_encode($answers)) }}, newAnswer: ''}" class='border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 shadow-sm p-2'>
                    <template x-for="answer in answers">
                        <input type="hidden" name="answers[]" :value="answer">
                    </template>
                    <div class="w-full mx-auto">
                        <div class="answers-input">
                            <template x-for="answer in answers" :key="answer">
                                <span class="answers-input-answer">
                                    <span x-text="answer"></span>
                                    <button type="button" class="answers-input-remove" @click="answers = answers.filter(i => i !== answer)">
                                        &times;
                                    </button>
                                </span>
                            </template>
                
                            <input class="answers-input-text" placeholder="Add answer..."
                                @keydown.enter.prevent="if (newAnswer.trim() !== '') answers.push(newAnswer.trim()); newAnswer = ''"
                                @keydown.backspace="if (newAnswer.trim() === '') answers.pop()"
                                x-model="newAnswer"
                            >
                        </div>
                    </div>
                </div>
            </div>  
            
            <div class="col-span-6 sm:col-span-6">
                <x-form.label for="default_value" value="{{ __('Default Value') }}" />
                <x-form.input id="default_value"  type="text" name="default_value" value="{{ $default_value }}" wire:model="default_value" required  autocomplete="name" />
                <x-form.input-error for="default_value" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-6">
                <x-form.label for="validation_rules" value="{{ __('Validation Rules') }}" />
                <x-form.input id="validation_rules"  type="text" name="validation_rules" value="{{ $validation_rules }}" wire:model="validation_rules"  required />
                <x-form.input-error for="validation_rules" class="mt-2" />
            </div>
        </x-slot>
    
        <x-slot name="actions">
            <x-general.action-message class="mr-3" on="saved">
                {{ __('Updated.') }}
            </x-general.action-message>
    
            <x-form.button>
                {{ __('Update Field') }}
            </x-form.button>
        </x-slot>
    </x-form.form-section>
</div>
