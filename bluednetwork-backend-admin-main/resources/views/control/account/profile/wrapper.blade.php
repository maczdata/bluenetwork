
<x-layouts.authenticated>
    @section('seo')
        <title>Update Profile &raquo; {{ config('app.name') }}</title>
        <meta name="description" content="{{ config('app.name') }} Update Profile">
    @stop
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            @include('control.fragments.general.flash')
            @livewire('control.basic-profile-information-form')
            <x-general.section-border/>


            <div class="mt-10 sm:mt-0">
                @livewire('control.password-form')
            </div>

            <x-general.section-border/>
        </div>
    </div>

</x-layouts.authenticated>
