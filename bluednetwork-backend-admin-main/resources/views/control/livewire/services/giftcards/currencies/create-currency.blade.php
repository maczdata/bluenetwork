<div class="p-2">
    <x-form.form-section
    method="post"
    title="Add Currency to Gift Card"
    description="Add currency to gift card"

>
    <x-slot name="action">
        {{ route('control.giftCards.store') }}
    </x-slot>
    <x-slot name="content">
        <x-form.validation-errors />
    </x-slot>
    <x-slot name="form">
        <x-form.input id="title" type="hidden" name="title" :value="old('title')" required/>
        <div class="col-span-12 sm:col-span-12">
            <x-form.label for="currency" value="{{ __('Select Currency') }}" />
            <x-form.select id="currency" name="currency">
                <x-slot name="slot">
                    <option value="" selected>{{ __('Select Currency') }}</option>
                    @foreach($currencies as $currency)
                    <option value="{{ $currency->id }}">{{ $currency->name }}</option>
                    @endforeach
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
