<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 border border-transparent rounded-xl font-semibold text-sm text-white uppercase tracking-wider shadow-lg hover:shadow-red-500/30 hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:ring-offset-black transition-all duration-300']) }}>
    {{ $slot }}
</button>
