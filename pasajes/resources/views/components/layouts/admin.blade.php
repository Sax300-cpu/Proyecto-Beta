<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Admin - Sistema de Pasajes' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-gray-950 text-gray-200 antialiased font-sans flex h-screen overflow-hidden selection:bg-brand-500 selection:text-white">

    {{-- Global Toast Notification System --}}
    <div x-data="{ 
            toasts: [],
            addToast(message, type = 'success') {
                const id = Date.now();
                this.toasts.push({ id, message, type });
                setTimeout(() => this.removeToast(id), 5000);
            },
            removeToast(id) {
                this.toasts = this.toasts.filter(t => t.id !== id);
            }
         }"
         @toast.window="addToast($event.detail.message, $event.detail.type)"
         class="fixed top-20 right-4 z-[100] flex flex-col gap-3 w-full max-w-sm pointer-events-none">
        
        <template x-for="toast in toasts" :key="toast.id">
            <div x-show="true" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-x-full"
                 x-transition:enter-end="opacity-100 translate-x-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-x-0"
                 x-transition:leave-end="opacity-0 translate-x-full"
                 class="pointer-events-auto shadow-xl rounded-2xl p-4 border flex justify-between items-start"
                 :class="{
                    'bg-emerald-900/90 border-emerald-700 text-emerald-200 backdrop-blur-md': toast.type === 'success',
                    'bg-red-900/90 border-red-700 text-red-200 backdrop-blur-md': toast.type === 'error',
                    'bg-brand-900/90 border-brand-700 text-brand-200 backdrop-blur-md': toast.type === 'info',
                    'bg-yellow-900/90 border-yellow-700 text-yellow-200 backdrop-blur-md': toast.type === 'warning'
                 }">
                <div class="flex items-center gap-3">
                    <span x-show="toast.type === 'success'" class="text-xl">✅</span>
                    <span x-show="toast.type === 'error'" class="text-xl">❌</span>
                    <span x-show="toast.type === 'info'" class="text-xl">ℹ️</span>
                    <span x-show="toast.type === 'warning'" class="text-xl">⚠️</span>
                    <p class="font-medium text-sm" x-text="toast.message"></p>
                </div>
                <button @click="removeToast(toast.id)" class="opacity-60 hover:opacity-100 ml-4 transition">✕</button>
            </div>
        </template>
    </div>

    {{-- Sidebar --}}
    <aside class="w-64 bg-[#0a0a0b] border-r border-gray-800/60 flex flex-col hidden md:flex relative overflow-hidden">
        <!-- Glow effect -->
        <div class="absolute top-0 left-0 w-full h-32 bg-brand-600/10 blur-[50px] -z-10 pointer-events-none"></div>
        
        <div class="h-20 flex items-center px-6 border-b border-gray-800/60">
            <a href="{{ route('home') }}" class="text-xl font-black text-transparent bg-clip-text bg-gradient-to-r from-brand-400 to-indigo-500 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-brand-500 to-indigo-600 flex items-center justify-center text-white shadow-lg shadow-brand-500/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                AdminPanel
            </a>
        </div>

        <nav class="flex-1 px-4 py-8 space-y-2 overflow-y-auto">
            <div class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-4 px-3">General</div>
            
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-brand-600 text-white shadow-md shadow-brand-600/20' : 'text-gray-400 hover:text-white hover:bg-gray-800/50' }}">
                <span class="text-lg">📊</span> Dashboard
            </a>
            
            <div class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mt-8 mb-4 px-3">Operaciones</div>
            
            <a href="{{ route('admin.pagos') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.pagos') ? 'bg-brand-600 text-white shadow-md shadow-brand-600/20' : 'text-gray-400 hover:text-white hover:bg-gray-800/50' }}">
                <span class="text-lg">💳</span> Validar Pagos
            </a>
            <a href="{{ route('admin.hojas-ruta.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.hojas-ruta.*') ? 'bg-brand-600 text-white shadow-md shadow-brand-600/20' : 'text-gray-400 hover:text-white hover:bg-gray-800/50' }}">
                <span class="text-lg">📋</span> Hojas de Ruta
            </a>

            @hasrole('Admin')
            <div class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mt-8 mb-4 px-3">Catálogos</div>

            <a href="{{ route('admin.buses.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.buses.*') ? 'bg-brand-600 text-white shadow-md shadow-brand-600/20' : 'text-gray-400 hover:text-white hover:bg-gray-800/50' }}">
                <span class="text-lg">🚌</span> Buses
            </a>
            <a href="{{ route('admin.rutas.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.rutas.*') ? 'bg-brand-600 text-white shadow-md shadow-brand-600/20' : 'text-gray-400 hover:text-white hover:bg-gray-800/50' }}">
                <span class="text-lg">🗺️</span> Rutas
            </a>
            <a href="{{ route('admin.frecuencias.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.frecuencias.*') ? 'bg-brand-600 text-white shadow-md shadow-brand-600/20' : 'text-gray-400 hover:text-white hover:bg-gray-800/50' }}">
                <span class="text-lg">⏰</span> Frecuencias
            </a>
            <a href="{{ route('admin.usuarios') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.usuarios') ? 'bg-brand-600 text-white shadow-md shadow-brand-600/20' : 'text-gray-400 hover:text-white hover:bg-gray-800/50' }}">
                <span class="text-lg">👥</span> Usuarios
            </a>
            <a href="{{ route('admin.cooperativa') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.cooperativa') ? 'bg-brand-600 text-white shadow-md shadow-brand-600/20' : 'text-gray-400 hover:text-white hover:bg-gray-800/50' }}">
                <span class="text-lg">🏢</span> Cooperativa
            </a>
            @endhasrole
        </nav>

        <div class="p-4 border-t border-gray-800/60 bg-gradient-to-t from-gray-900 to-transparent">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="flex items-center gap-3 px-3 py-2.5 w-full text-left rounded-xl text-sm font-semibold text-gray-400 hover:text-red-400 hover:bg-red-500/10 transition-all duration-200">
                    <span class="text-lg">🚪</span> Cerrar Sesión
                </button>
            </form>
        </div>
    </aside>

    {{-- Contenido Principal --}}
    <main class="flex-1 flex flex-col h-screen overflow-hidden bg-[#030712] relative">
        <!-- Decoración de fondo sutil -->
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-brand-500/5 rounded-full blur-[100px] pointer-events-none -z-10"></div>
        <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-indigo-500/5 rounded-full blur-[100px] pointer-events-none -z-10"></div>

        {{-- Header superior --}}
        <header class="h-20 border-b border-gray-800/40 flex items-center justify-between px-8 bg-[#0a0a0b]/80 backdrop-blur-xl z-10 sticky top-0">
            <div class="flex items-center gap-4">
                <button class="md:hidden text-gray-400 hover:text-white transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                </button>
                <div class="hidden md:block">
                    <h2 class="text-lg font-bold text-white leading-tight tracking-tight">{{ $title ?? 'Dashboard' }}</h2>
                    <p class="text-xs text-gray-500 font-medium">Gestionando tu Cooperativa</p>
                </div>
            </div>
            
            <div class="flex items-center gap-5">
                <button class="w-10 h-10 rounded-full bg-gray-900 border border-gray-800 flex items-center justify-center text-gray-400 hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                </button>
                <div class="h-8 w-px bg-gray-800/60 hidden sm:block"></div>
                <div class="flex items-center gap-3">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-bold text-white">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-brand-400 font-medium tracking-wide">Administrador</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-brand-600 to-indigo-600 flex items-center justify-center text-white font-bold shadow-lg shadow-brand-500/30 ring-2 ring-gray-900 cursor-pointer">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                </div>
            </div>
        </header>

        {{-- Contenedor scrolleable --}}
        <div class="flex-1 overflow-y-auto p-4 md:p-8 z-0">
            {{ $slot }}
        </div>
    </main>

</body>
</html>

