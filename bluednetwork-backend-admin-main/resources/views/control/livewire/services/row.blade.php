<x-livewire-tables::table.cell>
    {{ ucfirst($row?->title) }}
</x-livewire-tables::table.cell>
<x-livewire-tables::table.cell>
    {{ ucfirst($row->service_type?->title) }}
</x-livewire-tables::table.cell>
<x-livewire-tables::table.cell>
    {{ core()->formatBasePrice($row->price  ?? 0) }}
</x-livewire-tables::table.cell>
<x-livewire-tables::table.cell>
    @include('control.livewire.general.boolean', ['boolean' => $row->enabled])
</x-livewire-tables::table.cell>
<x-livewire-tables::table.cell>
    {{ $row->created_at  }}
</x-livewire-tables::table.cell>
<x-livewire-tables::table.cell>
    <a href="{{ route('control.service.manage', ['service_id' => $row->hashId()]) }}"
        class="inline-flex items-center px-4 py-2 bg-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-dark active:bg-gray-900 focus:outline-none focus:border-primary-faded focus:ring focus:ring-gray-300 disabled:opacity-25 transition ml-2">
        <i class="fa fa-edit"></i>
    </a>
</x-livewire-tables::table.cell>
<x-livewire-tables::table.cell>
    @can('delete_service')
    <x-form.danger-button onclick="Livewire.emit('openModal', 'control.services.delete-service',  {{ json_encode([$row->hashId()]) }} )"
        >
        <i class="fa fa-trash-alt"></i>
    </x-form.danger-button>
    @endcan
</x-livewire-tables::table.cell>
