<x-layouts.authenticated>
    @section('seo')
        <title>User details &raquo; {{ config('app.name') }}</title>
        <meta name="description" content="{{ config('app.name') }} User details">
        <link type="text/css" href="{{ asset('assets/css/control/main.css') }}" rel="stylesheet">
    @stop
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User details') }}
        </h2>
    </x-slot>

    <div class="py-4 sm:py-10 mx-2 sm:mx-4 md:mx-5 lg:mx-10">
        @include('control.fragments.general.flash')
        <div class="flex-none md:flex gap-0 md:gap-4">
            <div class="w-full bg-white rounded-lg overflow-hidden shadow-lg px-6 py-4">
                <div class="flex justify-between mb-6">
                    <div>
                        <h3 class="font-bold text-base mb-1 text-gray-400">
                            Avatar
                        </h3>
                        <div class="items-center flex">
                            <img src="{{ $user->user_avatar }}" alt="user-avatar"
                                class="object-contain md:object-scale-down" style="width: 100%" />
                        </div>
                    </div>

                </div>
                <div class="flex justify-between mb-6">
                    <div>
                        <h3 class="font-bold text-base mb-1 text-gray-400">
                            User
                        </h3>
                        <div class="text-grey-dark">
                            {{ ucfirst($user->full_name ?? '') }}
                        </div>
                    </div>
                    <div>
                        <h3 class="font-bold text-base mb-1 text-gray-400">
                            Wallet Balance
                        </h3>
                        <div class="text-grey-dark">
                            {{ core()->formatBasePrice($user->wallet_balance ?? 0) }}
                        </div>
                    </div>
                    <div>
                        <h3 class="font-bold text-base mb-1 text-gray-400">
                            Role
                        </h3>
                        <div class="text-grey-dark">
                            {{ $user->role }}
                        </div>
                    </div>
                </div>
                <div class="flex justify-between mb-6">
                    <div>
                        <h3 class="font-bold text-base mb-1 text-gray-400">
                            Email
                        </h3>
                        <div class="text-grey-dark">
                            {{ $user->email }}
                        </div>
                    </div>
                    <div>
                        <h3 class="font-bold text-base mb-1 text-gray-400">
                            Phone
                        </h3>
                        <div class="text-grey-dark">
                            {{ $user->phone_number }}
                          
                        </div>
                    </div>
                </div>
                <div class="flex justify-between mb-6">
                    <div>
                        <h3 class="font-bold text-base mb-1 text-gray-400">
                            International Phone
                        </h3>
                        <div class="text-grey-dark">
                            {{ 'International Number: '. $user->intl_phone_number }}
                        </div>
                    </div>
                    <div>
                        <h3 class="font-bold text-base mb-1 text-gray-400">
                            Account Level
                        </h3>
                        <div class="text-grey-dark">
                            {{ $user->profile->accountLevel->name }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-full bg-white rounded-lg overflow-hidden shadow-lg px-6 py-4">
                <x-form.form-section method="post" title="Fund User's Wallet" description="Fund user's wallet">
                    <x-slot name="action">
                        {{ route('control.user.fund-wallet', ['user_id' => $user->hashId()]) }}
                    </x-slot>
                    <x-slot name="content">
                        <x-form.validation-errors />
                    </x-slot>
                    <x-slot name="form">
                        <div class="col-span-6 sm:col-span-3">
                            <x-form.label for="amount" value="{{ __('Amount') }}" />
                            <x-form.input id="amount" type="number" name="amount" :value="old('amount')" required
                                autofocus />
                            <x-form.input-error for="amount" class="mt-2" />
                        </div>
                        <div>
                            <x-form.input id="mode_of_payment" type="hidden" name="mode_of_payment" value="system"
                                required autofocus />
                        </div>
                    </x-slot>
                    @can('update_user')
                        <x-slot name="actions">
                            <x-form.button>
                                {{ __('Fund User\'s wallet') }}
                            </x-form.button>
                        </x-slot>
                    @endcan
                </x-form.form-section>

            </div>
            <div class="w-auto md:w-1/1 lg:w-1/2 flex"></div>
        </div>

        <div class="flex-none md:flex gap-0 md:gap-4 mt-2">
            <div class="w-full bg-white rounded-lg overflow-hidden shadow-lg px-6 py-4">
                <x-form.form-section method="post" title="Update user's role and permissions"
                    description="Update user's role and permissions">
                    <x-slot name="action">
                        {{ route('control.user.update-role-permissions', ['user_id' => $user->hashId()]) }}
                    </x-slot>
                    <x-slot name="content">
                        <x-form.validation-errors />
                    </x-slot>
                    <x-slot name="form">
                        <div class="">Authorizations</div>
                        <div class="col-start-2 col-span-7">
                            <div class="grid grid-cols-2 gap-2">
                                <div class="">
                                    <div class="card card-accent-primary">
                                        <div class="card-header">
                                            @lang('Roles')
                                        </div>
                                        <div class="card-body">
                                            @if ($roles->count())
                                                @foreach ($roles as $role)
                                                    <div class="card">
                                                        <div class="card-header">
                                                            <div class="checkbox">
                                                                <label class="block font-medium text-sm text-gray-700"
                                                                    for="{{ 'role-' . $role->id }}">
                                                                    <input type="radio"
                                                                        class="rounded border-gray-300 text-primary shadow-sm focus:border-primary focus:ring lfocus:ring-indigo-200 lfocus:ring-opacity-50' : 'rounded h-4 w-4 text-primary border-blue-gray-300 focus:ring-blue-500"
                                                                        name="role" id="{{ 'role-' . $role->id }}"
                                                                        value="{{ $role->name }}"
                                                                        @if (in_array($role->name, $user->getRoleNames()->toArray())) checked @endif />
                                                                    {{ ucwords($role->name) . '(' . $role->guard_name . ')' }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="card-body">
                                                            @if ($role->id != 1)
                                                                @if ($role->permissions->count())
                                                                    @foreach ($role->permissions as $permission)
                                                                        <i
                                                                            class="far fa-check-circle mr-1"></i>{{ $permission->name }}&nbsp;
                                                                    @endforeach
                                                                @else
                                                                    None
                                                                @endif
                                                            @else
                                                                All Permissions
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <!--card-->
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="">
                                    <div class="card card-accent-info">
                                        <div class="card-header">
                                            Permissions
                                        </div>
                                        <div class="card-body">
                                            @if ($permissions->count())
                                                @foreach ($permissions as $permission)
                                                    <div class="checkbox">
                                                        <label class="block font-medium text-sm text-gray-700 mb-2"
                                                            for="{{ 'permission-' . $permission->id }}">
                                                            <input type="checkbox"
                                                                class="rounded border-gray-300 text-primary shadow-sm focus:border-primary focus:ring lfocus:ring-indigo-200 lfocus:ring-opacity-50' : 'rounded h-4 w-4 text-primary border-blue-gray-300 focus:ring-blue-500"
                                                                name="permissions[]"
                                                                id="{{ 'permission-' . $permission->id }}"
                                                                value="{{ $permission->name }}"
                                                                @if (in_array($permission->name, $user->getPermissionNames()->toArray())) checked @endif />
                                                            {{ $permission->name }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </x-slot>
                    @can('update_user')
                        <x-slot name="actions">
                            <x-form.button>
                                {{ __('Update User\'s role/permissions') }}
                            </x-form.button>
                        </x-slot>
                    @endcan
                </x-form.form-section>
            </div>
        </div>

        <div class="flex-none md:flex gap-0 md:gap-4 mt-2">
            <div class="w-full bg-white rounded-lg overflow-hidden shadow-lg px-6 py-4">
                <div class="flex justify-between">
                    <div class="">
                        <div class="card card-accent-primary">
                            <div class="card-header">
                                Verify Email
                            </div>
                            <div class="card-body">
                                <div class="m-2">
                                    <span class="flex mb-2">
                                        Current Status: @include('control.livewire.general.boolean', ['boolean' => $user?->email_verified_at ? true : false])
                                  </span>
                                    @if ($user->email_verified_at)
                                        Date Verified: {{ $user->email_verified_at }}
                                    @endif
                                </div>
                            </div>
                            <div class="card-footer">
                                <span>
                                    <button type="button"
                                    onclick="Livewire.emit('openModal', 'control.users.approve-verification',  {{ json_encode([$user->hashId(), 'email']) }} )"
                                        class="inline-flex items-center px-4 py-2
                                         bg-primary border border-transparent rounded-md 
                                         font-semibold text-xs text-white uppercase 
                                         tracking-widest 
                                         hover:bg-primary-dark active:bg-gray-900 
                                         focus:outline-none focus:border-primary-faded 
                                         focus:ring focus:ring-gray-300 disabled:opacity-25 
                                         transition"
                                        {{ $user->email_verified_at ? 'disabled' : '' }}>
                                        Accept
                                    </button>
                                </span>
                                <span>
                                    <button 
                                    type='button' 
                                    onclick="Livewire.emit('openModal', 'control.users.reject-verification',  {{ json_encode([$user->hashId(), 'email']) }} )"
                                    class='inline-flex items-center justify-center px-4 
                                    py-2 bg-red-600 border border-transparent 
                                    rounded-md font-semibold text-xs text-white
                                     uppercase tracking-widest hover:bg-red-500
                                      focus:outline-none focus:border-red-700 
                                      focus:ring focus:ring-red-200 active:bg-red-600
                                       disabled:opacity-25 transition'
                                        {{ $user->email_verified_at ? '' : 'disabled' }}>Reject</button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <div class="card card-accent-primary">
                            <div class="card-header">
                                Verify Phone
                            </div>
                            <div class="card-body">
                                <div class="m-2">
                                    <span class="flex mb-2">
                                        Current Status: @include('control.livewire.general.boolean', ['boolean' => $user?->phone_verified_at ? true : false])
                                    </span>
                                    @if ($user->phone_verified_at)
                                        Date Verified: {{ $user->phone_verified_at }}
                                    @endif
                                </div>
                            </div>
                            <div class="card-footer">
                                <span>
                                    <button type="button"
                                        onclick="Livewire.emit('openModal', 'control.users.approve-verification',  {{ json_encode([$user->hashId(), 'phone']) }})"
                                        class="inline-flex items-center px-4 py-2
                                         bg-primary border border-transparent rounded-md 
                                         font-semibold text-xs text-white uppercase 
                                         tracking-widest 
                                         hover:bg-primary-dark active:bg-gray-900 
                                         focus:outline-none focus:border-primary-faded 
                                         focus:ring focus:ring-gray-300 disabled:opacity-25 
                                         transition"
                                        {{ $user->phone_verified_at ? 'disabled' : '' }}>
                                        Accept
                                    </button>
                                </span>
                                <span>
                                    <button 
                                        type='button'
                                        onclick="Livewire.emit('openModal', 'control.users.reject-verification',  {{ json_encode([$user->hashId(), 'phone']) }})"
                                        class='inline-flex items-center justify-center px-4 
                                        py-2 bg-red-600 border border-transparent 
                                        rounded-md font-semibold text-xs text-white
                                        uppercase tracking-widest hover:bg-red-500
                                        focus:outline-none focus:border-red-700 
                                        focus:ring focus:ring-red-200 active:bg-red-600
                                        disabled:opacity-25 transition'
                                        {{ $user->phone_verified_at ? '' : 'disabled' }}>Reject</button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="card card-accent-primary">
                            <div class="card-header">
                                Verify BVN
                            </div>
                            <div class="card-body">
                                <div class="m-2">
                                    <span class="flex mb-2">
                                        Current Status: @include('control.livewire.general.boolean', ['boolean' => $user?->profile?->bvn_verified_at ? true : false])
                                    </span>
                                    @if ($user->profile?->bvn_verified_at)
                                        Date Verified: {{ $user->profile?->bvn_verified_at }}
                                    @endif
                                </div>
                            </div>
                            <div class="card-footer">
                                <span>
                                    <button type="button"
                                    onclick="Livewire.emit('openModal', 'control.users.approve-verification',  {{ json_encode([$user->hashId(), 'bvn']) }} )"
                                        
                                        class="inline-flex items-center px-4 py-2
                                         bg-primary border border-transparent rounded-md 
                                         font-semibold text-xs text-white uppercase 
                                         tracking-widest 
                                         hover:bg-primary-dark active:bg-gray-900 
                                         focus:outline-none focus:border-primary-faded 
                                         focus:ring focus:ring-gray-300 disabled:opacity-25 
                                         transition"
                                        {{ $user->profile->bvn_verified_at ? 'disabled' : '' }}>
                                        Accept
                                    </button>
                                </span>
                                <span>
                                    <button 
                                    type='button' 
                                    onclick="Livewire.emit('openModal', 'control.users.reject-verification',  {{ json_encode([$user->hashId(), 'bvn']) }} )"
                                    class='inline-flex items-center justify-center px-4 
                                    py-2 bg-red-600 border border-transparent 
                                    rounded-md font-semibold text-xs text-white
                                     uppercase tracking-widest hover:bg-red-500
                                      focus:outline-none focus:border-red-700 
                                      focus:ring focus:ring-red-200 active:bg-red-600
                                       disabled:opacity-25 transition'
                                        {{ $user->profile->bvn_verified_at ? '' : 'disabled' }}>Reject</button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <div class="card card-accent-primary">
                            <div class="card-header">
                                Verify Proof of Address
                            </div>
                            <div class="card-body">
                                <div class="m-2">
                                    <span class="flex mb-2">
                                        Current Status: @include('control.livewire.general.boolean', ['boolean' => $user?->profile?->proof_of_address_verified_at ? true : false])
                                    </span>
                                    @if ($user?->profile?->address)
                                        <p>Address: {{ $user?->profile?->address }}</p>
                                    @endif
                                    @if ($user?->profile?->proof_of_address_type)
                                        <p>Proof of Address Type: {{ $user?->profile?->proof_of_address_type }}</p>
                                    @endif
                                    @if ($user?->profile?->proof_of_address_verified_at)
                                        <p>Date Verified: {{ $user?->profile?->proof_of_address_verified_at }}</p>
                                    @endif

                                    @if ($user->getMedia('proof_of_address')->count() > 0)
                                    <br>
                                        <p> File: 
                                            <a 
                                            class="rounded-sm bg-gray-500 text-white ml-3 mt-4 p-2 font-bold items-center hover:bg-gray-700"
                                            href="{{ $user->getMedia('proof_of_address')?->first()->original_url }}" 
                                            target="_blank" >View File</a>
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div class="card-footer">
                                <span>
                                    <button type="button"
                                        onclick="Livewire.emit('openModal', 'control.users.approve-verification',  {{ json_encode([$user->hashId(), 'proofOfAddress']) }} )"
                                        class="inline-flex items-center px-4 py-2
                                         bg-primary border border-transparent rounded-md 
                                         font-semibold text-xs text-white uppercase 
                                         tracking-widest 
                                         hover:bg-primary-dark active:bg-gray-900 
                                         focus:outline-none focus:border-primary-faded 
                                         focus:ring focus:ring-gray-300 disabled:opacity-25 
                                         transition"
                                        {{ $user->profile->proof_of_address_verified_at ? 'disabled' : '' }}>
                                        Accept
                                    </button>
                                </span>
                                <span>
                                    <button 
                                        type='button' 
                                        onclick="Livewire.emit('openModal', 'control.users.reject-verification',  {{ json_encode([$user->hashId(), 'proofOfAddress']) }} )"
                                        class='inline-flex items-center justify-center px-4 
                                    py-2 bg-red-600 border border-transparent 
                                    rounded-md font-semibold text-xs text-white
                                     uppercase tracking-widest hover:bg-red-500
                                      focus:outline-none focus:border-red-700 
                                      focus:ring focus:ring-red-200 active:bg-red-600
                                       disabled:opacity-25 transition'
                                        {{ $user?->profile?->proof_of_address_verified_at ? '' : 'disabled' }}>Reject</button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="card card-accent-primary">
                            <div class="card-header">
                                Verify Identity
                            </div>
                            <div class="card-body">
                                <div class="m-2">
                                    <span class="flex mb-2">
                                        Current Status: @include('control.livewire.general.boolean', ['boolean' => $user?->profile?->identity_verified_at ? true : false])
                                    </span>
                                    @if ($user?->profile?->date_of_birth)
                                    <p> Date of Birth: {{ $user?->profile?->date_of_birth }}</p>
                                    @endif
                                    @if ($user?->profile?->proof_of_identity_type)
                                    <p> Proof of identity type: {{ $user?->profile?->proof_of_identity_type }}</p>
                                    @endif
                                    @if ($user?->profile?->proof_of_identity_number)
                                    <p> Proof of Identity Number: {{ $user?->profile?->proof_of_identity_number }}</p>
                                    @endif
                                    @if ($user?->profile?->identity_verified_at)
                                    <p> Date Verified: {{ $user?->profile?->identity_verified_at }}</p>
                                    @endif
                                    @if ($user->getMedia('proof_of_identity')->count() > 0)
                                    <br>
                                        <p> Proof of identity: 
                                            <a 
                                            class="rounded-sm bg-gray-500 text-white ml-3 mt-4 p-2 font-bold items-center hover:bg-gray-700"
                                            href="{{ $user->getMedia('proof_of_identity')->first()->original_url }}" 
                                            target="_blank" >View File</a>
                                        </p>
                                    @endif
                                    @if ($user->getMedia('passport_photograph')->count() > 0)
                                    <br>
                                        <p>Passport Photograph: 
                                            <a 
                                            class="rounded-sm bg-gray-500 text-white ml-4 mt-2 p-2 font-bold items-center hover:bg-gray-700"
                                            href="{{ $user->getMedia('passport_photograph')->first()->original_url }}" 
                                            target="_blank" >View Passport</a>
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div class="card-footer">
                                <span>
                                    <button 
                                        type="button"
                                        onclick="Livewire.emit('openModal', 'control.users.approve-verification',  {{ json_encode([$user->hashId(), 'identity']) }} )"
                                        class="inline-flex items-center px-4 py-2
                                         bg-primary border border-transparent rounded-md 
                                         font-semibold text-xs text-white uppercase 
                                         tracking-widest 
                                         hover:bg-primary-dark active:bg-gray-900 
                                         focus:outline-none focus:border-primary-faded 
                                         focus:ring focus:ring-gray-300 disabled:opacity-25 
                                         transition"
                                        {{ $user->profile->identity_verified_at ? 'disabled' : '' }}>
                                        Accept
                                    </button>
                                </span>
                                
                                <span>
                                    <button 
                                    type='button' 
                                    onclick="Livewire.emit('openModal', 'control.users.reject-verification',  {{ json_encode([$user->hashId(), 'identity']) }} )"
                                    class='inline-flex items-center justify-center px-4 
                                    py-2 bg-red-600 border border-transparent 
                                    rounded-md font-semibold text-xs text-white
                                     uppercase tracking-widest hover:bg-red-500
                                      focus:outline-none focus:border-red-700 
                                      focus:ring focus:ring-red-200 active:bg-red-600
                                       disabled:opacity-25 transition'
                                        {{ $user?->profile?->identity_verified_at ? '' : 'disabled' }}>Reject</button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layouts.authenticated>
