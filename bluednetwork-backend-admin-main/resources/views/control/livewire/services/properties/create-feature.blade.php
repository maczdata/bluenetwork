<div class="p-3">
    <x-form.form-section
        method="post"
        title="{{ 'Create '. ucfirst($modelType) .' Feature'}}"
        description="Create a new service feature"
    >

        <x-slot name="action">
            {{ route('control.service.store') }}
        </x-slot>
        <x-slot name="content">
            <x-form.validation-errors />
        </x-slot>
        <x-slot name="form">
            <x-form.input type="hidden" name="request_type" value="feature" />
            <x-form.input id="type" type="hidden" name="model_type" value="{{ $modelType }}" />
            <x-form.input id="type" type="hidden" name="service_id" value="{{ $service->hashId() }}" />
            <div class="col-span-12 sm:col-span-12">
                <x-form.label for="title" value="{{ __('Title') }}" />
                <x-form.input id="title"  type="text" name="title" :value="old('title')" required  autocomplete="name" />
                <x-form.input-error for="title" class="mt-2" />
            </div>
        </x-slot>
    
        <x-slot name="actions">
            <x-general.action-message class="mr-3" on="saved">
                {{ __('Saved.') }}
            </x-general.action-message>
    
            <x-form.button>
                {{ __('Save Feature') }}
            </x-form.button>
        </x-slot>
    </x-form.form-section>
</div>
