<div class="md:grid md:grid-cols-3 md:gap-6">
    <x-general.section-title>
        <x-slot name="title">Basic Information</x-slot>
        <x-slot name="description">
            Update your account's basic data.
        </x-slot>
    </x-general.section-title>
    <div class="mt-5 md:mt-0 md:col-span-2">
        <form wire:submit.prevent="updateProfileInformation">
            @csrf
            <div class="px-4 py-5 bg-white sm:p-6 shadow sm:rounded-tl-md sm:rounded-tr-md">

                <div x-data="{photoName: null, photoPreview: null}" class="form-item w-full mb-4">
                    <!-- Profile Photo File Input -->
                    <input type="file" class="hidden"
                           wire:model="profile_image"
                           x-ref="profile_image"
                           x-on:change="
                                    photoName = $refs.profile_image.files[0].name;
                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        photoPreview = e.target.result;
                                    };
                                    reader.readAsDataURL($refs.profile_image.files[0]);
                            "/>

                    <x-form.label for="profile_image" value="{{ __('Photo') }}"/>

                    <!-- Current Profile Photo -->
                    <div class="mt-1 flex items-center">
                        <div class="mt-2" x-show="! photoPreview">
                            <img src="{{ $this->user->user_avatar }}" alt="{{ $this->user->name }}"
                                 class="rounded-full overflow-hidden bg-gray-100 h-20 w-20 object-cover">
                        </div>

                        <!-- New Profile Photo Preview -->
                        <div class="mt-2" x-show="photoPreview">
                            <span class="block rounded-full w-20 h-20"
                                  x-bind:style="'background-size: cover; background-repeat: no-repeat; background-position: center center; background-image: url(\'' + photoPreview + '\');'">
                            </span>
                        </div>

                        <x-form.secondary-button
                            class="mt-2 ml-5 bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            type="button"
                            x-on:click.prevent="$refs.profile_image.click()">
                            {{ __('Select A New Photo') }}
                        </x-form.secondary-button>

                        <x-form.input-error for="profile_image" class="mt-2"/>
                    </div>
                </div>
                <div class="flex flex-col space-y-4 md:space-y-0 md:flex-row md:space-x-4 mb-4">
                    <div class="form-item w-full">
                        <x-form.label for="first_name" value="First name" class="font-semibold mb-3">
                            <x-general.required-field/>
                        </x-form.label>
                        <x-form.input id="first_name" type="text"
                                      class="text-sm sm:text-base placeholder-gray-500 rounded-lg border border-gray-400 w-full py-2 focus:outline-none focus:border-indigo-400"
                                      wire:model.defer="state.first_name" autocomplete="first_name"/>
                        <x-form.input-error for="state.first_name" class="mt-2"/>
                    </div>

                    <div class="form-item w-full">
                        <x-form.label for="last_name" value="Last name" class="font-semibold mb-3">
                            <x-general.required-field/>
                        </x-form.label>
                        <x-form.input id="last_name" type="text"
                                      class="text-sm sm:text-base placeholder-gray-500 rounded-lg border border-gray-400 w-full py-2 focus:outline-none focus:border-indigo-400"
                                      wire:model.defer="state.last_name" autocomplete="last_name"/>
                        <x-form.input-error for="last_name" class="mt-2"/>
                    </div>
                    <div class="form-item w-full">
                        <x-form.label for="email" value="Email Address" class="font-semibold mb-3">
                            <x-general.required-field/>
                        </x-form.label>
                        <x-form.input id="email" type="text"
                                      class="text-sm sm:text-base placeholder-gray-500 rounded-lg border border-gray-400 w-full py-2 focus:outline-none focus:border-indigo-400"
                                      wire:model.defer="state.email" autocomplete="email"/>
                        <x-form.input-error for="email" class="mt-2"/>
                    </div>
                </div>
            </div>
            <div
                class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">
                <x-general.action-message class="mr-3" on="saved">
                    {{ __('Saved.') }}
                </x-general.action-message>
                <x-form.primary-button wire:loading.attr="disabled" wire:loading.class="bg-gray-500"
                                       wire:target="profile_image">
                    {{ __('Save') }}
                </x-form.primary-button>
            </div>
        </form>
    </div>
</div>
