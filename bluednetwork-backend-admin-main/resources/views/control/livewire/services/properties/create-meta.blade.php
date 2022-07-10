<div class="p-3">
    <x-form.form-section
        method="post"
        title="{{ 'Create '. ucfirst($modelType) .' Meta'}}"
        description="Create a new meta"
    >
        <x-slot name="action">
            {{-- @if($modelType === "feature")
            {{ route('create-feature-meta', ['feature_id' => $service->hashId()]) }}
            @else --}}
            {{ route('control.service.store') }}
            {{-- @endif --}}
        </x-slot>
        <x-slot name="content">
            <x-form.validation-errors />
        </x-slot>
        <x-slot name="form">
            <x-form.input type="hidden" name="request_type" value="meta" />
            <x-form.input id="type" type="hidden" name="model_type" value="{{ $modelType }}" />
            <x-form.input id="type" type="hidden" name="service_id" value="{{ $service->hashId() }}" />
            <div class="col-span-12 sm:col-span-12">
                <x-form.label for="key" value="{{ __('Title') }}" />
                <x-form.input id="key"  type="text" name="key" :value="old('key')" required  autocomplete="name" />
                <x-form.input-error for="key" class="mt-2" />
            </div>
            <div class="col-span-12 sm:col-span-12">
                <x-form.label for="value" value="{{ __('Value') }}" />
                <x-form.input id="value"  type="text" name="value" :value="old('value')" required autocomplete="name" />
                <x-form.input-error for="value" class="mt-2" />
            </div>
        </x-slot>
    
        <x-slot name="actions">
            <x-general.action-message class="mr-3" on="saved">
                {{ __('Saved.') }}
            </x-general.action-message>
    
            <x-form.button>
                {{ __('Save Meta') }}
            </x-form.button>
        </x-slot>
    </x-form.form-section>
</div>
