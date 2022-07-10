<x-layouts.authenticated>
    @section('seo')
        <title>Update Service Variant &raquo; {{ config('app.name') }}</title>
        <meta name="description" content="{{ config('app.name') }} Update Service Variant">
    @stop
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Update Service Variant') }}
        </h2>
    </x-slot>
    @include('control.fragments.general.flash')
    <div>
        <div class="mx-auto p-3">
            <div>
                <x-form.validation-errors />
            </div>
            <div class="flex-none md:flex gap-0 md:gap-4">
                <div class="w-full bg-white rounded-lg overflow-hidden shadow-lg px-6 py-4">
                    <div class="flex justify-between mb-6">
                        <div>
                            <h3 class="font-bold text-base mb-1 text-gray-400">
                               Title
                            </h3>
                            <div class="text-grey-dark">
                                {{ ucfirst($serviceVariant->title) }}
                            </div>
                        </div>
                        <div>
                            <h3 class="font-bold text-base mb-1 text-gray-400">
                                Description
                            </h3>
                            <div class="text-grey-dark">
                                {{ $serviceVariant->description }}
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-between mb-6">
                        <div>
                            <h3 class="font-bold text-base mb-1 text-gray-400">
                                Price
                            </h3>
                            <div class="text-grey-dark">
                                {{ core()->formatBasePrice($serviceVariant->price  ?? 0) }}
                            </div>
                        </div>
                        <div>
                            <h3 class="font-bold text-base mb-1 text-gray-400">
                                Enabled
                            </h3>
                            <div class="text-grey-dark">
                                @include('control.livewire.general.boolean', ['boolean' => $serviceVariant->enabled])
                            </div>
                        </div>
                    </div>
                    <x-form.button
                        class="m-2"
                        onclick="Livewire.emit('openModal', 'control.services.properties.edit-variant', {{ json_encode([
                            $serviceVariant->hashId(), $service->hashId()
                        ]) }})"
                        >
                        {{ __('Update Service Variant') }}
                    </x-form.button>
                </div>
                
                <div class="w-auto md:w-1/1 lg:w-1/2 flex">
                </div>
            </div>
            <div class="flex-none md:flex gap-0 md:gap-4 mt-2">
                <div class="w-full bg-white rounded-lg overflow-hidden shadow-lg px-6 py-4">
                    <div class="flex justify-between mb-6">
                        <div>
                            <h2 class="font-bold text-base mb-1 text-gray-600">
                                Service Variant AddOns
                            </h2>
                        </div>
                    </div>
                    @if($serviceVariant->addons)
                    @foreach($serviceVariant->addons as $addon)
                    <div class="flex justify-between mb-6">
                        <div>
                            <h3 class="font-bold text-base mb-1 text-gray-400">
                                Label
                            </h3>
                            <div class="text-grey-dark">
                                {{ $addon->title }}
                            </div>
                        </div>
                        <div>
                            <h3 class="font-bold text-base mb-1 text-gray-400">
                                Description
                            </h3>
                            <div class="text-grey-dark">
                                {{ $addon->description }}
                            </div>
                        </div>
                        <div>
                            <h3 class="font-bold text-base mb-1 text-gray-400">
                                Price
                            </h3>
                            <div class="text-grey-dark">
                                {{ core()->formatBasePrice($addon->price  ?? 0) }}
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-between mb-6">
                        <div>
                            <h3 class="font-bold text-base mb-1 text-gray-400">
                                Enabled
                            </h3>
                            <div class="text-grey-dark">
                                @include('control.livewire.general.boolean', ['boolean' => $addon->enabled])
                            </div>
                        </div>
                        <div>
                            @can('update_addon')
                            <x-form.danger-button 
                               class="inline-flex items-center px-4 py-2 bg-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-dark active:bg-gray-900 focus:outline-none focus:border-primary-faded focus:ring focus:ring-gray-300 disabled:opacity-25 transition ml-2" onclick="Livewire.emit('openModal', 'control.services.properties.edit-addon',  {{ json_encode([$addon->hashId()]) }} )"
                                >
                                <i class="fa fa-edit"></i>
                            </x-form.danger-button>
                            @endcan
                        @can('delete_addon')
                        <x-form.danger-button onclick="Livewire.emit('openModal', 'control.services.properties.delete-addon',  {{ json_encode([$addon->hashId()]) }} )"
                            >
                            <i class="fa fa-trash-alt"></i>
                        </x-form.danger-button>
                        @endcan
                        </div>
                    </div>
                    <hr/>
                    @endforeach
                    @endif
                    <x-form.button
                        class="m-2"
                        onclick="Livewire.emit('openModal', 'control.services.properties.create-addon', {{ json_encode([$serviceVariant->hashId(), 'service-variant']) }})"
                        >
                        {{ __('Add AddOn to Service Variant') }}
                    </x-form.button>
                </div>
                <div class="w-auto md:w-1/1 lg:w-1/2 flex">
                </div>
            </div>
            <div class="flex-none md:flex gap-0 md:gap-4 mt-2">
                <div class="w-full bg-white rounded-lg overflow-hidden shadow-lg px-6 py-4">
                    <div class="flex justify-between mb-6">
                        <div>
                            <h2 class="font-bold text-base mb-1 text-gray-600">
                                Service Variant Features
                            </h2>
                        </div>
                    </div>
                    @if($serviceVariant->serviceFeatures)
                    @foreach($serviceVariant->serviceFeatures as $feature)
                    <div class="flex justify-between mb-6">
                        <div>
                            <h3 class="font-bold text-base mb-1 text-gray-400">
                                Title
                            </h3>
                            <div class="text-grey-dark">
                                {{ $feature->feature->title }}
                            </div>
                        </div>
                        <div>
                            <h3 class="font-bold text-base mb-1 text-gray-400">
                                Values
                            </h3>
                            <div class="text-grey-dark">
                                @foreach($feature->featureValues as $value)
                                <p>{{ $value->title }}</p>
                                @endforeach
                            </div>
                        </div>
                        <div>
                            <h3 class="font-bold text-base mb-1 text-gray-400">
                                Metas
                            </h3>
                            <div class="text-grey-dark">
                                @if ($feature->meta) 
                                @foreach($feature->meta as $meta)
                                <p>{{ $meta->key }} : {{ $meta->value }}</p>
                                @endforeach
                                @endif
                            </div>
                        </div>
                        <div>
                            @can('update_feature')
                            <a href="{{ route('control.service.edit-variant-feature', [
                                'service_id' => $service->hashId(),
                                'service_variant_id' => $serviceVariant->hashId(),
                                'feature_id' => $feature->hashId(),
                                ]) }}"
                                class="inline-flex items-center px-4 py-2 bg-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-dark active:bg-gray-900 focus:outline-none focus:border-primary-faded focus:ring focus:ring-gray-300 disabled:opacity-25 transition ml-2">
                                <i class="fa fa-edit"></i>
                            </a>
                            @endcan
                            @can('delete_feature')
                            <x-form.danger-button onclick="Livewire.emit('openModal', 'control.services.properties.delete-feature',  {{ json_encode([$feature->hashId()]) }} )"
                                >
                                <i class="fa fa-trash-alt"></i>
                            </x-form.danger-button>
                            @endcan
                        </div>
                    </div>
                    @endforeach
                    @endif
                    <x-form.button
                    class="m-2"
                    onclick="Livewire.emit('openModal', 'control.services.properties.create-feature', {{ json_encode([
                        $serviceVariant->hashId(), 'service-variant'
                    ]) }})"
                    >
                    {{ __('Add New Feature to Service Variant') }}
                </x-form.button>
                </div>
                <div class="w-auto md:w-1/1 lg:w-1/2 flex">
                </div>
            </div>
            <div class="flex-none md:flex gap-0 md:gap-4 mt-2">
                <div class="w-full bg-white rounded-lg overflow-hidden shadow-lg px-6 py-4">
                    <div class="flex justify-between mb-6">
                        <div>
                            <h2 class="font-bold text-base mb-1 text-gray-600">
                                Service Variant Fields
                            </h2>
                        </div>
                    </div>
                    @if($serviceVariant->customFields)
                    @foreach($serviceVariant->customFields as $field)
                    <div class="flex justify-between mb-6">
                        <div>
                            <h3 class="font-bold text-base mb-1 text-gray-400">
                                Title
                            </h3>
                            <div class="text-grey-dark">
                                {{
                                    $field->title
                                }}
                            </div>
                        </div>
                        <div>
                            <h3 class="font-bold text-base mb-1 text-gray-400">
                                Type
                            </h3>
                            <div class="text-grey-dark">
                                {{ strtolower($field->type) }}
                            </div>
                        </div>
                        <div>
                            <h3 class="font-bold text-base mb-1 text-gray-400">
                                Description
                            </h3>
                            <div class="text-grey-dark">
                                {{
                                    $field->description
                                }}
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-between mb-6">
                        
                        <div>
                            <h3 class="font-bold text-base mb-1 text-gray-400">
                                Answers
                            </h3>
                            <div class="text-grey-dark">
                               
                                @if(isset($field->answers) && $field->answers !== "null")
                                    @if(is_array($field->answers)) 
                                        {{ $field->answers ? implode(',', $field->answers) : "" }}
                                    @else
                                        {{ $field->answers ? implode(',', json_decode($field->answers, true)) : "" }}
                                    @endif
                                @endif
                            </div>
                        </div>
                        <div>
                            @can('update_custom_field')
                            <x-form.danger-button 
                              class="inline-flex items-center px-4 py-2 
                              bg-primary border border-transparent
                               rounded-md font-semibold text-xs text-white
                                uppercase tracking-widest hover:bg-primary-dark
                                 active:bg-gray-900 focus:outline-none 
                                 focus:border-primary-faded focus:ring
                                  focus:ring-gray-300 disabled:opacity-25 
                                  transition ml-2" 
                                  onclick="Livewire.emit('openModal', 'control.services.properties.edit-field',  {{ json_encode([$field->hashId()]) }} )"
                                >
                                <i class="fa fa-edit"></i>
                            </x-form.danger-button>
                            @endcan
                            @can('delete_custom_field')
                            <x-form.danger-button onclick="Livewire.emit('openModal', 'control.services.properties.delete-field',  {{ json_encode([$field->hashId()]) }} )"
                                >
                                <i class="fa fa-trash-alt"></i>
                            </x-form.danger-button>
                            @endcan
                        </div>
                    </div>
                    <hr/>
                    @endforeach
                    @endif
                    <x-form.button
                        class="m-2"
                        onclick="Livewire.emit('openModal', 'control.services.properties.create-field', {{ json_encode([
                            $serviceVariant->hashId(), 'service-variant'
                        ]) }})"
                        >
                        {{ __('Add New Field to Service Variant') }}
                    </x-form.button>
                </div>
                <div class="w-auto md:w-1/1 lg:w-1/2 flex">
                </div>
            </div>
            <div class="flex-none md:flex gap-0 md:gap-4 mt-2">
                <div class="w-full bg-white rounded-lg overflow-hidden shadow-lg px-6 py-4">
                    <div class="flex justify-between mb-6">
                        <div>
                            <h2 class="font-bold text-base mb-1 text-gray-600">
                                Service Variant Metas
                            </h2>
                        </div>
                    </div>
                    @if($serviceVariant->meta)
                    @foreach($serviceVariant->meta as $meta)
                    <div class="flex justify-between mb-6">
                        <div>
                            <h3 class="font-bold text-base mb-1 text-gray-400">
                                Key
                            </h3>
                            <div class="text-grey-dark">
                                {{ $meta->key }}
                            </div>
                        </div>
                        <div>
                            <h3 class="font-bold text-base mb-1 text-gray-400">
                                Value
                            </h3>
                            <div class="text-grey-dark">
                               {{ $meta->value }}
                            </div>
                        </div>
                        <div>
                            <x-form.danger-button class="inline-flex items-center px-4 py-2 bg-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-dark active:bg-gray-900 focus:outline-none focus:border-primary-faded focus:ring focus:ring-gray-300 disabled:opacity-25 transition ml-2" onclick="Livewire.emit('openModal', 'control.services.properties.edit-meta',  {{ json_encode([$meta->id]) }} )"
                                >
                                <i class="fa fa-edit"></i>
                            </x-form.danger-button>
                            <x-form.danger-button onclick="Livewire.emit('openModal', 'control.services.properties.delete-meta',  {{ json_encode([$meta->id]) }} )"
                                >
                                <i class="fa fa-trash-alt"></i>
                            </x-form.danger-button>
                        </div>
                    </div>
                    @endforeach
                    @endif
                    <x-form.button
                        class="m-2"
                        onclick="Livewire.emit('openModal', 'control.services.properties.create-meta', {{ json_encode([
                            $serviceVariant->hashId(), 'service-variant'
                        ]) }})"
                        >
                        {{ __('Add Meta to Service Variant') }}
                    </x-form.button>
                </div>
                <div class="w-auto md:w-1/1 lg:w-1/2 flex">
                </div>
            </div>
            @include('control.fragments.general.flash')
            @include('control.fragments.general.flash')

        </div>
    </div>
</x-layouts.authenticated>
