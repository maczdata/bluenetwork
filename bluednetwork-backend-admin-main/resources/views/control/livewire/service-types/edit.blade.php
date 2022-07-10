<div class="p-3">
    <x-form.form-section
        method="post"
        title="Update Service Type"
        description="Update service type"
    > 
    <x-slot name="otherMethod">
        @method('PUT')
        </x-slot>
        <x-slot name="action">
            {{ route('control.service-type.update', ['service_type_id' => $serviceType->hashId()]) }}
        </x-slot>
        <x-slot name="content">
            <x-form.validation-errors />
        </x-slot>
        <x-slot name="form">
            <div class="col-span-12 sm:col-span-12">
                <x-form.label for="name" value="{{ __('Title') }}" />
                <x-form.input id="title"  type="text" name="title" value="{{ $serviceType->title }}"  required autofocus autocomplete="name" />
                <x-form.input-error for="title" class="mt-2" />
            </div>
            <div class="col-span-12 sm:col-span-12">
                <x-form.label for="enabled" value="{{ __('Status') }}" />
                <x-form.select id="enabled" name="enabled" required >
                    <x-slot name="slot">
                        <option value=""> Select Status </option>
                            <option value="1">Enabled</option>
                            <option value="0">Disabled</option>
                    </x-slot>
                </x-form.select>
            </div> 
        </x-slot>
    
        <x-slot name="actions">
            <x-general.action-message class="mr-3" on="saved">
                {{ __('Saved.') }}
            </x-general.action-message>
    
            <x-form.button>
                {{ __('Update') }}
            </x-form.button>
        </x-slot>
    </x-form.form-section>
</div>
