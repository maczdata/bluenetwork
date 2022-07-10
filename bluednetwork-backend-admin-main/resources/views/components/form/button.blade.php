<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-7 py-3 bg-blue-900 border border-transparent rounded-full font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600 active:bg-gray-900 focus:outline-none focus:border-blue-800 focus:ring focus:ring-gray-300 disabled:opacity-25 transition']) }}>
    {{ $slot }}
</button>
