<x-livewire-tables::table.cell>
    {{ $row->name }}
</x-livewire-tables::table.cell>
<x-livewire-tables::table.cell>
    @include('control.livewire.general.boolean', ['boolean' => $row->enabled])
</x-livewire-tables::table.cell>
<x-livewire-tables::table.cell>
    {{ core()->formatBasePrice($row->transaction_limit ?? 0) }}
</x-livewire-tables::table.cell>
<x-livewire-tables::table.cell>
    {{ core()->formatBasePrice($row->withdrawal_limit ?? 0) }}
</x-livewire-tables::table.cell>
<x-livewire-tables::table.cell>
    {{ core()->formatBasePrice($row->daily_limit ?? 0) }}
</x-livewire-tables::table.cell>
<x-livewire-tables::table.cell>
    {{ $row->created_at }}
</x-livewire-tables::table.cell>
<x-livewire-tables::table.cell>
    <a href="account-levels/edit/{{$row->id}}"
        class="inline-flex items-center px-4 py-2 bg-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-dark active:bg-gray-900 focus:outline-none focus:border-primary-faded focus:ring focus:ring-gray-300 disabled:opacity-25 transition ml-2">
        <i class="fa fa-edit"></i>
    </a>
</x-livewire-tables::table.cell>
