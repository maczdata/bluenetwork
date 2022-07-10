
        <div class="flex-none md:flex gap-0 md:gap-4 m-3 p-3">
            <x-form.form-section
                wire:submit.prevent="update"
                title="Update Order"
                description="Update order"
            > 
            <x-slot name="content">
                <x-form.validation-errors />
            </x-slot>
            <x-slot name="form">
                <div class="col-span-12 sm:col-span-12">
                    <x-form.label for="orderStatus" value="{{ __('Status') }}" />
                    <x-form.select id="orderStatus" name="orderStatus" wire:model="orderStatus">
                        <x-slot name="slot">
                            <option value="pending" {{ $order->status === 'pending' ? 'selected': '' }}>Pending</option>
                            <option value="pending_payment" {{ $order->status === 'pending_payment' ? 'selected': '' }}>Pending Payment</option>
                            <option value="processing" {{ $order->status === 'processing' ? 'selected': '' }}>Processing</option>
                            <option value="completed" {{ $order->status === 'completed' ? 'selected': '' }}>Completed</option>
                            <option value="canceled" {{ $order->status === 'canceled' ? 'selected': '' }}>Canceled</option>
                            <option value="closed" {{ $order->status === 'closed' ? 'selected': '' }}>Closed</option>
                            <option value="fraud" {{ $order->status === 'fraud' ? 'selected': '' }}>Fraud</option>
                            <option value="for_refund" {{ $order->status === 'for_refund' ? 'selected': '' }}>For Refund</option>
                            <option value="refunded" {{ $order->status === 'refunded' ? 'selected': '' }}>Refunded</option>
                        </x-slot>
                    </x-form.select>
                </div>
            </x-slot>
        
            <x-slot name="actions">
                <x-form.danger-button 
                    class="bg-primary"
                    onclick="Livewire.emit('openModal', 'control.orders.update-status',  {{ json_encode([$order->hashId(), $orderStatus]) }} )">
                    {{ __('Update Status') }}
                </x-form.danger-button>
            </x-slot>
        </x-form.form-section>
    </div>