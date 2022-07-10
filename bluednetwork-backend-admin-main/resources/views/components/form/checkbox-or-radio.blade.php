<input {!! $attributes->merge(['class' => $type === 'checkbox' ? 'rounded border-gray-300 text-primary shadow-sm focus:border-primary focus:ring lfocus:ring-indigo-200 lfocus:ring-opacity-50' : 'rounded h-4 w-4 text-primary border-blue-gray-300 focus:ring-blue-500']) !!}
       @if ($name) name="{{ $name }}" @endif
       @if ($id) id="{{ $id }}" @endif
       type="{{ $type }}"
       @if ($value) value="{{ $value }}" @endif
       @if ($checked && ! $attributes->hasStartsWith('wire:model')) checked @endif
    {{ $extraAttributes }}
/>

