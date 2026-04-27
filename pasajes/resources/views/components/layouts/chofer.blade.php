<!DOCTYPE html>
<html lang="es" class="h-full bg-black">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="Panel del Chofer — Sistema de Pasajes">
    <title>Panel Chofer — {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/html5-qrcode" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Outfit', sans-serif; }
        [x-cloak] { display: none !important; }
        body { -webkit-tap-highlight-color: transparent; }
    </style>
    @livewireStyles
</head>
<body class="h-full bg-gray-950 text-white max-w-[430px] mx-auto relative shadow-2xl shadow-black/50 border-x border-gray-900 overflow-x-hidden flex flex-col">

    {{-- Barra móvil del chofer --}}
    <header class="bg-gray-950/90 backdrop-blur-md border-b border-gray-800/80 px-5 py-4 flex items-center justify-between sticky top-0 z-50">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-brand-500/20 border border-brand-500/30 rounded-xl flex items-center justify-center text-brand-400">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <div>
                <p class="font-bold text-base leading-tight text-white">{{ auth()->user()->name }}</p>
                <div class="flex items-center gap-1.5 mt-0.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-brand-500 animate-pulse"></span>
                    <p class="text-brand-400 font-medium text-xs tracking-wide uppercase">Chofer</p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-10 h-10 bg-gray-900 hover:bg-gray-800 border border-gray-800 rounded-xl flex items-center justify-center text-gray-400 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
            </button>
        </form>
    </header>

    @if(session('success'))
    <div class="bg-emerald-900/50 border-b border-emerald-800 text-emerald-200 px-5 py-3 text-sm flex items-center gap-2">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        {{ session('success') }}
    </div>
    @endif
    @if(session('info'))
    <div class="bg-brand-900/30 border-b border-brand-800 text-brand-200 px-5 py-3 text-sm flex items-center gap-2">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        {{ session('info') }}
    </div>
    @endif

    <main class="flex-1 pb-24 overflow-y-auto p-4">
        {{ $slot }}
    </main>

    {{-- Bottom Navigation Bar --}}
    <nav class="fixed bottom-0 left-0 right-0 bg-gray-950/95 backdrop-blur-xl border-t border-gray-800/80 z-50 px-2 pb-safe pt-2 max-w-[430px] mx-auto border-x">
        <div class="flex justify-around items-center h-16">
            <a href="{{ Route::has('chofer.escaner') ? route('chofer.escaner') : '#' }}" class="flex flex-col items-center justify-center w-full h-full space-y-1 {{ request()->routeIs('chofer.escaner') ? 'text-brand-400' : 'text-gray-500 hover:text-gray-300' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                <span class="text-[10px] font-semibold tracking-wide">Escanear</span>
            </a>
            
            <a href="{{ Route::has('chofer.vender-en-ruta') ? route('chofer.vender-en-ruta') : '#' }}" class="flex flex-col items-center justify-center w-full h-full space-y-1 {{ request()->routeIs('chofer.vender*') ? 'text-brand-400' : 'text-gray-500 hover:text-gray-300' }}">
                <div class="relative {{ request()->routeIs('chofer.vender*') ? 'bg-brand-500/20 text-brand-400 p-2 rounded-xl' : '' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                </div>
                <span class="text-[10px] font-semibold tracking-wide">Vender</span>
            </a>

            <a href="{{ Route::has('chofer.manifiesto') ? route('chofer.manifiesto') : '#' }}" class="flex flex-col items-center justify-center w-full h-full space-y-1 {{ request()->routeIs('chofer.manifiesto') ? 'text-brand-400' : 'text-gray-500 hover:text-gray-300' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <span class="text-[10px] font-semibold tracking-wide">Pasajeros</span>
            </a>
        </div>
    </nav>

    @livewireScripts
</body>
</html>
