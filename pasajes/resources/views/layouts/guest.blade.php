<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sistema de Pasajes') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
    </style>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-gray-950 text-gray-200 antialiased font-sans selection:bg-brand-500 selection:text-white">
    <div class="flex min-h-screen">
        <!-- Left Side: Image -->
        <div class="hidden lg:flex lg:w-1/2 relative bg-gray-900 overflow-hidden">
            <div class="absolute inset-0 bg-brand-600/20 mix-blend-multiply z-10"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-gray-950 via-gray-950/40 to-transparent z-10"></div>
            <img src="https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?q=80&w=2069&auto=format&fit=crop" alt="Bus en Ecuador" class="absolute inset-0 w-full h-full object-cover">
            
            <div class="relative z-20 flex flex-col justify-end p-12 h-full">
                <h1 class="text-5xl font-black text-white mb-4">Conectando<br><span class="text-brand-400">Ecuador</span></h1>
                <p class="text-xl text-gray-300 max-w-md">Viaja seguro, cómodo y a tiempo con nuestra flota de buses modernos.</p>
            </div>
        </div>

        <!-- Right Side: Form -->
        <div class="w-full lg:w-1/2 flex flex-col justify-center items-center p-8 sm:p-12 relative overflow-hidden bg-[#030712]">
            <!-- Subtle background glows -->
            <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-brand-500/10 rounded-full blur-[100px] pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-indigo-500/10 rounded-full blur-[100px] pointer-events-none"></div>

            <div class="w-full max-w-md relative z-10">
                <a href="/" class="flex items-center gap-3 mb-10 group">
                    <div class="w-12 h-12 bg-gradient-to-br from-brand-500 to-brand-600 rounded-xl flex items-center justify-center shadow-lg shadow-brand-500/20 group-hover:scale-105 transition-transform text-black">
                        <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                    </div>
                    <span class="font-black text-2xl tracking-tight text-white">Pasajes<span class="text-brand-400">EC</span></span>
                </a>

                <div class="bg-[#0a0a0b]/60 backdrop-blur-xl border border-gray-800 p-8 rounded-3xl shadow-2xl">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</body>
</html>
