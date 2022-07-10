<div class="p-3">
    <x-form.form-section
        wire:submit.prevent="update"
        title="{{ 'Update Add on'}}"
        description="Update addon"
    >
        <x-slot name="content">
            <x-form.validation-errors />
        </x-slot>
        <x-slot name="form">
            <div class="col-span-12 sm:col-span-12">
                <x-form.label for="title" value="{{ __('Title') }}" />
                <x-form.input id="title"  type="text" name="title" value="{{ $addon->title }}" wire:model="title" />
                <x-form.input-error for="title" class="mt-2" />
            </div>
            <div class="col-span-12 sm:col-span-12">
                <x-form.label for="description" value="{{ __('Description') }}" />
                <x-form.textarea id="description" name="description" wire:model="description" >
                    {{ $addon->description }}
                </x-form.textarea>
                <x-form.input-error for="description" class="mt-2" />
            </div>
            <div class="col-span-12 sm:col-span-12">
                <x-form.label for="price" value="{{ __('Price') }}" />
                <x-form.input id="price"  type="number" name="price" value="{{ $addon->price }}" wire:model="price" />
                <x-form.input-error for="price" class="mt-2" />
            </div>
            <div class="col-span-12 sm:col-span-12">
                <x-form.label for="enabled" value="{{ __('Enabled') }}" />
                <x-form.select id="enabled" name="enabled" wire:model="enabled">
                    <x-slot name="slot">
                        <option value="1" {{ $addon->enabled === '1' ? 'selected': '' }}>Yes</option>
                        <option value="0" {{ $addon->enabled != '1' ? 'selected': '' }}>No</option>
                    </x-slot>
                </x-form.select>
            </div>  
        </x-slot>
    
        <x-slot name="actions">
            <x-general.action-message class="mr-3" on="saved">
                {{ __('Updated.') }}
            </x-general.action-message>
            <x-form.button>
                {{ __('Update Add On') }}
            </x-form.button>
        </x-slot>
    </x-form.form-section>
</div>
