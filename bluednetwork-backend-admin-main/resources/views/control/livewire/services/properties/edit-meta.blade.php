<div class="p-3">
    <x-form.form-section
        wire:submit.prevent="update"
        title="Update Meta"
        description="Update meta"
    >
        <x-slot name="action">
            {{ route('control.service.create') }}
        </x-slot>
        <x-slot name="content">
            <x-form.validation-errors />
        </x-slot>
        <x-slot name="form">
            <div class="col-span-12 sm:col-span-12">
                <x-form.label for="key" value="{{ __('Key') }}" />
                <x-form.input id="key"  type="text" name="key" value="{{ $meta->key }}" wire:model="key" required  autocomplete="name" />
                <x-form.input-error for="key" class="mt-2" />
            </div>
            <div class="col-span-12 sm:col-span-12">
                <x-form.label for="key" value="{{ __('Value') }}" />
                <x-form.input id="key"  type="text" name="value" value="{{ $meta->value }}" wire:model="value" required autocomplete="name" />
                <x-form.input-error for="key" class="mt-2" />
            </div>
        </x-slot>
    
        <x-slot name="actions">
            <x-general.action-message class="mr-3" on="saved">
                {{ __('Saved.') }}
            </x-general.action-message>
    
            <x-form.button>
                {{ __('Update Meta') }}
            </x-form.button>
        </x-slot>
    </x-form.form-section>
</div>
