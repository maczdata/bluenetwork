<x-livewire-tables::table.cell>
    {{ $row?->code }}
</x-livewire-tables::table.cell>
<x-livewire-tables::table.cell>
    @include('control.livewire.general.boolean', ['boolean' => $row->enabled])
</x-livewire-tables::table.cell>
<x-livewire-tables::table.cell>
    {{ $row->couponable?->title }}
</x-livewire-tables::table.cell>
<x-livewire-tables::table.cell>
    {{ ucfirst($row->type)  }}
</x-livewire-tables::table.cell>
<x-livewire-tables::table.cell>
    {{ core()->formatBasePrice($row->value  ?? 0) }}
</x-livewire-tables::table.cell>
<x-livewire-tables::table.cell>
    {{ $row->percentage_off . '%'}}
</x-livewire-tables::table.cell>
<x-livewire-tables::table.cell>
    {{ $row->uses  }}
</x-livewire-tables::table.cell>
<x-livewire-tables::table.cell>
    {{ $row->max_uses  }}
</x-livewire-tables::table.cell>
<x-livewire-tables::table.cell>
    {{ $row->starts_at  }}
</x-livewire-tables::table.cell>
<x-livewire-tables::table.cell>
    {{ $row->expires_at  }}
</x-livewire-tables::table.cell>
<x-livewire-tables::table.cell>
    {{ $row->created_at  }}
</x-livewire-tables::table.cell>
<x-livewire-tables::table.cell>
    <x-form.primary-button class="ml-2" wire:click="$emit('openModal', 'control.coupons.edit-coupon',  {{ json_encode([$row->hashId()]) }} )"
    >
        <i class="fa fa-edit"></i>
    </x-form.primary-button>
    <x-form.danger-button onclick="Livewire.emit('openModal', 'control.coupons.delete-coupon',  {{ json_encode([$row->hashId()]) }} )"
        >
        <i class="fa fa-trash-alt"></i>
    </x-form.danger-button>
</x-livewire-tables::table.cell>
