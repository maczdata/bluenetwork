<select {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'appearance-none border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 shadow-sm']) !!}>
    {{ $slot }}
</select>
