<div class="p-3">
    <x-form.form-section
        method="post"
        title="{{ 'Create '. ucfirst($modelType) .' Field'}}"
        description="Create a new service Field"
    >
        <x-slot name="action">
            {{ route('control.service.store') }}
        </x-slot>
        <x-slot name="content">
            <x-form.validation-errors />
        </x-slot>
        <x-slot name="form">
            <x-form.input id="type" type="hidden" name="request_type" value="field" />
            <x-form.input id="type" type="hidden" name="model_type" value="{{ $modelType }}" />
            <x-form.input id="type" type="hidden" name="service_id" value="{{ $service->hashId() }}" />
            <div class="col-span-6 sm:col-span-6">
                <x-form.label for="title" value="{{ __('Title') }}" />
                <x-form.input id="title"  type="text" name="title" :value="old('title')" required  autocomplete="name" />
                <x-form.input-error for="title" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-6">
                <x-form.label for="description" value="{{ __('Description') }}" />
                <x-form.textarea id="description" name="description"  >
                </x-form.textarea>
                <x-form.input-error for="description" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-6">
                <x-form.label for="type" value="{{ __('Type') }}" />
                <x-form.select id="type" name="type">
                    <x-slot name="slot">
                        <option value="text">Text</option>
                        <option value="textarea">Textarea</option>
                        <option value="number">Number</option>
                        <option value="select">Select</option>
                        <option value="tel">Telephone</option>
                        <option value="checkbox">Checkbox</option>
                        <option value="radio">Radio</option>
                        <option value="file">File</option>
                        <option value="boolean">Boolean</option>
                        <option value="image">Image</option>
                    </x-slot>
                </x-form.select>
                <x-form.input-error for="type" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-6">
                <x-form.label for="required" value="{{ __('Field is required') }}" />
                <x-form.select id="required" name="required">
                    <x-slot name="slot">
                        <option value="1" selected>Yes</option>
                        <option value="0">No</option>
                    </x-slot>
                </x-form.select>
            </div>  
            <div class="col-span-6 sm:col-span-6">
                <x-form.label for="enabled" value="{{ __('Enabled') }}" />
                <x-form.select id="enabled" name="enabled">
                    <x-slot name="slot">
                        <option value="1" selected>Yes</option>
                        <option value="0">No</option>
                    </x-slot>
                </x-form.select>
            </div>  
            
            <div class="col-span-6 sm:col-span-6">
                <x-form.label for="has_values" value="{{ __('Field has Value') }}" />
                <x-form.select id="has_values" name="has_values">
                    <x-slot name="slot">
                        <option value="1">Yes</option>
                        <option value="0" selected>No</option>
                    </x-slot>
                </x-form.select>
            </div> 

            <div class="col-span-6 sm:col-span-6">
                <x-form.label for="answers" value="{{ __('Answers') }}" />
                <div x-data="{answers: ['hey'], newAnswer: '' }" class='border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 shadow-sm p-2'>
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
                <x-form.input id="default_value"  type="text" name="default_value" :value="old('default_value')" required  autocomplete="name" />
                <x-form.input-error for="default_value" class="mt-2" />
            </div>
            
            <div class="col-span-6 sm:col-span-6">
                <x-form.label for="validation_rules" value="{{ __('Validation Rules') }}" />
                <x-form.input id="validation_rules"  type="text" name="validation_rules" :value="old('validation_rules')" required  autocomplete="name" />
                <x-form.input-error for="validation_rules" class="mt-2" />
            </div>
        </x-slot>
    
        <x-slot name="actions">
            <x-general.action-message class="mr-3" on="saved">
                {{ __('Saved.') }}
            </x-general.action-message>
    
            <x-form.button>
                {{ __('Save Field') }}
            </x-form.button>
        </x-slot>
    </x-form.form-section>
</div>
