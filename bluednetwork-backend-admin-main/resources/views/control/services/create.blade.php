<x-layouts.authenticated>
    @section('seo')
        <title>Create Service &raquo; {{ config('app.name') }}</title>
        <meta name="description" content="{{ config('app.name') }} Create Service">
    @stop
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Service') }}
        </h2>
    </x-slot>
    @include('control.fragments.general.flash')
    <div>
        <div class="mx-auto p-3">
            <div>
                <x-form.validation-errors />
            </div>
            @include('control.fragments.general.flash')
            @include('control.fragments.general.flash')
            @livewire('control.services.create-service', [
                'serviceTypes' => $serviceTypes,
                'services' => $services,
            ])
        </div>
    </div>
</x-layouts.authenticated>
