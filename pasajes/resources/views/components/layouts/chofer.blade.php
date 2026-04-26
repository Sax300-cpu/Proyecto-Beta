<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Panel del Chofer — Sistema de Pasajes">
    <title>Panel Chofer — {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/html5-qrcode" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
    @livewireStyles
</head>
<body class="h-full bg-gray-950 text-white">

    {{-- Barra móvil del chofer --}}
    <header class="bg-gray-900 border-b border-gray-800 px-4 py-3 flex items-center justify-between sticky top-0 z-50 shadow-lg">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 bg-yellow-500 rounded-lg flex items-center justify-center text-black">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-4.724A1 1 0 013 14.382V5a1 1 0 011-1h4l2 4h4l2-4h4a1 1 0 011 1v9.382a1 1 0 01-.553.894L13 20H9z"/>
                </svg>
            </div>
            <div>
                <p class="font-bold text-sm leading-tight">{{ auth()->user()->name }}</p>
                <p class="text-yellow-300 text-xs">Chofer</p>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-xs px-3 py-1.5 bg-gray-800 hover:bg-gray-700 border border-gray-700 rounded-lg transition">
                Salir
            </button>
        </form>
    </header>

    @if(session('success'))
    <div class="bg-green-800 border-b border-green-700 text-green-100 px-4 py-2 text-sm">
        ✅ {{ session('success') }}
    </div>
    @endif
    @if(session('info'))
    <div class="bg-yellow-900/40 border-b border-yellow-700 text-yellow-100 px-4 py-2 text-sm">
        ℹ️ {{ session('info') }}
    </div>
    @endif

    <main class="min-h-[calc(100vh-56px)]">
        {{ $slot }}
    </main>

    @livewireScripts
    <script src="//unpkg.com/alpinejs" defer></script>
</body>
</html>
