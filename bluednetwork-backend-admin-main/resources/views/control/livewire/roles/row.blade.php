<x-livewire-tables::table.cell>
    {{ $row->name }}
</x-livewire-tables::table.cell>
<x-livewire-tables::table.cell>
    {{ $row->guard_name }}
</x-livewire-tables::table.cell>
<x-livewire-tables::table.cell>
    @if($row?->permissions?->count() > 0)
    <ul class="mt-3 list-disc list-inside text-sm">
        @foreach($row?->permissions?->pluck('name') as $permission)
            <li>{{ $permission }}</li>
        @endforeach
    </ul>
    @elseif($row->id == '1' || $row->id == '3')
    <span> All Permissions</span>
    @else
    <span> No Permissions</span>
    @endif
</x-livewire-tables::table.cell>
<x-livewire-tables::table.cell>
    @can('update_order')
    <a href="{{ route('control.roles.view', ['role_id' => $row->hashId()]) }}"
        class="inline-flex items-center px-4 py-2 bg-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-dark active:bg-gray-900 focus:outline-none focus:border-primary-faded focus:ring focus:ring-gray-300 disabled:opacity-25 transition ml-2">
        <i class="fa fa-edit"></i>
    </a>
    @endcan
    {{-- <x-form.danger-button wire:click="$emit('openModal', 'control.orders.delete-order',  {{ json_encode([$row->hashId()]) }} )"
    >
        <i class="fa fa-trash-alt"></i>
    </x-form.danger-button> --}}
</x-livewire-tables::table.cell>
