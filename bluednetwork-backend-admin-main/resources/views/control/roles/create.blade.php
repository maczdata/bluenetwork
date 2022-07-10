<x-layouts.authenticated>
    @section('seo')
        <title>Create Role &raquo; {{ config('app.name') }}</title>
        <meta name="description" content="{{ config('app.name') }} Create Role">
    @stop
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Role') }}
        </h2>
    </x-slot>
    @include('control.fragments.general.flash')
    <div>
        <div class="mx-auto p-3">
            <div>
                <x-form.validation-errors />
            </div>
            <form method="post" action="{{ route('control.roles.store') }}" role="form">
                {!! csrf_field() !!}
                <div class="flex-none md:flex gap-0 md:gap-4">
                    <div class="w-full bg-white rounded-lg overflow-hidden shadow-lg px-6 py-4">
                        <div class="flex justify-between mb-6">
                            <div>
                                <h3 class="font-bold text-base mb-1 text-gray-400">
                                    Role
                                </h3>
                                <div class="form-item w-full">
                                    <x-form.label for="name" value="Name" class="font-semibold mb-3">
                                        <x-general.required-field/>
                                    </x-form.label>
                                    <x-form.input 
                                        id="name"
                                        type="text"
                                        name="name"
                                        class="text-sm sm:text-base placeholder-gray-500 rounded-lg border border-gray-400 w-full py-2 focus:outline-none focus:border-indigo-400"
                                    />
                                    <x-form.input-error for="name" class="mt-2"/>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-between mb-6">
                            <div>
                                <h3 class="font-bold text-base mb-1 text-gray-400">
                                    Guard Type
                                </h3>
                                <div class="form-item w-full">
                                    <x-form.label for="name" value="Guard Type" class="font-semibold mb-3">
                                        <x-general.required-field/>
                                    </x-form.label>
                                    <select name="guard_type">
                                        <option value="dashboard">Dashboard (Can only access dashboard)</option>
                                        <option value="frontend">Frontend (Can only access Frontend UI)</option>
                                    </select>
                                    <x-form.input-error for="name" class="mt-2"/>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-between mb-6">
                            <div>
                                <h3 class="font-bold text-base mb-1 text-gray-400">
                                    Permissions
                                </h3>
                                    @foreach ($permissions as $key => $permission)
                                        <div class="form-item w-full m-2">
                                            <x-form.label 
                                                for="{{ 'permission-' . $key }}"
                                                value="{{ $permission->name . ' - ' . $permission->guard_name}}"
                                            >
                                                <input 
                                                    type="checkbox" 
                                                    class="rounded border-gray-300 text-primary shadow-sm focus:border-primary focus:ring lfocus:ring-indigo-200 lfocus:ring-opacity-50' : 'rounded h-4 w-4 text-primary border-blue-gray-300 focus:ring-blue-500"
                                                    name="permissions[]" 
                                                    id="{{ 'permission-' . $key }}" 
                                                    value="{{ $permission->name }}"
                                                /> 
                                            </x-form.label>
                                        </div>
                                    @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="w-auto md:w-1/1 lg:w-1/2 flex">
                    </div>
                </div>
                <x-form.button class="mt-2">
                    {{ __('Create Role') }}
                </x-form.button>
            </form>
            @include('control.fragments.general.flash')
            @include('control.fragments.general.flash')

        </div>
    </div>
</x-layouts.authenticated>
