<x-livewire-tables::table.cell>
    <a href="{{  route('control.user.view', ['user_id' => $row->hashId()]) }}" class="">{{ ucfirst($row->username) }}</a>
</x-livewire-tables::table.cell>
<x-livewire-tables::table.cell>
    {{ ucfirst($row->first_name) }}
</x-livewire-tables::table.cell>
<x-livewire-tables::table.cell>
    {{ $row->last_name }}
</x-livewire-tables::table.cell>
<x-livewire-tables::table.cell>
    {{ $row->email }}
</x-livewire-tables::table.cell>
<x-livewire-tables::table.cell>
    {{ $row->role }}
</x-livewire-tables::table.cell>
<x-livewire-tables::table.cell>
    {{ $row->phone_number }}
</x-livewire-tables::table.cell>
<x-livewire-tables::table.cell>
    @include('control.livewire.general.boolean', ['boolean' => $row->email_verified_at])
</x-livewire-tables::table.cell>
<x-livewire-tables::table.cell>
    @include('control.livewire.general.boolean', ['boolean' => $row->phone_verified_at])
</x-livewire-tables::table.cell>
<x-livewire-tables::table.cell>
    @include('control.livewire.general.boolean', ['boolean' => $row->profile->proof_of_address_verified_at])
</x-livewire-tables::table.cell>
<x-livewire-tables::table.cell>
    @include('control.livewire.general.boolean', ['boolean' => $row->profile->identity_verified_at])
</x-livewire-tables::table.cell>
<x-livewire-tables::table.cell>
    @can('edit_user')
   <a href="{{  route('control.user.view', ['user_id' => $row->hashId()]) }}" class="rounded-sm bg-gray-500 text-white mt-4 p-2 font-bold items-center hover:bg-gray-700" > <i class="fa fa-eye"></i> </a>
   
    <x-form.primary-button class="ml-2" wire:click="$emit('openModal', 'control.users.edit-user',  {{ json_encode([$row->hashId()]) }} )"
    >
        <i class="fa fa-edit"></i>
    </x-form.primary-button>
    @endcan
    @can('delete_user')
    <x-form.danger-button wire:click="$emit('openModal', 'control.users.delete-user',  {{ json_encode([$row->hashId()]) }} )"
    >
        <i class="fa fa-trash-alt"></i>
    </x-form.danger-button>
    @endcan
    {{-- <x-form.danger-button wire:click="$emit('openModal', 'control.users.delete-user',  {{ json_encode([$row->hashId()]) }} )"
    >
        <i class="fa fa-envelope"></i>
    </x-form.danger-button> --}}
</x-livewire-tables::table.cell>

