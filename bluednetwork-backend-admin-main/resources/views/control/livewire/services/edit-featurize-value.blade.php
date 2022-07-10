<div class="p-3">
    <x-form.form-section
        wire:submit.prevent="update"
        title="{{ 'Update Feature Value'}}"
        description="Update Feature Value"
    >
        <x-slot name="content">
            <x-form.validation-errors />
        </x-slot>
        <x-slot name="form">
            <div class="col-span-6 sm:col-span-6">
                <x-form.label for="title" value="{{ __('Title') }}" />
                <x-form.input id="title"  type="text" name="title" value="{{ $featurize->title }}" wire:model="title" />
                <x-form.input-error for="title" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-6">
                <x-form.label for="description" value="{{ __('Description') }}" />
                <x-form.textarea id="description" name="description" wire:model="description" >
                    {{ $featurize->description }}
                </x-form.textarea>
                <x-form.input-error for="description" class="mt-2" />
            </div>
            <div class="col-span-12 sm:col-span-12">
            @if(count($valueMetas) > 0)
                @foreach($valueMetas as $key => $meta)
                <div class="grid grid-cols-3 mt-2" wire:key="meta-{{ $key }}">
                    <div class="">
                        <x-form.label value="{{ __('Meta Key') }}" />
                        <x-form.input type="text" value="{{$meta['key']}}" wire:model="valueMetas.{{ $key }}.key" autocomplete="name" />
                    </div>
                    <div class="ml-3">
                        <x-form.label value="{{ __('Meta Value') }}" />
                        <x-form.input type="text" value="{{$meta['value']}}" wire:model="valueMetas.{{ $key }}.value" autocomplete="name" />
                    </div>
                    <div class="ml-3 mt-3">
                        <x-form.danger-button class="mt-2" wire:click.prevent="removeRow('meta', '{{$key}}')">
                            {{ __('Remove') }}
                        </x-form.danger-button>
                    </div>
                </div>
                @endforeach 
            @endif
            <x-form.button class="mt-2" wire:click.prevent="addNewRow('meta')">
                {{ __('Add More') }}
            </x-form.button>
        </div>
        </x-slot>
    
        <x-slot name="actions">
            <x-general.action-message class="mr-3" on="saved">
                {{ __('Updated.') }}
            </x-general.action-message>
    
            <x-form.button>
                {{ __('Update Feature Value') }}
            </x-form.button>
        </x-slot>
    </x-form.form-section>
</div>
