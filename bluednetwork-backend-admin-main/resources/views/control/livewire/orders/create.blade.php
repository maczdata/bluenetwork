<div class="p-3">
    <x-form.form-section
        method="post"
        action="create-order"
        title="Add Order"
        description="Create a new order"
    >
    <x-slot name="formencoding">
        multipart/form-data
    </x-slot>
        <x-slot name="action">
            {{ route('control.order.create') }}
        </x-slot>
        <x-slot name="content">
            <x-form.validation-errors />
        </x-slot>
        <x-slot name="form">
            <div class="col-span-6 sm:col-span-6">
                <x-form.label for="user" value="{{ __('User') }}" />
                <x-form.select id="user" name="user" wire:model="user" required >
                    <x-slot name="slot">
                        <option value=""> Select a user </option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}"> {{ ucfirst($user->username) }}</option>
                        @endforeach
                    </x-slot>
                </x-form.select>
            </div>
            <div class="col-span-6 sm:col-span-6">
                <x-form.label for="service" value="{{ __('Service') }}" />
                <x-form.select id="service" name="service" wire:model="service" required >
                    <x-slot name="slot">
                        <option value=""> Select a service </option>
                        @foreach($services as $service)
                            @if(is_null($service->parent_id))
                                <option value="{{ $service->id }}"> {{ ucfirst($service->title) }}</option>
                            @endif
                        @endforeach
                    </x-slot>
                </x-form.select>
            </div>
            @if($selectedService?->key === "electricity")
            <div class="col-span-6 sm:col-span-6">
                <x-form.checkbox-group :stacked="false" :gridCols="2">
                    <x-form.label for="post_paid" value="{{ __(' Post Paid') }}">
                        <input type="radio" class="rounded h-4 w-4 text-primary border-blue-gray-300 focus:ring-blue-500" id="postpaid" type="radio" name="electricity_meter_type" value="postpaid" checked >
                    </x-form.label>
                    <x-form.label for="pre_paid" value="{{ __('Pre Paid ') }}">
                        <input type="radio" class="rounded h-4 w-4 text-primary border-blue-gray-300 focus:ring-blue-500" id="prepaid" type="radio" name="electricity_meter_type" value="prepaid">
                    </x-form.label>
                </x-form.checkbox-group>
            </div> 
            @endif
         
            @if ($selectedService?->key === "gift-card-exchange")
            <div class="col-span-6 sm:col-span-6">
                <x-form.label for="gift_card_id" value="{{ __('Gift Card') }}" />
                <x-form.select id="gift_card_id" name="gift_card_id" wire:model="giftCard"  >
                    <x-slot name="slot">
                        <option value=""> Select a Gift Card </option>
                        @foreach($giftCards as $giftCard) 
                            <option value="{{ $giftCard->id }}"> {{ $giftCard->title }}</option>
                        @endforeach
                    </x-slot>
                </x-form.select>
            </div>  
            @elseif($selectedService && ! in_array($selectedService?->key, ["data-subscription", "cable_tv", "airtime-topup", "electricity","airtime-for-cash"]))
                @if (isset($serviceVariants) && count($serviceVariants) > 0)
                <div class="col-span-6 sm:col-span-6">
                    <x-form.label for="variant_key" value="{{ __('Select Category') }}" />
                    <x-form.select id="variant_key" name="variant_key">
                        <x-slot name="slot">
                            <option value="">Select Category </option>
                            @foreach($serviceVariants as $serviceVariant) 
                                <option value="{{ $serviceVariant->key }}"> {{ $serviceVariant->title }} - {{ core()->formatBasePrice($serviceVariant->price)  }}</option>
                            @endforeach
                        </x-slot>
                    </x-form.select>
                </div> 
                @endif  
            @else
            <div class="col-span-6 sm:col-span-6">
                <x-form.label for="service_provider" value="{{ __('Service Provider') }}" />
                <x-form.select id="service_provider" name="service_provider" wire:model="serviceProvider"  >
                    <x-slot name="slot">
                        <option value=""> Select a service provider </option>
                        @foreach($serviceProviders as $serviceProvider) 
                            <option value="{{ $serviceProvider->id }}"> {{ $serviceProvider->title }}</option>
                        @endforeach
                    </x-slot>
                </x-form.select>
            </div>  
            @endif
            @if($selectedService?->key === "gift-card-exchange" && $giftCard)
            <div class="col-span-6 sm:col-span-6">
                <x-form.label for="gift_card_currency_id" value="{{ __('Select a Country/Currency') }}" />
                <x-form.select id="gift_card_currency_id" name="gift_card_currency_id" wire:model="giftCardCurrency"  >
                    <x-slot name="slot">
                        <option value=""> Select a Country/Currency </option>
                        @foreach($giftCardCurrencies as $giftCardCurrency) 
                            <option value="{{ $giftCardCurrency->id }}"> {{ $giftCardCurrency->currency->name }}</option>
                        @endforeach
                    </x-slot>
                </x-form.select>
            </div>  
            @endif
            @if($selectedService?->key === "gift-card-exchange" && $giftCard)
            <div class="col-span-6 sm:col-span-6">
                <x-form.label for="gift_card_category_id" value="{{ __('Select a Card Type') }}" />
                <x-form.select id="gift_card_category_id" name="gift_card_category_id" wire:model="giftCardCategoryId"  >
                    <x-slot name="slot">
                        <option value=""> Select a Card Type </option>
                        @foreach($giftCardCategories as $giftCardCategory) 
                            @if(is_null(optional($giftCardCategory->categoryOfGiftCard)->parent_id))
                            <option value="{{ $giftCardCategory->id }}"> {{ $giftCardCategory->categoryOfGiftCard->title }}</option>
                            @endif
                        @endforeach
                    </x-slot>
                </x-form.select>
            </div>  
            @endif
            @if($selectedService?->key === "gift-card-exchange" && $giftCardCategoryId && count($giftCardSubCategories) > 0)
            <div class="col-span-6 sm:col-span-6">
                <x-form.label for="gift_card_sub_category_id" value="{{ __('Select a Receipt Type') }}" />
                <x-form.select id="gift_card_sub_category_id" name="gift_card_sub_category_id" wire:model="giftCardSubCategory"  >
                    <x-slot name="slot">
                        <option value=""> Select a Receipt Type </option>
                        @foreach($giftCardSubCategories as $giftCardSubCategory) 
                            <option value="{{ $giftCardSubCategory->id }}"> {{ $giftCardSubCategory->title }}</option>
                        @endforeach
                    </x-slot>
                </x-form.select>
            </div>  
            @endif
            @if($giftCardSubCategory)
                @if($giftCardCurrencyRates)
                    <h4> {{ __('Card Value') }} </h4>
                    @foreach($giftCardCurrencyRates as $key => $rate)
                    <div class="col-span-12 sm:col-span-12">
                        <div class="grid grid-cols-3 gap-2">
                            <div>
                                <p>{{ $rate['price'] }}
                                <x-form.input id="gift_card_rates" type="hidden" name="gift_card_rates[{{$key}}][rate_id]"  value="{{$rate['rate_id']}}"  />
                            </div>
                            <div> 
                                <x-form.input id="gift_card_rates" type="number" placeholder="Quantity" name="gift_card_rates[{{$key}}][quantity]" value="{{$rate['quantity']}}" wire:model="giftCardCurrencyRates.{{ $key }}.quantity" class="ml-2"/>
                                <x-form.input-error for="gift_card_rates" class="mt-2" />
                            </div>
                            <div> 
                                <x-form.input id="gift_card_rates" type="text" placeholder="E-code" name="gift_card_rates[{{$key}}][codes]" value="{{$rate['codes']}}" wire:model="giftCardCurrencyRates.{{ $key }}.codes" class="ml-2"  />
                                <x-form.input-error for="gift_card_rates" class="mt-2" />
                                <span class="ml-3">
                                    {{ (int) $rate['price'] * (int) $rate['quantity'] }}
                                </span>
                            </div>
                           
                        </div>
                        
                    </div>
                    @endforeach
                @endif
                <div class="col-span-6 sm:col-span-6">
                    <x-form.label for="gift_card_proof_files" value="{{ __('Gift Card Proof Files') }}" />
                    <x-form.input id="gift_card_proof_files" type="file" name="gift_card_proof_files[]"  required multiple />
                    <x-form.input-error for="gift_card_proof_files" class="mt-2" />
                </div>
            @endif

            @if($selectedService?->key === "cable_tv")
            <div class="col-span-6 sm:col-span-6">
                <x-form.label for="cable_tv_package" value="{{ __('Service Package (Cable TV)') }}" />
                <x-form.select id="cable_tv_package" name="cable_tv_package" wire:model="cablePackage" required >
                    <x-slot name="slot">
                        <option value="">Select Package (Cable TV) </option>
                        @foreach($cableTvPackages as $cableTvPackage) 
                            <option value="{{ $cableTvPackage->key }}"> {{ $cableTvPackage->title }} - {{ core()->formatBasePrice($cableTvPackage->price)  }}</option>
                        @endforeach
                    </x-slot>
                </x-form.select>
            </div>   
            @endif 
            
            @if($selectedService?->key === "data-subscription")
            <div class="col-span-6 sm:col-span-6">
                <x-form.label for="variant" value="{{ __('Data Value') }}" />
                <x-form.select id="variant" name="variant" wire:model="dataValue" required >
                    <x-slot name="slot">
                        <option value=""> Select Data Value </option>
                        @foreach($dataValues as $dataValue) 
                            <option value="{{ $dataValue->id }}"> {{ $dataValue->title }} - {{ core()->formatBasePrice($dataValue->price)  }}</option>
                        @endforeach
                    </x-slot>
                </x-form.select>
            </div>    
            @endif
            @if(! in_array($selectedService?->key, ["data-subscription", "cable_tv", "gift-card-exchange"]))
            <div class="col-span-6 sm:col-span-6">
                <x-form.label for="amount" value="{{ __('Amount') }}" />
                <x-form.input id="amount" type="number" name="amount" :value="old('amount')" required autofocus />
                <x-form.input-error for="amount" class="mt-2" />
            </div> 
            @endif
           
            @if($selectedService?->key === "electricity")
            <div class="col-span-6 sm:col-span-6">
                <x-form.label for="name" value="{{ __('Meter Number') }}" />
                <x-form.input id="electricity_meter_number" type="number" name="electricity_meter_number" :value="old('name')" required autofocus  />
                <x-form.input-error for="electricity_meter_number" class="mt-2" />
            </div>
            @elseif($selectedService?->key === "cable_tv")
            <div class="col-span-6 sm:col-span-6">
                <x-form.label for="cable_tv_smart_card_number" value="{{ __('Smart Card Number') }}" />
                <x-form.input id="cable_tv_smart_card_number" type="number" name="cable_tv_smart_card_number" :value="old('name')" required autofocus  />
                <x-form.input-error for="cable_tv_smart_card_number" class="mt-2" />
            </div>
            @else
            <div class="col-span-6 sm:col-span-6">
                <x-form.label for="name" value="{{ __('Phone Number') }}" />
                <x-form.input id="phone_number" type="tel" name="phone_number" :value="old('name')" autofocus  />
                <x-form.input-error for="phone_number" class="mt-2" />
            </div>
            @endif
            <div class="hidden col-span-6 sm:col-span-6">
                <x-form.input id="service_key" type="text" name="service_key" wire:model="orderKey" required autofocus  />
                <x-form.input id="mode_of_payment" type="text" name="mode_of_payment" value="wallet"  />
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
