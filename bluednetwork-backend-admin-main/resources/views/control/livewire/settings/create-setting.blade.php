<div class="p-2">
    <x-form.form-section method="post" title="Add Setting" description="Create a new settting">
        <x-slot name="formencoding">
            multipart/form-data
        </x-slot>
        <x-slot name="action">
            {{ route('control.settings.store') }}
        </x-slot>
        <x-slot name="content">
            <x-form.validation-errors />
        </x-slot>
        <x-slot name="form">
            <div class="col-span-6 sm:col-span-6">
                <x-form.label for="setting_type" value="{{ __('Setting Type') }}">
                    <x-form.select id="setting_type" name="setting_type_id" required>
                        <x-slot name="slot">
                            <option value=""> Select a setting type </option>
                            @foreach ($settingTypes as $settingType)
                                <option value="{{ $settingType['id'] }}"> {{ $settingType['name'] }}</option>
                            @endforeach
                        </x-slot>
                    </x-form.select>
                </x-form.label>
            </div>
            <div class="col-span-6 sm:col-span-6">
                <x-form.label for="setting" value="{{ __('Setting Data type') }}" />
                <x-form.select id="setting" name="data_type" wire:model="settingDataType" required>
                    <x-slot name="slot">
                        <option value=""> Select the data type </option>
                        <option value="text"> Text</option>
                        <option value="textarea">Textarea</option>
                        <option value="number">Number</option>
                        {{-- <option value="select">Select</option> --}}
                        <option value="file">File/Image</option>
                        <option value="boolean">Boolean</option>
                    </x-slot>
                </x-form.select>
            </div>
            <div class="col-span-4 sm:col-span-4">
                <x-form.label for="name" value="{{ __('Name') }}" />
                <x-form.input id="name" type="text" name="name" :value="old('name')" />
                <x-form.input-error for="name" class="mt-2" />
            </div>
            @if ($settingDataType === 'text')
                <div class="col-span-4 sm:col-span-4">
                    <x-form.label for="value" value="{{ __('Value') }}" />
                    <x-form.input id="value" type="text" name="value" />
                    <x-form.input-error for="value" class="mt-2" />
                </div>
            @elseif($settingDataType === 'textarea')
                <div class="col-span-4 sm:col-span-4">
                    <x-form.label for="value" value="{{ __('Value') }}" />
                    <x-form.textarea id="value" name="value">
                    </x-form.textarea>
                    <x-form.input-error for="value" class="mt-2" />
                </div>
            @elseif($settingDataType === 'number')
                <div class="col-span-4 sm:col-span-4">
                    <x-form.label for="value" value="{{ __('Value') }}" />
                    <x-form.textarea id="value" name="value">
                    </x-form.textarea>
                    <x-form.input-error for="value" class="mt-2" />
                </div>
            @elseif($settingDataType === 'boolean')
            <div class="col-span-4 sm:col-span-4">
                <x-form.label for="value" value="{{ __('Boolean Option') }}" />
                <x-form.select id="value" name="value">
                    <x-slot name="slot">
                        <option value="1" selected>Yes</option>
                        <option value="0">No</option>
                    </x-slot>
                </x-form.select>
            </div>
            @elseif($settingDataType === 'file' || $settingDataType === 'image')
                <div class="col-span-4 sm:col-span-4">
                    <div class="">
                        <x-form.label for="value" value="{{ __('File/Image') }}" />
                        <x-form.input id="icon" type="file" name="value" required />
                        <x-form.input-error for="value" class="mt-2" />
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-12">
                    <div class="grid grid-cols-3 mt-2">
                        <div class="">
                            <x-form.label value="{{ __('File/Image Position') }}" />
                            <x-form.input type="text" value="" name="position" />
                        </div>
                        <div class="ml-3">
                            <x-form.label value="{{ __('File/Image Link') }}" />
                            <x-form.input type="text" name="link"  />
                        </div>
                    </div>
                </div>
            @else
                <div class="col-span-4 sm:col-span-4">
                    <x-form.label for="value" value="{{ __('Value') }}" />
                    <x-form.input id="value" type="text" name="value" />
                    <x-form.input-error for="value" class="mt-2" />
                </div>
            @endif

            <div class="col-span-12 sm:col-span-12">
                <x-form.label for="enabled" value="{{ __('Enabled') }}" />
                <x-form.select id="enabled" name="enabled">
                    <x-slot name="slot">
                        <option value="1" selected>Yes</option>
                        <option value="0">No</option>
                    </x-slot>
                </x-form.select>
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
