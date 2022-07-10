<div class="p-3">
    <x-form.form-section
        wire:submit.prevent="update"
        title="{{ 'Update Feature'}}"
        description="Update feature title"
    >
        <x-slot name="content">
            <x-form.validation-errors />
        </x-slot>
        <x-slot name="form">
            <div class="col-span-12 sm:col-span-12">
                <x-form.label for="title" value="{{ __('Title') }}" />
                <x-form.input id="title"  type="text" name="title" value="{{ $feature->title }}" wire:model="title" />
                <x-form.input-error for="title" class="mt-2" />
            </div>
        </x-slot>
    
        <x-slot name="actions">
            <x-general.action-message class="mr-3" on="saved">
                {{ __('Saved.') }}
            </x-general.action-message>
    
            <x-form.button>
                {{ __('Update Feature') }}
            </x-form.button>
        </x-slot>
    </x-form.form-section>
</div>
