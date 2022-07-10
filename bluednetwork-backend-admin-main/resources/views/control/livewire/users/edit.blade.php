<div class="p-3">
    <x-form.form-section method="post" title="Edit User" description="Update an existing user">
        <x-slot name="formencoding">
            multipart/form-data
        </x-slot>
        <x-slot name="otherMethod">
            @method('PUT')
        </x-slot>
        <x-slot name="action">
            {{ route('control.user.update-user', $user->hashId()) }}
        </x-slot>
        <x-slot name="content">
            <x-form.validation-errors />
        </x-slot>
        <x-slot name="form">
            <div class="flex col-span-6 sm:col-span-3">
                <div class="items-center flex">
                    <img src="{{ $user->user_avatar }}" alt="user-avatar" class="object-contain md:object-scale-down"
                        style="width: 100%" />
                    <label for="avatar-image" class="ml-3 text-sm text-blue-500 underline font-bold">
                        <x-form.input id="icon" type="file" name="profile_image" />
                        <span>User Avatar</span>
                    </label>
                </div>
            </div>
            <div class="col-span-6 sm:col-span-3">
                <x-form.label for="name" value="{{ __('First Name') }}" />
                <x-form.input id="first_name" type="text" name="first_name" value="{{ $user->first_name }}" autofocus
                    autocomplete="name" />
                <x-form.input-error for="name" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-3">
                <x-form.label for="name" value="{{ __('Last Name') }}" />
                <x-form.input id="last_name" type="text" name="last_name" value="{{ $user->last_name }}" autofocus
                    autocomplete="name" />
                <x-form.input-error for="name" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-3">
                <x-form.label for="name" value="{{ __('Username') }}" />
                <x-form.input id="username" type="text" name="username" value="{{ $user->username }}" autofocus
                    autocomplete="name" />
                <x-form.input-error for="name" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-3">
                <x-form.label for="email" value="{{ __('Email') }}" />
                <x-form.input id="email" type="email" name="email" value="{{ $user->email }}" />
                <x-form.input-error for="email" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-3">
                <x-form.label for="role" value="{{ __('User Role') }}">
                    <x-form.select id="role" name="role" required>
                        <x-slot name="slot">
                            <option value=""> Select a user role </option>
                            @foreach (App\Models\Common\Role::get() as $role)
                                <option value="{{ $role->name }}"
                                    {{ $user->role === $role->name ? 'selected' : '' }}> {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </x-slot>
                    </x-form.select>
                </x-form.label>
            </div>
            <div class="col-span-6 sm:col-span-6">
                <x-form.label for="name" value="{{ __('Phone Number') }}" />
                <x-form.input id="phone_number" type="tel" name="phone_number" value="{{ $user->phone_number }}"
                    autofocus />
                <x-form.input-error for="name" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-3">
                <x-form.label for="password" value="{{ __('Password') }}" />
                <x-form.input id="password" type="password" name="password" autocomplete="new-password" />
                <x-form.input-error for="password" class="mt-2" />
            </div>
        </x-slot>

        <x-slot name="actions">
            <x-general.action-message class="mr-3" on="saved">
                {{ __('Saved.') }}
            </x-general.action-message>

            <x-form.button>
                {{ __('Update') }}
            </x-form.button>
        </x-slot>
    </x-form.form-section>
</div>
