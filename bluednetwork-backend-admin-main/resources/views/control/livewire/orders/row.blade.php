<x-livewire-tables::table.cell>
    {{ "#" .$row->order_number }}
</x-livewire-tables::table.cell>
<x-livewire-tables::table.cell>
    {{ $row->orderable->title }}
</x-livewire-tables::table.cell>
<x-livewire-tables::table.cell>
    {{ optional($row->user)->full_name }}
</x-livewire-tables::table.cell>
<x-livewire-tables::table.cell>
    @include('control.livewire.orders.status', ['status' => $row->status])
</x-livewire-tables::table.cell>
<x-livewire-tables::table.cell>
    {{ core()->formatBasePrice($row->sub_total  ?? 0) }}
</x-livewire-tables::table.cell>
<x-livewire-tables::table.cell>
    {{ core()->formatBasePrice($row->grand_total  ?? 0) }}
</x-livewire-tables::table.cell>
<x-livewire-tables::table.cell>
    {{ $row->created_at  }}
</x-livewire-tables::table.cell>
<x-livewire-tables::table.cell>
    @can('update_order')
    <a href="{{ route('control.order.single', ['order_id' => $row->hashId()]) }}"
        class="inline-flex items-center px-4 py-2 bg-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-dark active:bg-gray-900 focus:outline-none focus:border-primary-faded focus:ring focus:ring-gray-300 disabled:opacity-25 transition ml-2">
        <i class="fa fa-edit"></i>
    </a>
    @endcan
    @can('delete_order')
    <x-form.danger-button wire:click="$emit('openModal', 'control.orders.delete-order',  {{ json_encode([$row->hashId()]) }} )"
    >
        <i class="fa fa-trash-alt"></i>
    </x-form.danger-button>
    @endcan
</x-livewire-tables::table.cell>
