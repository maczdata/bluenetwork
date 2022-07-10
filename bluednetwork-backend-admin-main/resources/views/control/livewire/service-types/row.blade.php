<x-livewire-tables::table.cell>
    {{-- <a href="{{  route('control.service-type.view', ['service_type_id' => $row->hashId()]) }}" class=""> --}}
        {{ ucfirst($row?->title) }}
    {{-- </a> --}}
</x-livewire-tables::table.cell>
<x-livewire-tables::table.cell>
    {{ $row->slug }}
</x-livewire-tables::table.cell>
<x-livewire-tables::table.cell>
    @include('control.livewire.general.boolean', ['boolean' => $row->enabled])
</x-livewire-tables::table.cell>
<x-livewire-tables::table.cell>
    {{ $row->created_at  }}
</x-livewire-tables::table.cell>
<x-livewire-tables::table.cell>
    @can('update_service_type')
    <x-form.primary-button class="ml-2" wire:click="$emit('openModal', 'control.service-types.edit-service-types',  {{ json_encode([$row->hashId()]) }} )"
    >
        <i class="fa fa-edit"></i>
    </x-form.primary-button>
    @endcan
    @can('delete_service_type')
    <x-form.danger-button wire:click="$emit('openModal', 'control.service-types.delete-service-types',  {{ json_encode([$row->hashId()]) }} )"
    >
        <i class="fa fa-trash-alt"></i>
    </x-form.danger-button>
    @endcan
</x-livewire-tables::table.cell>
