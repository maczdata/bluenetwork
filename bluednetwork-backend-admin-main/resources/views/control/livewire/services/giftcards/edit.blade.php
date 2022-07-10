<div class="p-2">
    <x-form.form-section
    method="post"
    title="Update Gift Card"
    description="Update Gift Card"
>
    <x-slot name="formencoding">
        multipart/form-data
    </x-slot>
    <x-slot name="otherMethod">
        @method('PUT')
    </x-slot>
    <x-slot name="action">
        {{ route('control.giftCards.update', ['giftcard_id' => $giftCard->hashId()]) }}
    </x-slot>
    <x-slot name="content">
        <x-form.validation-errors />
    </x-slot>
    <x-slot name="form">
        <div class="flex">
            <div class="items-center flex">
                <img
                  src="{{ $giftCard->gift_card_image }}"
                  alt="giftCard-image"
                  class="object-contain md:object-scale-down"
                  style="width: 100%"
                />
              <label
                for="avatar-image"
                class="ml-3 text-sm text-blue-500 underline font-bold"
              >
              <x-form.input id="icon" type="file" name="icon" />
                <span>Gift Card Icon</span>
              </label>
            </div>
          </div>
        <div class="col-span-6 sm:col-span-6">
            <x-form.label for="title" value="{{ __('Title') }}" />
            <x-form.input id="title" type="text" name="title" value="{{ $giftCard->title }}" />
            <x-form.input-error for="title" class="mt-2" />
        </div>
        <div class="col-span-6 sm:col-span-6">
            <x-form.label for="description" value="{{ __('Description') }}" />
            <x-form.textarea id="description" name="description">
                {{ $giftCard->description }}
            </x-form.textarea>
            <x-form.input-error for="description" class="mt-2" />
        </div>
        <div class="col-span-6 sm:col-span-6">
            <x-form.label for="enabled" value="{{ __('Enabled') }}" />
            <x-form.select id="enabled" name="enabled">
                <x-slot name="slot">
                    <option value="1" {{ $giftCard->enabled === '1' ? 'selected': '' }}>Yes</option>
                    <option value="0" {{ $giftCard->enabled != '1' ? 'selected': '' }}>No</option>
                </x-slot>
            </x-form.select>
        </div>  
    </x-slot>

    <x-slot name="actions">
        <x-general.action-message class="mr-3" on="saved">
            {{ __('Updated.') }}
        </x-general.action-message>

        <x-form.button>
            {{ __('Update') }}
        </x-form.button>
    </x-slot>
</x-form.form-section>
</div>
