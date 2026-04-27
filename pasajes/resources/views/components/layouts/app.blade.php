<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistema de gestión y venta de pasajes de transporte interprovincial Ecuador">
    <title>{{ config('app.name') }} — {{ $title ?? 'Inicio' }}</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .glass { background: rgba(255,255,255,0.08); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.15); }
        .seat-available { transition: all 0.2s ease; }
        .seat-available:hover { transform: scale(1.15); }
        [x-cloak] { display: none !important; }
    </style>
    @livewireStyles
</head>
<body class="h-full bg-gray-950 text-gray-100 flex flex-col min-h-screen">

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
                          'bg-brand-900/90 border-brand-700 text-brand-100 backdrop-blur-md': toast.type === 'info',
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

    {{-- Session Flash Fallback (For standard HTTP redirects) --}}
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition class="fixed top-20 right-4 z-50 max-w-sm w-full pointer-events-none">
            <div class="pointer-events-auto bg-emerald-900/90 border border-emerald-700 text-emerald-200 px-4 py-3 rounded-2xl shadow-xl flex justify-between items-start backdrop-blur-md">
                <div class="flex items-center gap-3">
                    <span class="text-xl">✅</span>
                    <p class="font-medium text-sm">{{ session('success') }}</p>
                </div>
                <button @click="show = false" class="opacity-60 hover:opacity-100 ml-4 transition">✕</button>
            </div>
        </div>
    @endif
    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)" x-transition class="fixed top-20 right-4 z-50 max-w-sm w-full pointer-events-none">
            <div class="pointer-events-auto bg-red-900/90 border border-red-700 text-red-200 px-4 py-3 rounded-2xl shadow-xl flex justify-between items-start backdrop-blur-md">
                <div class="flex items-center gap-3">
                    <span class="text-xl">❌</span>
                    <p class="font-medium text-sm">{{ session('error') }}</p>
                </div>
                <button @click="show = false" class="opacity-60 hover:opacity-100 ml-4 transition">✕</button>
            </div>
        </div>
    @endif

    {{-- Navbar --}}
    <nav x-data="{ mobileMenuOpen: false }" class="bg-gray-950/80 backdrop-blur-xl border-b border-gray-800 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <a href="{{ route('home') }}" class="flex items-center gap-3 group z-50 relative">
                    <div class="w-10 h-10 bg-gradient-to-br from-brand-500 to-brand-600 rounded-xl flex items-center justify-center shadow-lg shadow-brand-500/20 group-hover:scale-105 transition-transform text-black">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                    </div>
                    <span class="font-black text-xl tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-400">PasajesEcuador</span>
                </a>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center gap-4">
                    @auth
                        <div class="flex items-center gap-2 mr-4">
                            <div class="w-8 h-8 rounded-full bg-gray-800 flex items-center justify-center text-sm font-bold border border-gray-700">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-white leading-tight">{{ auth()->user()->name }}</span>
                                <span class="text-[10px] uppercase tracking-wider text-brand-400 font-bold">{{ auth()->user()->getRoleNames()->first() }}</span>
                            </div>
                        </div>

                        @if(auth()->user()->hasAnyRole(['Admin','Oficinista']))
                        <a href="{{ route('admin.dashboard') }}" class="text-sm font-semibold text-gray-300 hover:text-white transition px-2">Panel</a>
                        @endif

                        @if(auth()->user()->hasRole('Chofer'))
                        <a href="{{ route('chofer.escaner') }}" class="text-sm font-semibold text-gray-300 hover:text-white transition px-2">Escáner</a>
                        @endif

                        <a href="{{ route('mis-boletos') }}" class="text-sm font-semibold text-gray-300 hover:text-white transition px-2">Mis Boletos</a>

                        <form method="POST" action="{{ route('logout') }}" class="ml-2">
                            @csrf
                            <button type="submit" class="text-sm px-4 py-2 bg-gray-800 hover:bg-gray-700 text-white rounded-xl font-semibold border border-gray-700 transition">
                                Salir
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-300 hover:text-white transition px-2">
                            Ingresar
                        </a>
                        <a href="{{ route('register') }}" class="text-sm px-5 py-2.5 bg-brand-600 hover:bg-brand-500 text-white rounded-xl font-bold shadow-lg shadow-brand-500/20 transition">
                            Crear cuenta
                        </a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <div class="flex md:hidden items-center z-50 relative">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-400 hover:text-white focus:outline-none">
                        <svg x-show="!mobileMenuOpen" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                        <svg x-show="mobileMenuOpen" style="display: none;" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Overlay -->
        <div x-show="mobileMenuOpen" x-transition.opacity style="display: none;" class="fixed inset-0 bg-black/80 backdrop-blur-md z-40 md:hidden"></div>

        <!-- Mobile Menu Content -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-4"
             style="display: none;"
             class="absolute top-16 left-0 right-0 bg-gray-900 border-b border-gray-800 z-40 md:hidden shadow-2xl">
            <div class="px-4 py-6 space-y-4 flex flex-col">
                @auth
                    <div class="flex items-center gap-3 pb-4 border-b border-gray-800">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-brand-600 to-brand-500 flex items-center justify-center text-lg font-bold text-white">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="flex flex-col">
                            <span class="font-bold text-white">{{ auth()->user()->name }}</span>
                            <span class="text-xs uppercase tracking-wider text-brand-400 font-bold">{{ auth()->user()->getRoleNames()->first() }}</span>
                        </div>
                    </div>

                    @if(auth()->user()->hasAnyRole(['Admin','Oficinista']))
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center text-base font-semibold text-gray-300 hover:text-white py-2">
                        Panel de Administración
                    </a>
                    @endif

                    @if(auth()->user()->hasRole('Chofer'))
                    <a href="{{ route('chofer.escaner') }}" class="flex items-center text-base font-semibold text-gray-300 hover:text-white py-2">
                        Panel de Chofer
                    </a>
                    @endif

                    <a href="{{ route('mis-boletos') }}" class="flex items-center text-base font-semibold text-gray-300 hover:text-white py-2">
                        Mis Boletos
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="pt-4 border-t border-gray-800">
                        @csrf
                        <button type="submit" class="w-full text-center py-3 bg-red-500/10 hover:bg-red-500/20 text-red-400 rounded-xl font-bold transition">
                            Cerrar Sesión
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block w-full text-center py-3 font-semibold text-gray-300 hover:text-white bg-gray-800 rounded-xl border border-gray-700">
                        Ingresar a mi cuenta
                    </a>
                    <a href="{{ route('register') }}" class="block w-full text-center py-3 bg-brand-600 hover:bg-brand-500 text-white rounded-xl font-bold shadow-lg shadow-brand-500/20 transition">
                        Crear una cuenta nueva
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Content --}}
    <main class="flex-grow">
        {{ $slot }}
    </main>

    <footer class="bg-[#0a0a0b] border-t border-gray-800/60 py-8 mt-auto">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <div class="w-10 h-10 bg-gray-900 rounded-xl flex items-center justify-center mx-auto mb-4 border border-gray-800">
                <svg class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <p class="text-gray-500 text-sm font-medium">
                © {{ date('Y') }} Sistema de Pasajes — Transporte Interprovincial Ecuador
            </p>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
