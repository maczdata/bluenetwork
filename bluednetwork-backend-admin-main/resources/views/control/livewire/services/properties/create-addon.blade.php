<div class="p-3">
    <x-form.form-section
        method="post"
        title="{{ 'Create '. ucfirst($modelType) .' Add on'}}"
        description="Create a new service addon"
    >
        <x-slot name="action">
            {{ route('control.service.store') }}
        </x-slot>
        <x-slot name="content">
            <x-form.validation-errors />
        </x-slot>
        <x-slot name="form">
            <x-form.input id="type" type="hidden" name="request_type" value="addon" />
            <x-form.input id="type" type="hidden" name="model_type" value="{{ $modelType }}" />
            <x-form.input id="type" type="hidden" name="service_id" value="{{ $service->hashId() }}" />
            <div class="col-span-12 sm:col-span-12">
                <x-form.label for="title" value="{{ __('Title') }}" />
                <x-form.input id="title"  type="text" name="title" :value="old('title')" required  autocomplete="name" />
                <x-form.input-error for="title" class="mt-2" />
            </div>
            <div class="col-span-12 sm:col-span-12">
                <x-form.label for="description" value="{{ __('Description') }}" />
                <x-form.textarea id="description" name="description"  >
                </x-form.textarea>
                <x-form.input-error for="description" class="mt-2" />
            </div>
            <div class="col-span-12 sm:col-span-12">
                <x-form.label for="price" value="{{ __('Price') }}" />
                <x-form.input id="price"  type="number" name="price" :value="old('price')" autocomplete="name" />
                <x-form.input-error for="price" class="mt-2" />
            </div>
            <div class="col-span-12 sm:col-span-12">
                <x-form.label for="enabled" value="{{ __('Enabled') }}" />
                <x-form.select id="enabled" name="enabled">
                    <x-slot name="slot">
                        <option value="1" selected>Yes</option>
                        <option value="0">No</option>
                    </x-slot>
                </x-form.select>
            </div>  
        </x-slot>
    
        <x-slot name="actions">
            <x-general.action-message class="mr-3" on="saved">
                {{ __('Saved.') }}
            </x-general.action-message>
            <x-form.button>
                {{ __('Save Add On') }}
            </x-form.button>
        </x-slot>
    </x-form.form-section>
</div>
