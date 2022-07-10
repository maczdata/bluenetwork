<x-layouts.authenticated>
    @section('seo')
        <title>Roles &raquo; {{ config('app.name') }}</title>
        <meta name="description" content="{{ config('app.name') }} Order list">
    @stop
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Roles') }}
        </h2>
    </x-slot>
    @include('control.fragments.general.flash')


    <div class="mx-auto p-3">
        <div>
            <x-form.validation-errors />
        </div>
        <a class="inline-flex items-center px-7 py-3 bg-blue-900 border border-transparent rounded-full font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600 active:bg-gray-900 focus:outline-none focus:border-blue-800 focus:ring focus:ring-gray-300 disabled:opacity-25 transition m-2"
        href="{{ route('control.roles.create') }}"
            >
            {{ __('Create Role') }}
            </a>
            <x-form.button
                class="m-2"
                onclick="Livewire.emit('openModal', 'control.roles.permissions.create-permission')"
            >
            {{ __('Add Permission') }}
            </x-form.button>
        @include('control.fragments.general.flash')
        @livewire('control.roles.list-roles')
    </div>

</x-layouts.authenticated>

