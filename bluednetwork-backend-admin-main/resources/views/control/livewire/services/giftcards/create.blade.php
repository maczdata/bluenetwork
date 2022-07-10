<div class="p-2">
    <x-form.form-section
    method="post"
    title="Add Gift Card"
    description="Create a new gift card"

>
<x-slot name="formencoding">
    multipart/form-data
</x-slot>
    <x-slot name="action">
        {{ route('control.giftCards.store') }}
    </x-slot>
    <x-slot name="content">
        <x-form.validation-errors />
    </x-slot>
    <x-slot name="form">
        <div class="col-span-3 sm:col-span-3">
            <x-form.label for="title" value="{{ __('Title') }}" />
            <x-form.input id="title" type="text" name="title" :value="old('title')" required/>
            <x-form.input-error for="title" class="mt-2" />
        </div>
        <div class="col-span-3 sm:col-span-3">
            <x-form.label for="description" value="{{ __('Description') }}" />
            <x-form.textarea id="description" name="description"  >
            </x-form.textarea>
            <x-form.input-error for="description" class="mt-2" />
        </div>
        <div class="col-span-12 sm:col-span-12">
            <x-form.label for="enabled" value="{{ __('Enabled') }}" />
            <x-form.select id="enabled" name="enabled">
                <x-slot name="slot">
                    <option value="1" selected>Yes</option>
                    <option value="0">No</option>
                </x-slot>
        </x-form.select>
        </div>   
        <div class="col-span-3 sm:col-span-3 ">
            <div class="grid grid-cols-2 mt-2">
                <div class="">
                    <x-form.label for="icon" value="{{ __('Gift Card Icon') }}" />
                    <x-form.input id="icon" type="file" name="icon" required />
                    <x-form.input-error for="icon" class="mt-2" />
                </div>
            </div>
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
