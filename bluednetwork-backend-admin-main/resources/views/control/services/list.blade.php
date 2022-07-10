<x-layouts.authenticated>
    @section('seo')
        <title>Service list &raquo; {{ config('app.name') }}</title>
        <meta name="description" content="{{ config('app.name') }} Service list">
    @stop
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Service list') }}
        </h2>
    </x-slot>
    @include('control.fragments.general.flash')
    <div class="py-4 sm:py-10 mx-2 sm:mx-4 md:mx-5 lg:mx-10">
        @can('create_service')
            <a class="inline-flex items-center px-7 py-3 bg-blue-900 border border-transparent rounded-full font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600 active:bg-gray-900 focus:outline-none focus:border-blue-800 focus:ring focus:ring-gray-300 disabled:opacity-25 transition m-2"
                    href="{{ route('control.service.create') }}"
                >
                {{ __('Create Service') }}
            </a>
        @endcan
        @include('control.fragments.general.flash')
        @livewire('control.services.list-services')
    </div>

</x-layouts.authenticated>
