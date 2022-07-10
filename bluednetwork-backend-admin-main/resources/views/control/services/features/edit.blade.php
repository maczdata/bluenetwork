<x-layouts.authenticated>
    @section('seo')
        <title>Update Service Feature &raquo; {{ config('app.name') }}</title>
        <meta name="description" content="{{ config('app.name') }} Update Service Feature">
    @stop
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Update Service Feature') }}
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
                            $featurize->feature->hashId(), 'service'
                        ]) }})"
                        >
                        {{ __('Update Service Feature Title') }}
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
                                <a href=""
                                    class="m-2 inline-flex items-center p-3 bg-green-900 border border-transparent rounded-full font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-600 active:bg-gray-900 focus:outline-none focus:border-green-800 focus:ring focus:ring-gray-300 disabled:opacity-25 transition ml-2">
                                    {{ __('Update Feature Meta') }}
                                </a>
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
                            <a href=""
                                class="m-2 inline-flex items-center p-3 bg-green-900 border border-transparent rounded-full font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-600 active:bg-gray-900 focus:outline-none focus:border-green-800 focus:ring focus:ring-gray-300 disabled:opacity-25 transition ml-2">
                                {{ __('Update Feature Value') }}
                            </a>
                        </div>
                    </div>
                    <hr/>
                    @endforeach
                    @endif
                    <x-form.button
                        class="m-2"
                        onclick="Livewire.emit('openModal', 'control.services.featurizes.create-value', {{ json_encode([
                              $service->hashId(), $featurize->hashId(), 'featurize'
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
