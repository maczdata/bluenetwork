<x-layouts.authenticated>
    @section('seo')
        <title>Service list &raquo; {{ config('app.name') }}</title>
        <meta name="description" content="{{ config('app.name') }} Service Type list">
    @stop
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Service Type list') }}
        </h2>
    </x-slot>
    @include('control.fragments.general.flash')

    <div class="py-4 sm:py-10 mx-2 sm:mx-4 md:mx-5 lg:mx-10">
        @can('create_service_type')
            <x-form.button class="m-2"
                onclick="Livewire.emit('openModal', 'control.service-types.create-service-types')">
                {{ __('Create Service Type') }}
            </x-form.button>
        @endcan
        @include('control.fragments.general.flash')
        @livewire('control.service-types.list-service-types')
    </div>


</x-layouts.authenticated>
