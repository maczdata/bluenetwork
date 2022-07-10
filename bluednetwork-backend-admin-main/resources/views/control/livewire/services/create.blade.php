<div>
    <x-form.form-section
    method="post"
    title="Add Service"
    description="Create a new service"

>
<x-slot name="formencoding">
    multipart/form-data
</x-slot>
    <x-slot name="action">
        {{ route('control.service.store') }}
    </x-slot>
    <x-slot name="content">
        <x-form.validation-errors />
    </x-slot>
    <x-slot name="form">
        <div class="col-span-6 sm:col-span-6">
            <x-form.label for="service_type" value="{{ __('Service Type') }}" class="inline-block">
            <x-form.select id="service_type" name="service_type" required>
                <x-slot name="slot">
                    <option value=""> Select a service type </option>
                    @foreach($serviceTypes as $serviceType) 
                        <option value="{{ $serviceType->hashId() }}"> {{ $serviceType->title }}</option>
                    @endforeach
                </x-slot>
            </x-form.select>
            </x-form.label>
        </div>  
        <div class="col-span-12 sm:col-span-12">
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
        <div class="col-span-3 sm:col-span-3">
            <x-form.label for="service" value="{{ __('Parent Service') }}" />
            <x-form.select id="service" name="parent_id">
                <x-slot name="slot">
                    <option value=""> Select a parent service </option>
                    @foreach($services as $service) 
                        <option value="{{ $service->hashId() }}"> {{ ucfirst($service->title) }}</option>
                    @endforeach
                </x-slot>
            </x-form.select>
        </div>  
        @endif
        <div class="col-span-3 sm:col-span-3">
            <x-form.label for="title" value="{{ __('Title') }}" />
            <x-form.input id="title" type="text" name="title" :value="old('title')" />
            <x-form.input-error for="title" class="mt-2" />
        </div>
        <div class="col-span-3 sm:col-span-3">
            <x-form.label for="price" value="{{ __('Price') }}" />
            <x-form.input id="price" type="number" name="price"  />
            <x-form.input-error for="price" class="mt-2" />
        </div>
        <div class="col-span-3 sm:col-span-3">
            <x-form.label for="description" value="{{ __('Description') }}" />
            <x-form.textarea id="description" name="description"  >
            </x-form.textarea>
            <x-form.input-error for="description" class="mt-2" />
        </div>
        <div class="col-span-3 sm:col-span-3">
            <x-form.label for="discount_type" value="{{ __('Discount Type') }}" />
            <x-form.select id="discount_type" name="discount_type">
                <x-slot name="slot">
                    <option value=""> Select a discount type </option>
                    <option value="fixed">Fixed</option>
                    <option value="percentage">Percentage</option>
                </x-slot>
            </x-form.select>
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
        <div class="col-span-3 sm:col-span-3">
            <x-form.label for="discount_value" value="{{ __('Discount Value') }}" />
            <x-form.input id="discount_value" type="number" name="discount_value"    />
            <x-form.input-error for="discount_value" class="mt-2" />
        </div>
        <div class="col-span-3 sm:col-span-3 ">
            <div class="grid grid-cols-2 mt-2">
                <div class="">
                    <x-form.label for="icon" value="{{ __('Service Icon') }}" />
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
            {{ __('Save') }}
        </x-form.button>
    </x-slot>
</x-form.form-section>
</div>
