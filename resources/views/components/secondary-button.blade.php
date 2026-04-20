<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-6 py-3 bg-white/5 border border-white/20 rounded-xl font-semibold text-sm text-gray-200 uppercase tracking-wider shadow-lg hover:bg-white/10 hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 focus:ring-offset-black disabled:opacity-25 transition-all duration-300']) }}>
    {{ $slot }}
</button>
