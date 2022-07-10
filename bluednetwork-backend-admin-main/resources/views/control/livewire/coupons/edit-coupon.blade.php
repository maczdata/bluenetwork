<div class="p-3">
    <x-form.form-section
        method="post"
        action="edit-coupon"
        title="Edit Coupon"
        description="Update coupon"
    >
    <x-slot name="otherMethod">
        @method('PUT')
        </x-slot>
        <x-slot name="action">
            {{ route('control.coupons.update', ['coupon_id' => $coupon->hashId()]) }}
        </x-slot>
        <x-slot name="content">
            <x-form.validation-errors />
        </x-slot>
        <x-slot name="form">
            <div class="col-span-6 sm:col-span-6">
                <x-form.label for="couponable_type_category" value="{{ __('Couponable Item Category') }}">
                    <x-form.select id="couponable_type_category" name="couponable_type_category" wire:model="couponableTypeCategory" required>
                        <x-slot name="slot">
                                <option value="gift_cards" selected> Gift Card</option>
                                <option value="services"> Service</option>
                                <option value="service_variants"> Service Variant</option>
                        </x-slot>
                    </x-form.select>
                </x-form.label>
            </div>
            <div class="col-span-6 sm:col-span-6">
                <x-form.label for="couponable_type" value="{{ __('Couponable Item') }}" />
                <x-form.select id="couponable_type" name="couponable_type" required>
                    <x-slot name="slot">
                        @foreach($couponableTypeOptions as $couponableTypeOption)
                            <option value="{{ $couponableTypeOption->hashId() }}" {{ $coupon->couponable_id === $couponableTypeOption->id ? ' selected' : ''}}> {{ ucfirst( $couponableTypeOption->key ?? $couponableTypeOption->title )}}</option>
                        @endforeach
                    </x-slot>
                </x-form.select>
            </div>
            <div class="col-span-6 sm:col-span-6">
                <x-form.label for="code" value="{{ __('Code') }}" />
                <x-form.input id="code" type="text" name="code" value="{{ $coupon->code }}" required />
                <x-form.input-error for="code" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-6">
                <x-form.label for="description" value="{{ __('Description') }}" />
                <x-form.textarea id="description" name="description">
                    {{ $coupon->description }}
                </x-form.textarea>
                <x-form.input-error for="description" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-6">
                <x-form.label for="type" value="{{ __('Type') }}" />
                <x-form.select id="type" name="type" wire:model="type" required>
                    <x-slot name="slot">
                        <option value="fixed" {{ $coupon->type === 'fixed' ? 'selected': '' }}>Fixed</option>
                        <option value="percentage"  {{ $coupon->type === 'percentage' ? 'selected': '' }}>Percentage</option>
                    </x-slot>
                </x-form.select>
            </div>
            @if($type == "percentage")
                <div class="col-span-4 sm:col-span-4">
                    <x-form.label for="percentage_off" value="{{ __('Percentage off') }}" />
                    <x-form.input id="percentage_off" type="number" name="percentage_off" value="{{ $coupon->percentage_off }}" required />
                    <x-form.input-error for="percentage_off" class="mt-2" />
                </div>
            @else
                <div class="col-span-4 sm:col-span-4">
                    <x-form.label for="value" value="{{ __('Value') }}" />
                    <x-form.input id="value" type="number" name="value" value="{{ $coupon->value }}" required />
                    <x-form.input-error for="value" class="mt-2" />
                </div>
            @endif
            <div class="col-span-6 sm:col-span-6">
                <x-form.label for="max_uses" value="{{ __('Maximum Uses') }}" />
                <x-form.input id="max_uses" type="number" name="max_uses" value="{{ $coupon->max_uses }}" />
                <x-form.input-error for="max_uses" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-6">
                <x-form.label for="starts_at" value="{{ __('Starts At') }}" />
                <x-form.input id="starts_at" type="datetime-local" name="starts_at" value="{{ date('Y-m-d\TH:i:s', strtotime(date('Y-m-d H:i', strtotime($coupon->starts_at)))) }}" required/>
                <x-form.input-error for="starts_at" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-6">
                <x-form.label for="expires_at" value="{{ __('Expires At') }}" />
                <x-form.input id="expires_at" type="datetime-local" name="expires_at"  value="{{ date('Y-m-d\TH:i:s', strtotime(date('Y-m-d H:i', strtotime($coupon->expires_at)))) }}" required/>
                <x-form.input-error for="expires_at" class="mt-2" />
            </div> 
            <div class="col-span-12 sm:col-span-12">
                <x-form.label for="enabled" value="{{ __('Enabled') }}" />
                <x-form.select id="enabled" name="enabled">
                    <x-slot name="slot">
                        <option value="1" {{ $coupon->enabled === '1' ? 'selected': '' }}>Yes</option>
                        <option value="0" {{ $coupon->enabled === '0' ? 'selected': '' }}>No</option>
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
