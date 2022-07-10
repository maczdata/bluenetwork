<div class="p-3">
        <x-form.form-section
            method="post"
            title="Add User"
            description="Create a new user"
        >

            <x-slot name="action">
                {{ route('control.user.create-user') }}
            </x-slot>
            <x-slot name="content">
                <x-form.validation-errors />
            </x-slot>
            <x-slot name="form">
                <div class="col-span-6 sm:col-span-3">
                    <x-form.label for="name" value="{{ __('First Name') }}" />
                    <x-form.input id="first_name" type="text" name="first_name" :value="old('name')" required autofocus autocomplete="name" />
                    <x-form.input-error for="name" class="mt-2" />
                </div>
                <div class="col-span-6 sm:col-span-3">
                    <x-form.label for="name" value="{{ __('Last Name') }}" />
                    <x-form.input id="last_name"  type="text" name="last_name" :value="old('name')" required autofocus autocomplete="name" />
                    <x-form.input-error for="name" class="mt-2" />
                </div>
                <div class="col-span-6 sm:col-span-3">
                    <x-form.label for="name" value="{{ __('Username') }}" />
                    <x-form.input id="username" type="text" name="username" :value="old('name')" required autofocus autocomplete="name" />
                    <x-form.input-error for="name" class="mt-2" />
                </div>
                <div class="col-span-6 sm:col-span-3">
                    <x-form.label for="email" value="{{ __('Email') }}" />
                    <x-form.input id="email" type="email" name="email" :value="old('email')" required />
                    <x-form.input-error for="email" class="mt-2" />
                </div>
                <div class="col-span-6 sm:col-span-3">
                    <x-form.label for="role" value="{{ __('User Role') }}">
                    <x-form.select id="role" name="role" required>
                        <x-slot name="slot">
                            <option value=""> Select a role </option>
                            @foreach (App\Models\Common\Role::get() as $role)
                            <option value="{{ $role->name }}"> {{ ucfirst($role->name) }}</option>
                            @endforeach
                        </x-slot>
                    </x-form.select>
                    </x-form.label>
                </div>
                <div class="col-span-6 sm:col-span-6">
                    <x-form.label for="name" value="{{ __('Phone Number') }}" />
                    <x-form.input id="phone_number" type="tel" name="phone_number" :value="old('name')" required autofocus  />
                    <x-form.input-error for="name" class="mt-2" />
                </div>
                <div class="col-span-6 sm:col-span-3">
                    <x-form.label for="password" value="{{ __('Password') }}" />
                    <x-form.input id="password" type="password" name="password" autocomplete="new-password" required />
                    <x-form.input-error for="password" class="mt-2" />
                </div>

                <div class="col-span-6 sm:col-span-3">
                    <x-form.label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                    <x-form.input id="password_confirmation"  name="password_confirmation" type="password" autocomplete="new-password" required />
                    <x-form.input-error for="password_confirmation" class="mt-2" />
                </div>
            </x-slot>

            <x-slot name="actions">
                <x-general.action-message class="mr-3" on="saved">
                    {{ __('Saved.') }}
                </x-general.action-message>

                <x-form.button>
                    {{ __('Save') }}
                </x-form.button>
            </x-slot>
        </x-form.form-section>
</div>
