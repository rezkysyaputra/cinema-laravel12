<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-dark-card border border-gray-700 rounded-md font-semibold text-xs text-gray-300 uppercase tracking-widest shadow-sm hover:bg-dark-lighter focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
