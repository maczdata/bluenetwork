<div class="p-2">
    <x-form.form-section
    method="post"
    title="Update Service"
    description="Update service"
>
<x-slot name="formencoding">
    multipart/form-data
</x-slot>
<x-slot name="otherMethod">
    @method('PUT')
    </x-slot>
    <x-slot name="action">
        {{ route('control.service.update', ['service_id' => $service->hashId()]) }}
    </x-slot>
    <x-slot name="content">
        <x-form.validation-errors />
    </x-slot>
    <x-slot name="form">
        <div class="flex">
            <div class="items-center flex">
   
                <img
                  src="{{ $service->service_icon }}"
                  alt="service-image"
                  class="object-contain md:object-scale-down"
                  style="width: 100%"
                />
              <label
                for="avatar-image"
                class="ml-3 text-sm text-blue-500 underline font-bold"
              >
              <x-form.input id="icon" type="file" name="icon" />
                <span>Service Icon</span>
              </label>
            </div>
          </div>
        <div class="col-span-6 sm:col-span-6">
            <x-form.label for="service_type" value="{{ __('Service Type') }}" class="inline-block">
            <x-form.select id="service_type" name="service_type" required>
                <x-slot name="slot">
                    <option value=""> Select a service type </option>
                    @foreach($serviceTypes as $serviceType) 
                        <option value="{{ $serviceType->hashId() }}" {{ $service->service_type_id === $serviceType->id ? 'selected': '' }}> {{ $serviceType?->title }}</option>
                    @endforeach
                </x-slot>
            </x-form.select>
            </x-form.label>
        </div>  
         <div class="col-span-6 sm:col-span-6">
            <br/>
            <h4>Type of Service</h4>
            <hr />
            <div class="inline-flex">
                <x-form.label for="child_service" value="{{ __(' Parent Service') }}">
                    <input type="radio" class="rounded h-4 w-4 text-primary border-blue-gray-300" id="parent_service" type="radio" name="child_service" value="0" wire:model="childService">
                </x-form.label>
             
                <x-form.label for="child_service" value="{{ __('Child Service') }}" class="ml-4">
                    <input type="radio" class="rounded h-4 w-4 text-primary border-blue-gray-300" id="child_service" type="radio" name="child_service" value="1" wire:model="childService">
                </x-form.label>
            </div>
        </div> 
        @if($childService === "1")
        <div class="col-span-6 sm:col-span-6">
            <x-form.label for="service" value="{{ __('Parent Service') }}" />
            <x-form.select id="service" name="parent_id">
                <x-slot name="slot">
                    <option value=""> Select a parent service </option>
                    @foreach($services as $pservice) 
                        <option value="{{ $pservice->hashId() }}" {{ $service->parent_id === $pservice->id ? 'selected': '' }} > {{ ucfirst($service?->title) }}</option>
                    @endforeach
                </x-slot>
            </x-form.select>
        </div>  
        @endif
        <div class="col-span-6 sm:col-span-6">
            <x-form.label for="title" value="{{ __('Title') }}" />
            <x-form.input id="title" type="text" name="title" value="{{ $service?->title }}" />
            <x-form.input-error for="title" class="mt-2" />
        </div>
        <div class="col-span-6 sm:col-span-6">
            <x-form.label for="price" value="{{ __('Price') }}" />
            <x-form.input id="price" type="number" name="price" value="{{ $service->price }}" />
            <x-form.input-error for="price" class="mt-2" />
        </div>
        <div class="col-span-6 sm:col-span-6">
            <x-form.label for="description" value="{{ __('Description') }}" />
            <x-form.textarea id="description" name="description">
                {{ $service->description }}
            </x-form.textarea>
            <x-form.input-error for="description" class="mt-2" />
        </div>
        <div class="col-span-6 sm:col-span-6">
            <x-form.label for="discount_type" value="{{ __('Discount Type') }}" />
            <x-form.select id="discount_type" name="discount_type">
                <x-slot name="slot">
                    <option value=""> Select a discount type </option>
                    <option value="fixed" {{ $service->discount_type === 'fixed' ? 'selected': '' }}>Fixed</option>
                    <option value="percentage" {{ $service->discount_type === 'percentage' ? 'selected': '' }}>Percentage</option>
                </x-slot>
            </x-form.select>
        </div>  
        <div class="col-span-6 sm:col-span-6">
            <x-form.label for="enabled" value="{{ __('Enabled') }}" />
            <x-form.select id="enabled" name="enabled">
                <x-slot name="slot">
                    <option value="1" {{ $service->enabled === '1' ? 'selected': '' }}>Yes</option>
                    <option value="0" {{ $service->enabled != '1' ? 'selected': '' }}>No</option>
                </x-slot>
            </x-form.select>
        </div>  
        <div class="col-span-6 sm:col-span-6">
            <x-form.label for="discount_value" value="{{ __('Discount Value') }}" />
            <x-form.input id="discount_value" type="number" name="discount_value" value="{{ $service->discount_value }}"  />
            <x-form.input-error for="discount_value" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-general.action-message class="mr-3" on="saved">
            {{ __('Updated.') }}
        </x-general.action-message>

        <x-form.button>
            {{ __('Update') }}
        </x-form.button>
    </x-slot>
</x-form.form-section>
</div>
