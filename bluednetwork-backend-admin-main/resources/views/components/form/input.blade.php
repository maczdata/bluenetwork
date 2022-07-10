
<input 
    {{ $disabled ? 'disabled' : '' }} 
    type="{{ $type }}" 
    id="{{ $id }}" 
    name="{{ $name }}"
    value="{{ $value }}"
    {!! $attributes->merge([
        'class' => 'border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 shadow-sm',
    ]) !!}>
