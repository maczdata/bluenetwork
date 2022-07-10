<x-layouts.authenticated>
    @section('seo')
        <title>Transaction &raquo; {{ config('app.name') }}</title>
        <meta name="description" content="{{ config('app.name') }} Transaction">
    @stop
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Transaction') }}
        </h2>
    </x-slot>
    @include('control.fragments.general.flash')
    <div>
        <div class="mx-auto p-3">
            <div>
                <x-form.validation-errors />
            </div>
            @can('view_transaction')
                <div class="flex-none md:flex gap-0 md:gap-4">
                    <div class="w-full bg-white rounded-lg overflow-hidden shadow-lg px-6 py-4">
                        <div class="flex justify-between mb-6">
                            <div>
                                <h3 class="font-bold text-base mb-1 text-gray-400">
                                    User
                                </h3>
                                <div class="text-grey-dark">
                                    {{ optional($transaction->ownerable)->first_name }}
                                </div>
                            </div>
                            <div>
                                <h3 class="font-bold text-base mb-1 text-gray-400">
                                    Transaction Type
                                </h3>
                                <div class="text-grey-dark">
                                    {{ ucfirst($transaction->transactionable_type) }}
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-between mb-6">
                            <div>
                                <h3 class="font-bold text-base mb-1 text-gray-400">
                                    Price
                                </h3>
                                <div class="text-grey-dark">
                                    {{ core()->formatBasePrice($transaction->price ?? 0) }}
                                </div>
                            </div>
                            <div>
                                <h3 class="font-bold text-base mb-1 text-gray-400">
                                    Status
                                </h3>
                                <div class="text-grey-dark">
                                    @include('control.livewire.general.boolean', ['boolean' => $transaction->status])
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-auto md:w-1/1 lg:w-1/2 flex">
                    </div>
                </div>
            @endcan
            @include('control.fragments.general.flash')
        </div>
    </div>
</x-layouts.authenticated>
