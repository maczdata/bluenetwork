<x-layouts.authenticated>
    @section('seo')
        <title>Order details &raquo; {{ config('app.name') }}</title>
        <meta name="description" content="{{ config('app.name') }} Order details">
    @stop
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <span class="text-blue-500"> #{{ ucfirst($order->order_number) }} </span> {{ __('Order details') }}
        </h2>
    </x-slot>

    <div class="py-4 sm:py-10 mx-2 sm:mx-4 md:mx-5 lg:mx-10">
        @include('control.fragments.general.flash')
        <div class="flex-none md:flex gap-0 md:gap-4">
            <div class="w-full bg-white rounded-lg overflow-hidden shadow-lg px-6 py-4">
                <div class="grid grid-cols-6 mb-6">
                    <div>
                        <h3 class="font-bold text-base mb-1 text-gray-400">
                            User
                        </h3>
                        <div class="text-grey-dark">
                            {{ ucfirst(optional($order->user)->full_name) }}
                        </div>
                    </div>
                    <div>
                        <h3 class="font-bold text-base mb-1 text-gray-400">
                            Service Type
                        </h3>
                        <div class="text-grey-dark">
                            @if($order->orderable_type === "gift_card")
                             {{ ucfirst($order?->items?->first()->orderitemable?->giftCardCategory->categoryOfGiftCard->title) }}
                            @elseif ($order->orderable_type === "service")
                                {{ ucfirst($order?->orderable?->service_type?->title) }}
                            @elseif ($order->orderable_type === "service_variant")
                                {{ ucfirst($order?->orderable?->service?->service_type?->title) }}
                            @endif
                        </div>
                    </div>
                    <div>
                        <h3 class="font-bold text-base mb-1 text-gray-400">
                            Service 
                        </h3>
                        <div class="text-grey-dark">
                           @if($order->orderable_type === "gift_card")
                              {{ ucfirst($order?->items?->first()->orderitemable?->giftCardCategory?->categoryOfGiftCard->title) }}
                            @elseif ($order->orderable_type === "service")
                                {{ ucfirst($order?->orderable?->parent?->title) }}
                            @elseif ($order->orderable_type === "service_variant")
                                {{ ucfirst($order?->orderable?->service?->title) }}
                            @endif
                        </div>
                    </div>
                    <div>
                        <h3 class="font-bold text-base mb-1 text-gray-400">
                            Service Provider
                        </h3>
                        <div class="text-grey-dark">
                            {{ ucfirst($order?->orderable?->title) }}
                        </div>
                    </div>
                    <div>
                        <h3 class="font-bold text-base mb-1 text-gray-400">
                            Amount
                        </h3>
                        <div class="text-grey-dark">
                            {{ core()->formatBasePrice($order->sub_total  ?? 0) }}
                        </div>
                    </div>
                    <div>
                        <h3 class="font-bold text-base mb-1 text-gray-400">
                            Status
                        </h3>
                        <div class="text-grey-dark">
                            @include('control.livewire.orders.status', ['status' => $order->status])
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-auto md:w-1/1 lg:w-1/2 flex"></div>
        </div>
        @if($order->media->count() > 0)
        <div class="flex-none md:flex gap-0 md:gap-4 m-3 p-3">
            <div class="w-full bg-white rounded-lg overflow-hidden shadow-lg px-6 py-4">
                @foreach($order->media as $media) 
                <div class="flex justify-between mb-6">
                    <div>
                        <h3 class="font-bold text-base mb-1 text-gray-400">
                            Order Media
                        </h3>
                        <div class="flex flex-row items-center">
                           {{ $media->img()->attributes(['class' => 'w-30a h-40']) }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="w-auto md:w-1/1 lg:w-1/2 flex"></div>
        </div>
        @endif
        @if($order->customFieldResponses->count() > 0)
        <div class="flex-none md:flex gap-0 md:gap-4 m-3 p-3">
           
            <div class="w-full bg-white rounded-lg overflow-hidden shadow-lg px-6 py-4">
                <h3>Custom Field Response</h3>
                @foreach($order->customFieldResponses as $customFieldResponse) 
                <div class="flex justify-between mb-6 mt-2">
                    <div>
                        <h3 class="font-bold text-base mb-1 text-gray-400">
                            Custom Field
                        </h3>
                        <div class="flex flex-row items-center">
                           {{ $customFieldResponse->field->title }}
                        </div>
                    </div>
                    <div>
                        <h3 class="font-bold text-base mb-1 text-gray-400">
                            Custom Field Response
                        </h3>
                        <div class="flex items-center">
                            @if($customFieldResponse->type == "object")
                            {{ "" }}
                            @elseif ($customFieldResponse->type == "file")
                                @if($customFieldResponse->media->count() > 0)
                                    @foreach($customFieldResponse->media as $media)
                                    <p>{{ $media->name }}</p>
                                    <br />
                                    <a href="{{ $media->original_url }}" target="_blank" class="rounded-md bg-green-500 text-white ml-3 p-2 font-bold items-center hover:bg-green-700"
                                    >Download File</a>
                                    @endforeach
                                @endif
                            @else
                            {{ (string) $customFieldResponse->value }}
                            @endif
                        </div>
                    </div>
                </div>
                <hr/>
                @endforeach
            </div>
            <div class="w-auto md:w-1/1 lg:w-1/2 flex"></div>
        </div>
        @endif
        @if($order->meta->count() > 0)
        <div class="flex-none md:flex gap-0 md:gap-4 m-3 p-3">
           
            <div class="w-full bg-white rounded-lg overflow-hidden shadow-lg px-6 py-4">
                <h3>Custom Field Response</h3>

               
                @foreach($order->meta as $meta) 
                <div class="flex justify-between mb-6 mt-2">
                    <div>
                        <h3 class="font-bold text-base mb-1 text-gray-400">
                            Meta Key
                        </h3>
                        <div class="flex flex-row items-center">
                           {{ $meta->key }}
                        </div>
                    </div>
                    <div>
                        <h3 class="font-bold text-base mb-1 text-gray-400">
                            Meta Value
                        </h3>
                        <div class="flex flex-row items-center">
                           {{ $meta->value }}
                        </div>
                    </div>
                </div>
                <hr/>
                @endforeach
            </div>
            <div class="w-auto md:w-1/1 lg:w-1/2 flex"></div>
        </div>
        @endif
        @if($order->orderable_type === "gift_card")
        <div class="flex-none md:flex gap-0 md:gap-4 m-3 p-3">
            <div class="w-full bg-white rounded-lg overflow-hidden shadow-lg px-6 py-4">
                @foreach($order->items as $item) 
                <div class="flex justify-between mb-6">
                    <div>
                        <h3 class="font-bold text-base mb-1 text-gray-400">
                            Order Category Name
                        </h3>
                        <div class="text-grey-dark">
                            {{ $item->orderitemable->giftCardCategory->categoryOfGiftCard->title }}
                        </div>
                    </div>
                    <div>
                        <h3 class="font-bold text-base mb-1 text-gray-400">
                            Order Currency Name
                        </h3>
                        <div class="text-grey-dark">
                            {{ $item->orderitemable->giftCardCurrency->currency->name }}
                        </div>
                    </div>
                    <div>
                        <h3 class="font-bold text-base mb-1 text-gray-400">
                            Order Quantity
                        </h3>
                        <div class="text-grey-dark">
                            {{ $item->quantity }}
                        </div>
                    </div>
                    <div>
                        <h3 class="font-bold text-base mb-1 text-gray-400">
                            Item Price
                        </h3>
                        <div class="text-grey-dark">
                            {{ core()->formatBasePrice($item->price  ?? 0) }}
                        </div>
                    </div>
                    <div>
                        <h3 class="font-bold text-base mb-1 text-gray-400">
                            Order Total Price
                        </h3>
                        <div class="text-grey-dark">
                            {{ core()->formatBasePrice($item->total  ?? 0) }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="w-auto md:w-1/1 lg:w-1/2 flex"></div>
        </div>
        @endif
        @livewire('control.orders.edit-order', [ $order->hashId() ])
    </div>

</x-layouts.authenticated>
