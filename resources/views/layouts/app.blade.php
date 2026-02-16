<!DOCTYPE html>
<html lang="es" x-data="{ darkMode: false, sidebarOpen: true }" :class="{ 'dark': darkMode }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mi Proyecto Laravel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] antialiased transition-colors duration-300">

    <div class="flex h-screen overflow-hidden">
        <aside x-show="sidebarOpen"
            class="w-64 bg-white dark:bg-[#161615] border-r border-[#e3e3e0] dark:border-[#3E3E3A] flex flex-col shrink-0 transition-all">
            <div class="p-6 font-bold text-xl border-b border-[#e3e3e0] dark:border-[#3E3E3A] dark:text-white">
                Menu
            </div>
            <nav class="flex-1 p-4 space-y-2">
                <a href="#"
                    class="block px-4 py-2 rounded-sm hover:bg-gray-100 dark:hover:bg-[#3E3E3A] dark:text-[#EDEDEC]">Inicio</a>
                <a href="#"
                    class="block px-4 py-2 rounded-sm hover:bg-gray-100 dark:hover:bg-[#3E3E3A] dark:text-[#EDEDEC]">Proyectos</a>
            </nav>
        </aside>

        <div class="flex-1 flex flex-col relative">
            <header
                class="h-16 bg-white dark:bg-[#161615] border-b border-[#e3e3e0] dark:border-[#3E3E3A] flex items-center justify-between px-8 shrink-0">
                <button @click="sidebarOpen = !sidebarOpen" class="dark:text-white">☰</button>

                <div class="flex items-center gap-4">
                    <span class="text-sm dark:text-[#A1A09A]">Bienvenida Erika</span>

                    <button @click="darkMode = !darkMode"
                        class="p-2 bg-[#dbdbd7] dark:bg-[#3E3E3A] rounded-full w-10 h-10 flex items-center justify-center">
                        <span x-text="darkMode ? '☀️' : '🌙'"></span>
                    </button>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-8">
                @yield('content')
            </main>
        </div>
    </div>
</body>

</html>