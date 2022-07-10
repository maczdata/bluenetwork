<x-layouts.authenticated>
    @section('seo')
        <title>Order list &raquo; {{ config('app.name') }}</title>
        <meta name="description" content="{{ config('app.name') }} Order list">
    @stop
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Order list') }}
        </h2>
    </x-slot>
    @include('control.fragments.general.flash')


    <div class="mx-auto p-3">
        <div>
            <x-form.validation-errors />
        </div>
        @can('view_order')
            @can('create_order')
                <x-form.button class="m-2" onclick="Livewire.emit('openModal', 'control.orders.create-order')">
                    {{ __('Add Order') }}
                </x-form.button>
            @endcan
            @include('control.fragments.general.flash')
            @livewire('control.orders.list-orders')
        @endcan
    </div>

</x-layouts.authenticated>
