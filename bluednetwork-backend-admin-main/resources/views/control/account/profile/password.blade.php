<div class="md:grid md:grid-cols-3 md:gap-6">
    <x-general.section-title>
        <x-slot name="title">
            {{ __('Update Password') }}
        </x-slot>

        <x-slot name="description">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </x-slot>
    </x-general.section-title>
    <div class="mt-5 md:mt-0 md:col-span-2">
        <form wire:submit.prevent="updatePassword">
            <div class="px-4 py-5 bg-white sm:p-6 shadow sm:rounded-tl-md sm:rounded-tr-md">
                <div class="col-span-6 sm:col-span-4 mb-4">
                    <x-form.label for="current_password" value="{{ __('Current Password') }}" class="font-semibold mb-3"/>
                    <x-form.input id="current_password" type="password" class="mt-1 block w-full"
                                  wire:model.defer="state.current_password" autocomplete="current-password" placeholder="Current password"/>
                    <x-form.input-error for="state.current_password" class="mt-2"/>
                </div>

                <div class="col-span-6 sm:col-span-4 mb-4">
                    <x-form.label for="password" value="{{ __('New Password') }}" class="font-semibold mb-3"/>
                    <x-form.input id="password" type="password" class="mt-1 block w-full"
                                  wire:model.defer="state.password" autocomplete="new-password" placeholder="New password"/>
                    <x-form.input-error for="state.password" class="mt-2"/>
                </div>

                <div class="col-span-6 sm:col-span-4 mb-4">
                    <x-form.label for="password_confirmation" value="{{ __('Confirm Password') }}" class="font-semibold mb-3"/>
                    <x-form.input id="password_confirmation" type="password" class="mt-1 block w-full"
                                  wire:model.defer="state.password_confirmation" autocomplete="new-password" placeholder="Confirm new password"/>
                    <x-form.input-error for="state.password_confirmation" class="mt-2"/>
                </div>
            </div>
            <div
                class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">
                <x-general.action-message class="mr-3" on="saved">
                    {{ __('Saved.') }}
                </x-general.action-message>
                <x-form.primary-button wire:loading.attr="disabled" wire:loading.class="bg-gray-500">
                    {{ __('Save') }}
                </x-form.primary-button>
            </div>
        </form>
    </div>
</div>
