<x-layouts.authenticated>
    @section('seo')
        <title>Users list &raquo; {{ config('app.name') }}</title>
        <meta name="description" content="{{ config('app.name') }} Users list">
    @stop
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Users list') }}
        </h2>
    </x-slot>
    @include('control.fragments.general.flash')
    <div>
        <div class="mx-auto p-3">
            <div>
                <x-form.validation-errors />
            </div>
            @can('create_user')
                <x-form.button
                    class="m-2"
                    onclick="Livewire.emit('openModal', 'control.users.create-user')"
                >
                {{ __('Add User') }}
                </x-form.button>
            @endcan
            @include('control.fragments.general.flash')
            @include('control.fragments.general.flash')
            @livewire('control.users.list-users')
        </div>
    </div>
</x-layouts.authenticated>
