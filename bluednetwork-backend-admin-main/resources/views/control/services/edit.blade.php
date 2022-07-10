<x-layouts.authenticated>
    @section('seo')
        <title>Edit Service &raquo; {{ config('app.name') }}</title>
        <meta name="description" content="{{ config('app.name') }} Edit Service">
    @stop
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Service') }}
        </h2>
    </x-slot>
    @include('control.fragments.general.flash')
    <div>
        <div class="mx-auto p-3">
            <div>
                <x-form.validation-errors />
            </div>
            @can('update_service')
                <div class="flex-none md:flex gap-0 md:gap-4">
                    <div class="w-full bg-white rounded-lg overflow-hidden shadow-lg px-6 py-4">
                        <div class="flex justify-between mb-6">
                            <div>
                                <h3 class="font-bold text-base mb-1 text-gray-400">
                                    Service
                                </h3>
                                <div class="text-grey-dark">
                                    {{ ucfirst($service->title) }}
                                </div>
                            </div>
                            <div>
                                <h3 class="font-bold text-base mb-1 text-gray-400">
                                    Service Type
                                </h3>
                                <div class="text-grey-dark">
                                    {{ ucfirst($service->service_type?->title) }}
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-between mb-6">
                            <div>
                                <h3 class="font-bold text-base mb-1 text-gray-400">
                                    Price
                                </h3>
                                <div class="text-grey-dark">
                                    {{ core()->formatBasePrice($service->price ?? 0) }}
                                </div>
                            </div>
                            <div>
                                <h3 class="font-bold text-base mb-1 text-gray-400">
                                    Enabled
                                </h3>
                                <div class="text-grey-dark">
                                    @include('control.livewire.general.boolean', ['boolean' => $service->enabled])
                                </div>
                            </div>
                        </div>
                        <x-form.button class="m-2"
                            onclick="Livewire.emit('openModal', 'control.services.edit-service', {{ json_encode([$service->hashId()]) }})">
                            {{ __('Update Service') }}
                        </x-form.button>
                    </div>
                    <div class="w-auto md:w-1/1 lg:w-1/2 flex">
                    </div>
                </div>
                @if ($service->key != 'gift-card-exchange')
                    @if (count($service->children) > 0)
                        <div class="flex-none md:flex gap-0 md:gap-4 mt-2">
                            <div class="w-full bg-white rounded-lg overflow-hidden shadow-lg px-6 py-4">
                                <div class="flex justify-between mb-6">
                                    <div>
                                        <h2 class="font-bold text-base mb-1 text-gray-600">
                                            Service Children
                                        </h2>
                                    </div>
                                </div>
                                @if ($service->children)
                                    @foreach ($service->children as $childService)
                                        <div class="flex justify-between mb-6">
                                            <div>
                                                <h3 class="font-bold text-base mb-1 text-gray-400">
                                                    Title
                                                </h3>
                                                <div class="text-grey-dark">
                                                    {{ $childService->title }}
                                                </div>
                                            </div>
                                            <div>
                                                <h3 class="font-bold text-base mb-1 text-gray-400">
                                                    Price
                                                </h3>
                                                <div class="text-grey-dark">
                                                    {{ core()->formatBasePrice($childService->price ?? 0) }}
                                                </div>
                                            </div>
                                            <div>
                                                @can('update_service')
                                                <a href="{{ route('control.service.manage', ['service_id' => $childService->hashId()]) }}"
                                                    class="inline-flex items-center px-4 py-2 bg-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-dark active:bg-gray-900 focus:outline-none focus:border-primary-faded focus:ring focus:ring-gray-300 disabled:opacity-25 transition ml-2">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                @endcan
                                                @can('delete_service')
                                                <x-form.danger-button
                                                    onclick="Livewire.emit('openModal', 'control.services.delete-child-service',  {{ json_encode([$childService->hashId()]) }} )">
                                                    <i class="fa fa-trash-alt"></i>
                                                </x-form.danger-button>
                                                @endcan
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <div class="w-auto md:w-1/1 lg:w-1/2 flex">
                            </div>
                        </div>
                    @endif
                    <div class="flex-none md:flex gap-0 md:gap-4 mt-2">
                        <div class="w-full bg-white rounded-lg overflow-hidden shadow-lg px-6 py-4">
                            <div class="flex justify-between mb-6">
                                <div>
                                    <h2 class="font-bold text-base mb-1 text-gray-600">
                                        Service Variants / Categories
                                    </h2>
                                </div>
                            </div>
                            @if ($service->variants)
                                @foreach ($service->variants as $variant)
                                    <div class="flex justify-between mb-6">
                                        <div>
                                            <h3 class="font-bold text-base mb-1 text-gray-400">
                                                Title
                                            </h3>
                                            <div class="text-grey-dark">
                                                {{ ucfirst($variant->title) }}
                                            </div>
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-base mb-1 text-gray-400">
                                                Price
                                            </h3>
                                            <div class="text-grey-dark">
                                                {{ core()->formatBasePrice($variant->price ?? 0) }}
                                            </div>
                                        </div>
                                        <div>
                                            @can('update_service_variant')
                                            <a href="{{ route('control.service.edit-variant', [
                                                'service_id' => $service->hashId(),
                                                'service_variant_id' => $variant->hashId(),
                                            ]) }}"
                                                class="inline-flex items-center px-4 py-2 bg-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-dark active:bg-gray-900 focus:outline-none focus:border-primary-faded focus:ring focus:ring-gray-300 disabled:opacity-25 transition ml-2">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            @endcan
                                            @can('delete_service_variant')
                                            <x-form.danger-button
                                                onclick="Livewire.emit('openModal', 'control.services.properties.delete-variant',  {{ json_encode([$variant->hashId()]) }} )">
                                                <i class="fa fa-trash-alt"></i>
                                            </x-form.danger-button>
                                            @endcan
                                        </div>
                                    </div>
                                    <hr />
                                @endforeach
                            @endif
                            <x-form.button class="m-2"
                                onclick="Livewire.emit('openModal', 'control.services.properties.create-variant', {{ json_encode([$service->hashId(), 'service']) }})">
                                {{ __('Add New Service Variant to Service') }}
                            </x-form.button>
                        </div>
                        <div class="w-auto md:w-1/1 lg:w-1/2 flex">
                        </div>
                    </div>
                    @if (isset($service->variants) && count($service->variants) <= 0)
                        <div class="flex-none md:flex gap-0 md:gap-4 mt-2">
                            <div class="w-full bg-white rounded-lg overflow-hidden shadow-lg px-6 py-4">
                                <div class="flex justify-between mb-6">
                                    <div>
                                        <h2 class="font-bold text-base mb-1 text-gray-600">
                                            Service AddOns
                                        </h2>
                                    </div>
                                </div>
                                @if ($service->addons)
                                    @foreach ($service->addons as $addon)
                                        <div class="flex justify-between mb-6">
                                            <div>
                                                <h3 class="font-bold text-base mb-1 text-gray-400">
                                                    Label
                                                </h3>
                                                <div class="text-grey-dark">
                                                    {{ $addon->title }}
                                                </div>
                                            </div>
                                            <div>
                                                <h3 class="font-bold text-base mb-1 text-gray-400">
                                                    Description
                                                </h3>
                                                <div class="text-grey-dark">
                                                    {{ $addon->description }}
                                                </div>
                                            </div>
                                            <div>
                                                <h3 class="font-bold text-base mb-1 text-gray-400">
                                                    Price
                                                </h3>
                                                <div class="text-grey-dark">
                                                    {{ core()->formatBasePrice($addon->price ?? 0) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex justify-between mb-6">

                                            <div>
                                                <h3 class="font-bold text-base mb-1 text-gray-400">
                                                    Enabled
                                                </h3>
                                                <div class="text-grey-dark">
                                                    @include('control.livewire.general.boolean', ['boolean' =>
                                                    $addon->enabled])
                                                </div>
                                            </div>
                                            <div>
                                                @can('update_addon')
                                                <x-form.danger-button
                                                    class="bg-green-500 hover:bg-green-600 active:bg-gray-900 focus:outline-none focus:border-green-800 focus:ring focus:ring-gray-300"
                                                    onclick="Livewire.emit('openModal', 'control.services.properties.edit-addon',  {{ json_encode([$addon->hashId()]) }} )">
                                                    <i class="fa fa-edit"></i>
                                                </x-form.danger-button>
                                                @endcan
                                                @can('delete_addon')
                                                <x-form.danger-button
                                                    onclick="Livewire.emit('openModal', 'control.services.properties.delete-addon',  {{ json_encode([$addon->hashId()]) }} )">
                                                    <i class="fa fa-trash-alt"></i>
                                                </x-form.danger-button>
                                                @endcan
                                            </div>
                                        </div>
                                        <hr />
                                    @endforeach
                                @endif
                                <x-form.button class="m-2"
                                    onclick="Livewire.emit('openModal', 'control.services.properties.create-addon', {{ json_encode([$service->hashId(), 'service']) }})">
                                    {{ __('Add AddOn to Service') }}
                                </x-form.button>
                            </div>
                            <div class="w-auto md:w-1/1 lg:w-1/2 flex">
                            </div>
                        </div>
                    @endif
                    <div class="flex-none md:flex gap-0 md:gap-4 mt-2">
                        <div class="w-full bg-white rounded-lg overflow-hidden shadow-lg px-6 py-4">
                            <div class="flex justify-between mb-6">
                                <div>
                                    <h2 class="font-bold text-base mb-1 text-gray-600">
                                        Service Features
                                    </h2>
                                </div>
                            </div>
                            @if ($service->serviceFeatures)
                                @foreach ($service->serviceFeatures as $feature)
                                    <div class="flex justify-between mb-6">
                                        <div>
                                            <h3 class="font-bold text-base mb-1 text-gray-400">
                                                Title
                                            </h3>
                                            <div class="text-grey-dark">
                                                {{ $feature->feature->title }}
                                            </div>
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-base mb-1 text-gray-400">
                                                Values
                                            </h3>
                                            <div class="text-grey-dark">
                                                @foreach ($feature->featureValues as $value)
                                                    <p>{{ $value->title }}</p>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-base mb-1 text-gray-400">
                                                Metas
                                            </h3>
                                            <div class="text-grey-dark">
                                                @if ($feature->meta)
                                                    @foreach ($feature->meta as $meta)
                                                        <p>{{ $meta->key }} : {{ $meta->value }}</p>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                        <div>
                                            @can('update_feature')
                                            <a href="{{ route('control.service.edit-feature', [
                                                'service_id' => $service->hashId(),
                                                'feature_id' => $feature->hashId(),
                                                'type' => 'service',
                                            ]) }}"
                                                class="inline-flex items-center px-4 py-2 bg-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-dark active:bg-gray-900 focus:outline-none focus:border-primary-faded focus:ring focus:ring-gray-300 disabled:opacity-25 transition ml-2">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            @endcan
                                            @can('delete_feature')
                                            <x-form.danger-button
                                                onclick="Livewire.emit('openModal', 'control.services.properties.delete-feature',  {{ json_encode([$feature->hashId()]) }} )">
                                                <i class="fa fa-trash-alt"></i>
                                            </x-form.danger-button>
                                            @endcan
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                            <x-form.button class="m-2"
                                onclick="Livewire.emit('openModal', 'control.services.properties.create-feature', {{ json_encode([$service->hashId(), 'service']) }})">
                                {{ __('Add New Feature to Service') }}
                            </x-form.button>
                        </div>
                        <div class="w-auto md:w-1/1 lg:w-1/2 flex">
                        </div>
                    </div>
                    @if (isset($service->variants) && count($service->variants) <= 0)
                        <div class="flex-none md:flex gap-0 md:gap-4 mt-2">
                            <div class="w-full bg-white rounded-lg overflow-hidden shadow-lg px-6 py-4">
                                <div class="flex justify-between mb-6">
                                    <div>
                                        <h2 class="font-bold text-base mb-1 text-gray-600">
                                            Service Fields
                                        </h2>
                                    </div>
                                </div>
                                @if ($service->customFields)
                                    @foreach ($service->customFields as $field)
                                        <div class="flex justify-between mb-6">
                                            <div>
                                                <h3 class="font-bold text-base mb-1 text-gray-400">
                                                    Title
                                                </h3>
                                                <div class="text-grey-dark">
                                                    {{ $field->title }}
                                                </div>
                                            </div>
                                            <div>
                                                <h3 class="font-bold text-base mb-1 text-gray-400">
                                                    Type
                                                </h3>
                                                <div class="text-grey-dark">
                                                    {{ strtolower($field->type) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex justify-between mb-6">
                                            <div>
                                                <h3 class="font-bold text-base mb-1 text-gray-400">
                                                    Description
                                                </h3>
                                                <div class="text-grey-dark">
                                                    {{ $field->description }}
                                                </div>
                                            </div>
                                            <div>
                                                <h3 class="font-bold text-base mb-1 text-gray-400">
                                                    Answers
                                                </h3>
                                                <div class="text-grey-dark">
                                                    @if (isset($field->answers) && $field->answers !== 'null')
                                                        @if (is_array($field->answers))
                                                            {{ $field->answers ? implode(',', $field->answers) : '' }}
                                                        @else
                                                            {{ $field->answers ? implode(',', json_decode($field->answers, true)) : '' }}
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                            <div>
                                                @can('update_custom_field')
                                                <x-form.danger-button
                                                    class="bg-green-500 hover:bg-green-600 active:bg-gray-900 focus:outline-none focus:border-green-800 focus:ring focus:ring-gray-300"
                                                    onclick="Livewire.emit('openModal', 'control.services.properties.edit-field',  {{ json_encode([$field->hashId()]) }} )">
                                                    <i class="fa fa-edit"></i>
                                                </x-form.danger-button>
                                                @endcan
                                                @can('delete_custom_field')
                                                <x-form.danger-button
                                                    onclick="Livewire.emit('openModal', 'control.services.properties.delete-field',  {{ json_encode([$field->hashId()]) }} )">
                                                    <i class="fa fa-trash-alt"></i>
                                                </x-form.danger-button>
                                                @endcan
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                                <x-form.button class="m-2"
                                    onclick="Livewire.emit('openModal', 'control.services.properties.create-field', {{ json_encode([$service->hashId(), 'service']) }})">
                                    {{ __('Add New Field to Service') }}
                                </x-form.button>
                            </div>
                            <div class="w-auto md:w-1/1 lg:w-1/2 flex">
                            </div>
                        </div>
                    @endif
                    <div class="flex-none md:flex gap-0 md:gap-4 mt-2">
                        <div class="w-full bg-white rounded-lg overflow-hidden shadow-lg px-6 py-4">
                            <div class="flex justify-between mb-6">
                                <div>
                                    <h2 class="font-bold text-base mb-1 text-gray-600">
                                        Service Metas
                                    </h2>
                                </div>
                            </div>
                            @if ($service->meta)
                                @foreach ($service->meta as $meta)
                                    <div class="flex justify-between mb-6">
                                        <div>
                                            <h3 class="font-bold text-base mb-1 text-gray-400">
                                                Key
                                            </h3>
                                            <div class="text-grey-dark">
                                                {{ $meta->key }}
                                            </div>
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-base mb-1 text-gray-400">
                                                Value
                                            </h3>
                                            <div class="text-grey-dark">
                                                {{ $meta->value }}
                                            </div>
                                        </div>
                                        <div>
                                            @can('update_meta')
                                            <x-form.danger-button
                                                class="bg-green-500 hover:bg-green-600 active:bg-gray-900 focus:outline-none focus:border-green-800 focus:ring focus:ring-gray-300"
                                                onclick="Livewire.emit('openModal', 'control.services.properties.edit-meta',  {{ json_encode([$meta->id]) }} )">
                                                <i class="fa fa-edit"></i>
                                            </x-form.danger-button>
                                            @endcan
                                            @can('delete_meta')
                                            <x-form.danger-button
                                                onclick="Livewire.emit('openModal', 'control.services.properties.delete-meta',  {{ json_encode([$meta->id]) }} )">
                                                <i class="fa fa-trash-alt"></i>
                                            </x-form.danger-button>
                                            @endcan
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                            <x-form.button class="m-2"
                                onclick="Livewire.emit('openModal', 'control.services.properties.create-meta', {{ json_encode([$service->hashId(), 'service']) }})">
                                {{ __('Add Meta to Service') }}
                            </x-form.button>
                        </div>
                        <div class="w-auto md:w-1/1 lg:w-1/2 flex">
                        </div>
                    </div>
                @else
                    <div class="flex-none md:flex gap-0 md:gap-4 mt-2">
                        <div class="w-full bg-white rounded-lg overflow-hidden shadow-lg px-6 py-4">

                            <div class="flex justify-between mb-6">
                                <div>
                                    <h2 class="font-bold text-base mb-1 text-gray-600">
                                        Gift Card
                                    </h2>
                                </div>
                            </div>
                            <x-form.button class="m-2"
                                onclick="Livewire.emit('openModal', 'control.services.gift-cards.create-gift-card')">
                                {{ __('Create Gift Card') }}
                            </x-form.button>
                            @if ($giftCards)
                                @foreach ($giftCards as $giftCard)
                                    <div class="flex justify-between  m-3">
                                        <div>
                                            <h3 class="font-bold text-base mb-1 text-gray-400">
                                                Title
                                            </h3>
                                            <div class="text-grey-dark">
                                                {{ $giftCard->title }}
                                            </div>
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-base mb-1 text-gray-400">
                                                Enabled
                                            </h3>
                                            <div class="text-grey-dark">
                                                @include('control.livewire.general.boolean', ['boolean' =>
                                                $giftCard->enabled])
                                            </div>
                                        </div>
                                        <div>
                                            <a href="{{ route('control.service.edit-gift-card', [
                                                'service_id' => $service->hashId(),
                                                'giftcard_id' => $giftCard->hashId(),
                                            ]) }}"
                                                class="inline-flex items-center px-4 py-2 bg-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-dark active:bg-gray-900 focus:outline-none focus:border-primary-faded focus:ring focus:ring-gray-300 disabled:opacity-25 transition ml-2">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <x-form.danger-button
                                                onclick="Livewire.emit('openModal', 'control.services.gift-cards.delete-gift-card',  {{ json_encode([$giftCard->hashId()]) }} )">
                                                <i class="fa fa-trash-alt"></i>
                                            </x-form.danger-button>
                                        </div>
                                    </div>
                                    <hr />
                                @endforeach
                            @endif
                        </div>
                        <div class="w-auto md:w-1/1 lg:w-1/2 flex">
                        </div>
                    </div>
                @endif
            @else
                <p>Sorry, you don't have permission to edit this</p>
            @endcan
            @include('control.fragments.general.flash')
            @include('control.fragments.general.flash')
        </div>
    </div>
</x-layouts.authenticated>
