<x-layouts.authenticated>
    @section('seo')
        <title>Coupons &raquo; {{ config('app.name') }}</title>
        <meta name="description" content="{{ config('app.name') }} Coupon list">
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
        <x-form.button class="m-2" onclick="Livewire.emit('openModal', 'control.coupons.create-coupon')">
            {{ __('Create Coupon') }}
        </x-form.button>
        @include('control.fragments.general.flash')
        <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg px-4 py-2">
            @livewire('control.coupons.list-coupon')
        </div>
    </div>

</x-layouts.authenticated>
