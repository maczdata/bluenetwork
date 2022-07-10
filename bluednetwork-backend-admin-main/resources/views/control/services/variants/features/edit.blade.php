<x-layouts.authenticated>
    @section('seo')
        <title>Update Service Variant Feature &raquo; {{ config('app.name') }}</title>
        <meta name="description" content="{{ config('app.name') }} Update Service Variant Feature">
    @stop
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Update Service Variant Feature') }}
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
                                {{ ucfirst($featurize->feature->title) }}
                            </div>
                        </div>
                    </div>
                    <x-form.button
                        class="m-2"
                        onclick="Livewire.emit('openModal', 'control.services.properties.edit-feature', {{ json_encode([
                            $featurize->feature->hashId()
                        ]) }})"
                        >
                        {{ __('Update Service Variant Feature Title') }}
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
                                Feature Metas
                            </h2>
                        </div>
                    </div>
                    @if($featurize->meta)
                        @foreach($featurize->meta as $meta)
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
                            $featurize->hashId(), 'feature'
                        ]) }})"
                        >
                        {{ __('Add Meta to Feature') }}
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
                                Feature Values
                            </h2>
                        </div>
                    </div>
                    @if($featurize->featureValues)
                    @foreach($featurize->featureValues as $value)
                    <div class="flex justify-between mb-6">
                        <div>
                            <h3 class="font-bold text-base mb-1 text-gray-400">
                                Title
                            </h3>
                            <div class="text-grey-dark">
                                {{
                                    $value->title
                                }}
                            </div>
                        </div>
                        <div>
                            <h3 class="font-bold text-base mb-1 text-gray-400">
                                Description
                            </h3>
                            <div class="text-grey-dark">
                                {{
                                    $value->description
                                }}
                            </div>
                        </div>
                        <div>
                            <x-form.danger-button class="inline-flex items-center px-4 py-2 bg-primary 
                            border border-transparent rounded-md font-semibold text-xs
                             text-white uppercase tracking-widest hover:bg-primary-dark
                              active:bg-gray-900 focus:outline-none focus:border-primary-faded focus:ring
                               focus:ring-gray-300 disabled:opacity-25 transition ml-2" 
                               onclick="Livewire.emit('openModal', 'control.services.featurizes.edit-value',  {{ json_encode([$value->hashId()]) }} )"
                                >
                                <i class="fa fa-edit"></i>
                            </x-form.danger-button>
                            <x-form.danger-button onclick="Livewire.emit('openModal', 'control.services.properties.delete-feature-value',  {{ json_encode([$value->hashId()]) }} )"
                                >
                                <i class="fa fa-trash-alt"></i>
                            </x-form.danger-button>
                        </div>
                    </div>
                    <hr/>
                    @endforeach
                    @endif
                    <x-form.button
                        class="m-2"
                        onclick="Livewire.emit('openModal', 'control.services.featurizes.create-value', {{ json_encode([
                              $serviceVariant->hashId(), $featurize->hashId(), 'service-variant'
                        ]) }})"
                        >
                        {{ __('Add New Value to Feature') }}
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
