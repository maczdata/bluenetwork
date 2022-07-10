<div wire:ignore>
    <input
        x-data
        x-init="flatpickr($refs.input,{{ $jsonOptions() }} )"
        x-ref="input"
        type="text"
        name="{{ $name }}"
        id="{{ $id }}"
        placeholder="{{ $placeholder }}"
        @if($value)value="{{ $value }}"@endif
        {{ $attributes->merge(['class' => 'block w-full disabled:bg-gray-200 border border-gray-300 rounded-md focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 sm:text-sm sm:leading-5']) }}
    />
</div>
