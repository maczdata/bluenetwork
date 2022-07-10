<div class="p-3">
    <x-form.form-section
        method="post"
        title="{{ 'Create '. ucfirst($modelType) .' Variant'}}"
        description="Create a new service variant"
    >
        <x-slot name="action">
            {{ route('control.service.store') }}
        </x-slot>
        <x-slot name="content">
            <x-form.validation-errors />
        </x-slot>
        <x-slot name="form">
            <x-form.input type="hidden" name="request_type" value="variant" />
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
                <x-form.label for="price" value="{{ __('Price') }}" />
                <x-form.input id="price"  type="number" name="price" :value="old('price')" autocomplete="name" />
                <x-form.input-error for="price" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-6">
                <x-form.label for="ready_duration" value="{{ __('Ready Duration') }}" />
                <x-form.input id="ready_duration"  type="text" name="ready_duration" :value="old('ready_duration')"  autocomplete="name" />
                <x-form.input-error for="ready_duration" class="mt-2" />
            </div>
            <div class="col-span-12 sm:col-span-12 p-2">
                <br/>
                <h4>Service Variant Icon</h4>
                <hr />
                <div class="grid grid-cols-2 mt-2">
                    <div class="">
                        <x-form.label for="icon" value="{{ __('Service Variant Icon') }}" />
                        <x-form.input id="icon" type="file" name="icon" required />
                        <x-form.input-error for="icon" class="mt-2" />
                    </div>
                </div>
            </div>
        </x-slot>
    
        <x-slot name="actions">
            <x-general.action-message class="mr-3" on="saved">
                {{ __('Saved.') }}
            </x-general.action-message>
    
            <x-form.button>
                {{ __('Save Variant') }}
            </x-form.button>
        </x-slot>
    </x-form.form-section>
</div>
