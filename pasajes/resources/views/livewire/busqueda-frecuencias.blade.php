<div class="max-w-7xl mx-auto px-4 py-10">

    {{-- Hero de búsqueda --}}
    <div class="text-center mb-10">
        <h1 class="text-4xl font-extrabold text-white mb-2">Compra tu pasaje</h1>
        <p class="text-gray-400">Transporte interprovincial seguro y confiable</p>
    </div>

    {{-- Formulario de búsqueda --}}
    <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6 mb-10 shadow-2xl">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <div>
                <label class="block text-xs font-semibold text-gray-400 mb-1 uppercase tracking-wide">Origen</label>
                <input wire:model.live.debounce.400ms="origen"
                    type="text" placeholder="ej: Ambato"
                    class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-brand-500 transition">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-400 mb-1 uppercase tracking-wide">Destino</label>
                <input wire:model.live.debounce.400ms="destino"
                    type="text" placeholder="ej: Quito"
                    class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-brand-500 transition">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-400 mb-1 uppercase tracking-wide">Fecha</label>
                <input wire:model.live="fecha"
                    type="date" min="{{ today()->format('Y-m-d') }}"
                    class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-brand-500 transition">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-400 mb-1 uppercase tracking-wide">Tipo de viaje</label>
                <select wire:model.live="tipo_viaje"
                    class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-brand-500 transition">
                    <option value="">Todos</option>
                    <option value="directa">Directo</option>
                    <option value="con_paradas">Con paradas</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-400 mb-1 uppercase tracking-wide">Cooperativa</label>
                <input wire:model.live.debounce.400ms="cooperativa"
                    type="text" placeholder="ej: Flota Pelileo"
                    class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-brand-500 transition">
            </div>
        </div>
    </div>

    {{-- Loading --}}
    <div wire:loading class="flex justify-center py-10">
        <div class="flex items-center gap-3 text-brand-400">
            <svg class="animate-spin w-6 h-6" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
            </svg>
            <span class="font-medium">Buscando frecuencias...</span>
        </div>
    </div>

    {{-- Resultados --}}
    <div wire:loading.remove>
        @if($hojas->isEmpty() && $origen && $destino)
        <div class="text-center py-16">
            <div class="text-6xl mb-4">🚌</div>
            <h3 class="text-xl font-semibold text-gray-300">No hay frecuencias disponibles</h3>
            <p class="text-gray-500 mt-2">Intenta con otra fecha u origen/destino diferente.</p>
        </div>
        @elseif(!$origen || !$destino)
        <div class="text-center py-16">
            <div class="text-6xl mb-4">🗺️</div>
            <p class="text-gray-400">Ingresa origen y destino para buscar frecuencias.</p>
        </div>
        @else
        <div class="space-y-6">
            @foreach($hojas as $hoja)
            <div class="bg-gray-900 border border-gray-800 hover:border-brand-600 rounded-3xl overflow-hidden transition-all duration-300 group shadow-lg hover:shadow-brand-900/20">
                <div class="flex flex-col md:flex-row">
                    
                    {{-- Foto del bus --}}
                    <div class="md:w-64 h-48 md:h-auto bg-gray-800 relative flex-shrink-0">
                        @if($hoja->bus->foto_url)
                            <img src="{{ asset('storage/' . $hoja->bus->foto_url) }}" alt="Foto Bus" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-gray-600">
                                <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span class="text-xs">Sin foto</span>
                            </div>
                        @endif
                        <div class="absolute top-3 left-3">
                            <span class="px-3 py-1 rounded-full text-xs font-bold shadow-md bg-gray-900/80 text-white backdrop-blur-sm border border-gray-700/50">
                                Bus #{{ $hoja->bus->numero_disco }}
                            </span>
                        </div>
                        <div class="absolute bottom-3 right-3">
                            <span class="px-3 py-1 rounded-full text-xs font-bold shadow-md {{ $hoja->frecuencia->es_directa ? 'bg-emerald-500 text-white' : 'bg-brand-500 text-black' }}">
                                {{ $hoja->frecuencia->es_directa ? '⚡ Directo' : '🛑 Con paradas' }}
                            </span>
                        </div>
                    </div>

                    {{-- Detalles --}}
                    <div class="flex-1 p-6 flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex items-center gap-4">
                                    {{-- Logo Cooperativa --}}
                                    @if($hoja->frecuencia->ruta->cooperativa->logo_url)
                                        <img src="{{ asset('storage/' . $hoja->frecuencia->ruta->cooperativa->logo_url) }}" alt="Logo Coop" class="w-12 h-12 rounded-lg object-contain bg-white p-1 border border-gray-700">
                                    @else
                                        <div class="w-12 h-12 rounded-lg bg-gray-800 border border-gray-700 flex items-center justify-center text-xl shadow-inner">
                                            🏢
                                        </div>
                                    @endif
                                    
                                    <div>
                                        <h3 class="font-bold text-white text-lg tracking-tight">{{ $hoja->frecuencia->ruta->cooperativa->nombre }}</h3>
                                        <p class="text-sm text-gray-500 flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Salida: <span class="text-white font-semibold">{{ \Carbon\Carbon::parse($hoja->frecuencia->hora_salida)->format('H:i') }}</span>
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-3xl font-black text-brand-400">${{ number_format($hoja->bus->categoria->precio_base, 2) }}</p>
                                    <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold mt-1">por persona</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-4 py-4 px-5 bg-gray-950/50 rounded-2xl border border-gray-800/60 mb-2">
                                <div class="flex-1">
                                    <p class="text-xs text-gray-500 uppercase tracking-wider mb-1 font-semibold">Origen</p>
                                    <p class="font-bold text-lg text-white">{{ $hoja->frecuencia->ruta->origen }}</p>
                                </div>
                                <div class="px-4 text-gray-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                </div>
                                <div class="flex-1 text-right">
                                    <p class="text-xs text-gray-500 uppercase tracking-wider mb-1 font-semibold">Destino</p>
                                    <p class="font-bold text-lg text-white">{{ $hoja->frecuencia->ruta->destino }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between mt-4">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full {{ $hoja->asientos_disponibles > 5 ? 'bg-emerald-500' : 'bg-orange-500' }} animate-pulse"></div>
                                <p class="text-sm font-medium {{ $hoja->asientos_disponibles > 5 ? 'text-emerald-400' : 'text-orange-400' }}">
                                    {{ $hoja->asientos_disponibles }} asientos libres
                                </p>
                            </div>

                            @auth
                            <a href="{{ route('boleto.comprar', $hoja->id) }}"
                               class="px-6 py-2.5 bg-brand-600 hover:bg-brand-500 text-white text-sm font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-brand-500/25 flex items-center gap-2
                               {{ $hoja->asientos_disponibles === 0 ? 'opacity-50 pointer-events-none' : '' }}">
                                {{ $hoja->asientos_disponibles === 0 ? 'Agotado' : 'Seleccionar Asientos' }}
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                            @else
                            <a href="{{ route('login') }}"
                               class="px-6 py-2.5 bg-gray-800 hover:bg-gray-700 text-white text-sm font-bold rounded-xl transition-all duration-200">
                                Iniciar sesión para comprar
                            </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
