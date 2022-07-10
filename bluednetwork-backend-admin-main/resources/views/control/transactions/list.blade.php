<x-layouts.authenticated>
    @section('seo')
        <title>Transaction list &raquo; {{ config('app.name') }}</title>
        <meta name="description" content="{{ config('app.name') }} transaction list">
    @stop
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Transaction list') }}
        </h2>
    </x-slot>
    @include('control.fragments.general.flash')


    <div class="py-4 sm:py-10 mx-2 sm:mx-4 md:mx-5 lg:mx-10">
        @include('control.fragments.general.flash')
        @livewire('control.transactions.list-transactions')
    </div>

</x-layouts.authenticated>
