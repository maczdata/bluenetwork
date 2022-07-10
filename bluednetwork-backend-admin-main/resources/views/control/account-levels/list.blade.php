<x-layouts.authenticated>
    @section('seo')
        <title>Account Level &raquo; {{ config('app.name') }}</title>
        <meta name="description" content="{{ config('app.name') }} Account Level list">
    @stop
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Account Level') }}
        </h2>
    </x-slot>
    @include('control.fragments.general.flash')

    <div class="py-4 sm:py-10 mx-2 sm:mx-4 md:mx-5 lg:mx-10">
    <a
    type="button" 
    class="rounded-md bg-blue-500 text-white focus:ring-red-600 px-4 py-2 text-sm" href="account-levels/create/view">Create Account </a>
    <br><br>

        @include('control.fragments.general.flash')
        @livewire('control.account-levels.list-account-level')
    </div>


</x-layouts.authenticated>
