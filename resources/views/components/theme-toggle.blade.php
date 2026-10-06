<button 
    x-data 
    @click="$store.theme.toggle()" 
    type="button" 
    class="p-2 dark:text-gray-400 text-gray-600 hover:text-amber-500 transition-colors rounded-lg dark:bg-gray-800/40 bg-gray-100 border dark:border-gray-800 border-gray-200 focus:outline-none"
    title="Ganti Tema (Gelap / Terang)"
    aria-label="Ganti Tema (Gelap / Terang)"
>
    <!-- Sun icon for light mode (shows when dark mode is active) -->
    <svg x-cloak x-show="$store.theme.dark" class="w-5 h-5 transform transition-transform duration-200 hover:rotate-45" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
    </svg>
    <!-- Moon icon for dark mode (shows when light mode is active) -->
    <svg x-cloak x-show="!$store.theme.dark" class="w-5 h-5 transform transition-transform duration-200 hover:-rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
    </svg>
</button>


