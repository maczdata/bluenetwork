<div class="p-3">
    <x-form.form-section
        method="post"
        title="{{ 'Update Service Variant'}}"
        description="Update service variant"
    >
    <x-slot name="formencoding">
        multipart/form-data
    </x-slot>
    <x-slot name="otherMethod">
        @method('PUT')
        </x-slot>
    <x-slot name="action">
        {{ route('control.service.update-variant', ['service_variant_id' => $serviceVariant->hashId(), 'service_id' => $service->hashId()]) }}
    </x-slot>
        <x-slot name="content">
            <x-form.validation-errors />
        </x-slot>
        <x-slot name="form">
            <div class="flex">
                <div class="items-center flex">
                    <img
                      src="{{ $serviceVariant->service_variant_icon }}"
                      alt="service-image"
                      class="object-contain md:object-scale-down"
                      style="width: 100%"
                    />
                  <label
                    for="avatar-image"
                    class="ml-3 text-sm text-blue-500 underline font-bold"
                  >
                  <x-form.input id="icon" type="file" name="icon" />
                    <span>Service Variant Icon</span>
                  </label>
                </div>
              </div>
            <div class="col-span-6 sm:col-span-6">
                <x-form.label for="title" value="{{ __('Title') }}" />
                <x-form.input id="title"  type="text" name="title" value="{{ $serviceVariant->title }}" />
                <x-form.input-error for="title" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-6">
                <x-form.label for="description" value="{{ __('Description') }}"  />
                <x-form.textarea id="description" name="description" value="{{ $serviceVariant->description }}">
                </x-form.textarea>
                <x-form.input-error for="description" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-6">
                <x-form.label for="price" value="{{ __('Price') }}" />
                <x-form.input id="price"  type="number" name="price" value="{{ $serviceVariant->price }}"  />
                <x-form.input-error for="price" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-6">
                <x-form.label for="ready_duration" value="{{ __('Ready Duration') }}" />
                <x-form.input id="ready_duration"  type="text" name="ready_duration" value="{{ $serviceVariant->ready_duration }}"  />
                <x-form.input-error for="ready_duration" class="mt-2" />
            </div>
        </x-slot>
    
        <x-slot name="actions">
            <x-general.action-message class="mr-3" on="saved">
                {{ __('Update.') }}
            </x-general.action-message>
    
            <x-form.button>
                {{ __('Update Variant') }}
            </x-form.button>
        </x-slot>
    </x-form.form-section>
</div>
