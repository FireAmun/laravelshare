<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 border border-transparent rounded-xl font-semibold text-sm text-white tracking-wide hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 active:from-indigo-800 active:to-purple-800 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl']) }}>
    {{ $slot }}
</button>
