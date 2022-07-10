<div class="p-3">
    <x-form.form-section method="post" title="{{ 'Create permission' }}" description="Create a new permission">
        <x-slot name="action">
            {{ route('control.roles.permissions.store') }}
        </x-slot>
        <x-slot name="content">
            <x-form.validation-errors />
        </x-slot>
        <x-slot name="form">
            <div class="">
                <div class="col-span-6 sm:col-span-3">
                    <x-form.label for="name" value="{{ __('Name') }}" />
                    <x-form.input id="name" type="text" name="name" :value="old('name')" required autocomplete="name" />
                    <x-form.input-error for="key" class="mt-2" />
                </div>
                <div class="col-span-6 sm:col-span-3 w-full mt-2">
                    <div class="form-item">
                        <x-form.label for="name" value="Guard Type" class="font-semibold mb-3">
                            <x-general.required-field />
                        </x-form.label>
                        <select name="guard_type">
                            <option value="dashboard">Dashboard (Can only access dashboard)</option>
                            <option value="frontend">Frontend (Can only access Frontend UI)</option>
                        </select>
                        <x-form.input-error for="name" class="mt-2" />
                    </div>
                </div>
            </div>

        </x-slot>

        <x-slot name="actions">
            <x-general.action-message class="mr-3" on="saved">
                {{ __('Saved.') }}
            </x-general.action-message>

            <x-form.button>
                {{ __('Save Permission') }}
            </x-form.button>
        </x-slot>
    </x-form.form-section>
</div>
