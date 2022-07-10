
<label {{ $attributes->merge(['class' => $getClassAttributes() . ' inline-block rounded-full px-2 py-1 text-xs font-bold ']) }}>
    {{ $slot }}
</label>
