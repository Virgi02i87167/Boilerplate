<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" 
    x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))"
    :class="{ 'dark': darkMode }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased selection:bg-indigo-500 selection:text-white">
    <div
        class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-[#FDFDFC] dark:bg-[#0a0a0a] relative overflow-hidden">
        <!-- Fondos Decorativos Premium -->
        <div
            class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-indigo-200/30 dark:bg-indigo-900/10 rounded-full blur-[120px] pointer-events-none">
        </div>
        <div
            class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-purple-200/30 dark:bg-purple-900/10 rounded-full blur-[120px] pointer-events-none">
        </div>

        <div class="relative z-10 w-full flex flex-col items-center">
            {{ $slot }}
        </div>
    </div>

    <!-- Toggle Dark Mode Floating -->
    <button onclick="toggleDarkMode()"
        class="fixed bottom-8 right-8 p-3 bg-white dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-full shadow-lg transition-transform hover:scale-110 dark:text-white z-50">
        <span id="theme-icon">🌙</span>
    </button>

    <script>
        function updateTheme() {
            const isDark = localStorage.getItem('darkMode') === 'true';
            document.documentElement.classList.toggle('dark', isDark);
            const icon = document.getElementById('theme-icon');
            if (icon) {
                icon.innerText = isDark ? '☀️' : '🌙';
            }
        }

        function toggleDarkMode() {
            const isDark = localStorage.getItem('darkMode') === 'true';
            localStorage.setItem('darkMode', !isDark);
            updateTheme();
            // Optional: sync with Alpine if it's there
            if (window.Alpine) {
                // This will try to find the html element and update its data
                const html = document.querySelector('html');
                if (html && html.__x) {
                    html.__x.$data.darkMode = !isDark;
                }
            }
        }

        // Initialize theme on load
        updateTheme();
    </script>
</body>

</html>