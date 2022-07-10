<x-layouts.authenticated>
    @section('seo')
        <title>Frontend Settings &raquo; {{ config('app.name') }}</title>
        <meta name="description" content="{{ config('app.name') }} Users list">
    @stop
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Frontend Settings') }}
        </h2>
    </x-slot>
    @include('control.fragments.general.flash')
    <div>
        <div class="mx-auto p-3">
            <div>
                <x-form.validation-errors />
            </div>
            @can('view_settings')
                <div class="mx-auto">
                    @if (auth()->user()->role === 'super_admin' ||
    auth()->user()->hasAnyPermission(['create_settings', 'update_settings']))
                        <x-form.button class="mt-2"
                            onclick="Livewire.emit('openModal', 'control.settings.create-setting-type')">
                            {{ __('Create Setting Type') }}
                        </x-form.button>
                        <x-form.button class="mt-2"
                            onclick="Livewire.emit('openModal', 'control.settings.create-setting', {{ json_encode([$settingTypes]) }} )">
                            {{ __('Create Setting') }}
                        </x-form.button>
                    @endif
                </div>
                @if (count($settingTypes) > 0)
                    @foreach ($settingTypes as $settingType)
                        <div class="flex-none md:flex gap-0 md:gap-4 mt-2">
                            <div class="w-full bg-white rounded-lg overflow-hidden shadow-lg px-6 py-4">
                                <div class="flex justify-between mb-6">
                                    <div>
                                        <h2 class="font-bold text-base mb-1 text-gray-600">
                                            {{ $settingType->name }}
                                        </h2>
                                    </div>
                                </div>
                                @foreach ($settingType->settings as $setting)
                                    <div class="flex justify-between mb-6">
                                        <div>
                                            <h3 class="font-bold text-base mb-1 text-gray-400">
                                                Name
                                            </h3>
                                            <div class="text-grey-dark">
                                                {{ $setting->name }}
                                            </div>
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-base mb-1 text-gray-400">
                                                Value
                                            </h3>
                                            <div class="text-grey-dark">
                                                @if ($setting->data_type === 'file')
                                                    @foreach ($setting->media as $media)
                                                        <div class="flex flex-row items-center">
                                                            {{ $media->img()->attributes(['class' => 'w-30a h-40 m-2']) }}
                                                        </div>
                                                    @endforeach
                                                @else
                                                    {{ $setting->value }}
                                                @endif
                                            </div>
                                            <div class="text-grey-dark">
                                                @if ($setting->data_type === 'file')
                                                    @foreach ($setting->meta as $meta)
                                                        <p>{{ ucfirst($meta->key) }} -  {{ $meta->value }}</p>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                        <div>
                                            <x-form.danger-button
                                                class="bg-green-500 hover:bg-green-600 active:bg-gray-900 focus:outline-none focus:border-green-800 focus:ring focus:ring-gray-300">
                                                <i class="fa fa-edit"></i>
                                            </x-form.danger-button>

                                            <x-form.danger-button onclick="Livewire.emit('openModal', 'control.settings.delete-setting',  {{ json_encode([$setting->hashId()]) }} )">
                                                <i class="fa fa-trash-alt"></i>
                                            </x-form.danger-button>

                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="w-auto md:w-1/1 lg:w-1/2 flex">
                            </div>
                        </div>
                    @endforeach
                @endif
            @endcan
            @include('control.fragments.general.flash')
        </div>
    </div>
</x-layouts.authenticated>
