<x-layouts.authenticated>
    @section('seo')
        <title>Update Gift Card  &raquo; {{ config('app.name') }}</title>
        <meta name="description" content="{{ config('app.name') }} Update Service Variant">
    @stop
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Update Gift Card') }}
        </h2>
    </x-slot>
    @include('control.fragments.general.flash')
    <div>
        <div class="mx-auto p-3">
            <div>
                <x-form.validation-errors />
            </div>
            <div class="flex-none md:flex gap-0 md:gap-4">
                <div class="w-full bg-white rounded-lg overflow-hidden shadow-lg px-6 py-4">
                    <div class="flex justify-between mb-6">
                        <div>
                            <div class="text-grey-dark">
                                <div class="flex flex-row items-center">
                                    <img src="{{ $giftCard->gift_card_image }}" class = "w-30a h-40" />
                                 </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-between mb-6">
                        <div>
                            <h3 class="font-bold text-base mb-1 text-gray-400">
                               Title
                            </h3>
                            <div class="text-grey-dark">
                                {{ ucfirst($giftCard->title) }}
                            </div>
                        </div>
                        <div>
                            <h3 class="font-bold text-base mb-1 text-gray-400">
                                Description
                            </h3>
                            <div class="text-grey-dark">
                                {{ $giftCard->description }}
                            </div>
                        </div>
                        <div>
                            <h3 class="font-bold text-base mb-1 text-gray-400">
                                Enabled
                            </h3>
                            <div class="text-grey-dark">
                                @include('control.livewire.general.boolean', ['boolean' => $giftCard->enabled])
                            </div>
                        </div>
                    </div>
                    <x-form.button
                        class="m-2"
                        onclick="Livewire.emit('openModal', 'control.services.gift-cards.edit-gift-card', {{ json_encode([
                            $giftCard->hashId()
                        ]) }})"
                        >
                        {{ __('Update Gift Card') }}
                    </x-form.button>
                </div>
                
                <div class="w-auto md:w-1/1 lg:w-1/2 flex">
                </div>
            </div>
            <div class="flex-none md:flex gap-0 md:gap-4 mt-2">
                <div class="w-full bg-white rounded-lg overflow-hidden shadow-lg px-6 py-4">
                    <div class="flex justify-between mb-6">
                        <div>
                            <h2 class="font-bold text-base mb-1 text-gray-600">
                                Gift Card Categories
                            </h2>
                        </div>
                    </div>
                    @if($giftCard->categories)
                    @foreach($giftCard->categories as $category)
                    <div class="flex justify-between m-3">
                        <div>
                            <h3 class="font-bold text-base mb-1 text-gray-400">
                                Label
                            </h3>
                            <div class="text-grey-dark">
                                {{ $category->categoryOfGiftCard->title }}
                            </div>
                        </div>
                        <div>
                            <h3 class="font-bold text-base mb-1 text-gray-400">
                                Enabled
                            </h3>
                            <div class="text-grey-dark">
                                 @include('control.livewire.general.boolean', ['boolean' => $category->categoryOfGiftCard->enabled])
                            </div>
                        </div>
                        @if($category->categoryOfGiftCard->children)
                        <div>
                            <h3 class="font-bold text-base mb-1 text-gray-400">
                                Sub-Categories
                            </h3>
                            <div class="text-grey-dark">
                                <ul class="max-h-60 overflow-y-auto select-none list-disc space-y-1 ">
                                @foreach($category->categoryOfGiftCard->children  as $subCategory)
                                    <li>{{ $subCategory->title }}</li>
                                @endforeach
                                </ul>
                            </div>
                        </div>
                        @endif
                        <div>
                            <x-form.danger-button 
                            class="inline-flex items-center px-4 py-2 bg-primary 
                            border border-transparent rounded-md font-semibold text-xs
                             text-white uppercase tracking-widest hover:bg-primary-dark
                              active:bg-gray-900 focus:outline-none focus:border-primary-faded
                               focus:ring focus:ring-gray-300 disabled:opacity-25 transition ml-2" 
                               onclick="Livewire.emit('openModal', 'control.services.gift-cards.categories.edit-category',  {{ json_encode([$category->hashId()]) }} )"
                                >
                                <i class="fa fa-edit"></i>
                            </x-form.danger-button>
                        <x-form.danger-button
                         onclick="Livewire.emit('openModal', 'control.services.gift-cards.categories.delete-category',  {{ json_encode([$category->hashId()]) }} )"
                            >
                            <i class="fa fa-trash-alt"></i>
                        </x-form.danger-button>
                        </div>
                    </div>
                    <hr/>
                    @endforeach
                    @endif
                    <x-form.button
                        class="m-2"
                        onclick="Livewire.emit('openModal', 'control.services.gift-cards.categories.create-category', {{ json_encode([$giftCard->hashId(), 'service-variant']) }})"
                        >
                        {{ __('Add Category to Gift Card') }}
                    </x-form.button>
                </div>
                <div class="w-auto md:w-1/1 lg:w-1/2 flex">
                </div>
            </div>
            <div class="flex-none md:flex gap-0 md:gap-4 mt-2">
                <div class="w-full bg-white rounded-lg overflow-hidden shadow-lg px-6 py-4">
                    <div class="flex justify-between mb-6">
                        <div>
                            <h2 class="font-bold text-base mb-1 text-gray-600">
                                Gift Card Currencies
                            </h2>
                        </div>
                    </div>
                    @if($giftCard->currencies)
                    @foreach($giftCard->currencies as $currency)
                    <div class="flex justify-between m-3">
                        <div>
                            <h3 class="font-bold text-base mb-1 text-gray-400">
                                Currency
                            </h3>
                            <div class="text-grey-dark">
                                {{ $currency->currency->name }}
                            </div>
                        </div>
                        <div>
                            {{-- <x-form.danger-button 
                            class="inline-flex items-center px-4 py-2 bg-primary 
                            border border-transparent rounded-md font-semibold text-xs
                             text-white uppercase tracking-widest hover:bg-primary-dark
                              active:bg-gray-900 focus:outline-none focus:border-primary-faded
                               focus:ring focus:ring-gray-300 disabled:opacity-25 transition ml-2" 
                               onclick="Livewire.emit('openModal', 'control.services.gift-cards.currencies.edit-currency',  {{ json_encode([$currency->hashId()]) }} )"
                                >
                                <i class="fa fa-edit"></i>
                            </x-form.danger-button> --}}
                        <x-form.danger-button
                         onclick="Livewire.emit('openModal', 'control.services.gift-cards.currencies.delete-currency',  {{ json_encode([$currency->hashId()]) }} )"
                            >
                            <i class="fa fa-trash-alt"></i>
                        </x-form.danger-button>
                        </div>
                    </div>
                    <hr/>
                    @endforeach
                    @endif
                    <x-form.button
                        class="m-2"
                        onclick="Livewire.emit('openModal', 'control.services.gift-cards.currencies.create-currency', {{ json_encode([$giftCard->hashId(), 'service-variant']) }})"
                        >
                        {{ __('Add Currency to Gift Card') }}
                    </x-form.button>
                </div>
                <div class="w-auto md:w-1/1 lg:w-1/2 flex">
                </div>
            </div>
            @include('control.fragments.general.flash')
            @include('control.fragments.general.flash')

        </div>
    </div>
</x-layouts.authenticated>
