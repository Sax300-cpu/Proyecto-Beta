<x-layouts.admin>
    <x-slot:title>Dashboard Admin</x-slot:title>

    <div class="max-w-7xl mx-auto space-y-10 pb-10">
        {{-- Hero Header --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-400 mb-1">
                    Panel de Administración
                </h1>
                <p class="text-gray-400 font-medium tracking-wide">Bienvenido al centro de control de tu cooperativa.</p>
            </div>
            
            <div class="flex items-center gap-3">
                <button class="px-5 py-2.5 bg-gray-900 border border-gray-800 hover:border-gray-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-all flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    {{ now()->format('d M, Y') }}
                </button>
                <a href="{{ route('admin.hojas-ruta.index') }}" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-500 text-white text-sm font-bold rounded-xl shadow-lg shadow-brand-500/20 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Nueva Hoja de Ruta
                </a>
            </div>
        </div>

        {{-- Estadísticas Principales --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- Boletos Hoy --}}
            <div class="bg-gradient-to-b from-[#121214] to-[#0a0a0b] border border-gray-800/60 rounded-3xl p-6 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity duration-500">
                    <svg class="w-24 h-24 text-brand-400 rotate-12" fill="currentColor" viewBox="0 0 24 24"><path d="M4 6h16v2H4zm0 5h16v2H4zm0 5h16v2H4z"></path></svg>
                </div>
                <div class="flex justify-between items-start mb-4 relative z-10">
                    <div class="w-12 h-12 rounded-2xl bg-brand-500/10 border border-brand-500/20 flex items-center justify-center text-brand-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                    </div>
                </div>
                <div class="relative z-10">
                    <p class="text-4xl font-black text-white tracking-tight mb-1">{{ $stats['boletos_hoy'] }}</p>
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Boletos Hoy</p>
                </div>
            </div>

            {{-- Comprobantes Pendientes --}}
            <div class="bg-gradient-to-b from-[#121214] to-[#0a0a0b] border border-gray-800/60 rounded-3xl p-6 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity duration-500">
                    <svg class="w-24 h-24 text-yellow-400 rotate-12" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 15h2v2h-2v-2zm0-10h2v8h-2V7z"></path></svg>
                </div>
                <div class="flex justify-between items-start mb-4 relative z-10">
                    <div class="w-12 h-12 rounded-2xl bg-yellow-500/10 border border-yellow-500/20 flex items-center justify-center text-yellow-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div class="relative z-10">
                    <p class="text-4xl font-black text-yellow-400 tracking-tight mb-1">{{ $stats['pendientes'] }}</p>
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Por Validar</p>
                </div>
            </div>

            {{-- Frecuencias Hoy --}}
            <div class="bg-gradient-to-b from-[#121214] to-[#0a0a0b] border border-gray-800/60 rounded-3xl p-6 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity duration-500">
                    <svg class="w-24 h-24 text-purple-400 rotate-12" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 22h20L12 2zm0 4.5l6.5 13h-13L12 6.5zm-1 5v4h2v-4h-2zm0 5v2h2v-2h-2z"></path></svg>
                </div>
                <div class="flex justify-between items-start mb-4 relative z-10">
                    <div class="w-12 h-12 rounded-2xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-purple-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                </div>
                <div class="relative z-10">
                    <p class="text-4xl font-black text-white tracking-tight mb-1">{{ $stats['hojas_hoy'] }}</p>
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Hojas de Ruta</p>
                </div>
            </div>

            {{-- Ingresos --}}
            <div class="bg-gradient-to-b from-[#121214] to-[#0a0a0b] border border-gray-800/60 rounded-3xl p-6 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity duration-500">
                    <svg class="w-24 h-24 text-emerald-400 rotate-12" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2H9v-2h4v-2H9V9h2V7h2v2h2v2h-4v2h4v4h-2v2z"></path></svg>
                </div>
                <div class="flex justify-between items-start mb-4 relative z-10">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div class="relative z-10">
                    <p class="text-4xl font-black text-emerald-400 tracking-tight mb-1">${{ number_format($stats['ingresos_mes'], 2) }}</p>
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Ingresos Mensuales</p>
                </div>
            </div>
        </div>

        {{-- Accesos Rápidos (Grid estilo Básico a Moderno) --}}
        <div>
            <h2 class="text-xl font-bold text-white mb-5 flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                Accesos Rápidos
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4">
                @foreach([
                    ['Validar Pagos',    route('admin.pagos'),          '💳', 'from-orange-500/10 to-yellow-500/10', 'text-yellow-500', 'border-yellow-500/20 hover:border-yellow-500/50'],
                    ['Hojas de Ruta',   route('admin.hojas-ruta.index'),'📋', 'from-brand-500/10 to-indigo-500/10', 'text-brand-500', 'border-brand-500/20 hover:border-brand-500/50'],
                    ['Buses',           route('admin.buses.index'),      '🚌', 'from-gray-500/10 to-gray-600/10', 'text-gray-300', 'border-gray-700 hover:border-gray-500'],
                    ['Rutas',           route('admin.rutas.index'),      '🗺️', 'from-emerald-500/10 to-teal-500/10', 'text-emerald-400', 'border-emerald-500/20 hover:border-emerald-500/50'],
                    ['Frecuencias',     route('admin.frecuencias.index'),'⏰', 'from-purple-500/10 to-pink-500/10', 'text-purple-400', 'border-purple-500/20 hover:border-purple-500/50'],
                    ['Usuarios',        route('admin.usuarios.index'),   '👥', 'from-gray-500/10 to-gray-600/10', 'text-gray-300', 'border-gray-700 hover:border-gray-500'],
                    ['Cooperativa',     route('admin.cooperativa'),      '🏢', 'from-brand-500/10 to-brand-600/10', 'text-brand-400', 'border-brand-500/20 hover:border-brand-500/50'],
                    ['Categorías Bus',  route('admin.categorias-bus.index'), '🏷️', 'from-sky-500/10 to-blue-500/10', 'text-sky-400', 'border-sky-500/20 hover:border-sky-500/50'],
                    ['Paradas',         route('admin.paradas.index'),    '📍', 'from-orange-500/10 to-amber-500/10', 'text-orange-400', 'border-orange-500/20 hover:border-orange-500/50'],
                    ['Reportes',        route('admin.reportes.index'),   '📊', 'from-rose-500/10 to-pink-500/10', 'text-rose-400', 'border-rose-500/20 hover:border-rose-500/50'],
                ] as [$label, $url, $icon, $bgGradient, $textColor, $border])
                
                <a href="{{ $url }}"
                   class="relative overflow-hidden bg-gray-900/50 border {{ $border }} rounded-2xl p-5 flex flex-col items-center justify-center gap-3 transition-all duration-300 hover:shadow-lg hover:-translate-y-1 group backdrop-blur-sm">
                    <div class="absolute inset-0 bg-gradient-to-br {{ $bgGradient }} opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="text-4xl filter drop-shadow-md transform group-hover:scale-110 transition-transform duration-300 relative z-10">{{ $icon }}</div>
                    <p class="text-xs font-bold {{ $textColor }} text-center relative z-10">{{ $label }}</p>
                </a>
                @endforeach
            </div>
        </div>

        {{-- Hojas de ruta de hoy - Tabla --}}
        <div class="bg-[#121214]/80 backdrop-blur-xl border border-gray-800/60 rounded-3xl overflow-hidden shadow-2xl">
            <div class="px-8 py-6 border-b border-gray-800/60 flex justify-between items-center bg-[#0a0a0b]/50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-brand-500/10 flex items-center justify-center text-brand-500 border border-brand-500/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <h2 class="font-bold text-xl text-white tracking-tight">Frecuencias del Día</h2>
                        <p class="text-xs text-gray-500 font-medium">Monitorea el estado actual de los viajes</p>
                    </div>
                </div>
                <a href="{{ route('admin.hojas-ruta.index') }}" class="text-sm font-semibold text-brand-400 hover:text-brand-300 flex items-center gap-1 transition-colors">
                    Ver historial
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-[#0a0a0b]/80 text-xs text-gray-400 uppercase tracking-wider font-semibold border-b border-gray-800/60">
                        <tr>
                            <th class="px-8 py-5">Ruta</th>
                            <th class="px-8 py-5">Bus Designado</th>
                            <th class="px-8 py-5 text-center">Salida</th>
                            <th class="px-8 py-5 text-center">Ocupación</th>
                            <th class="px-8 py-5 text-center">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800/60">
                        @forelse($hojasHoy as $hoja)
                        <tr class="hover:bg-gray-800/30 transition-colors group">
                            <td class="px-8 py-5">
                                <div class="font-bold text-white text-base flex items-center gap-2">
                                    {{ $hoja->frecuencia->ruta->origen }}
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                    {{ $hoja->frecuencia->ruta->destino }}
                                </div>
                                <div class="text-xs text-gray-500 mt-1">Ref: {{ $hoja->frecuencia->resolucion_ant }}</div>
                            </td>
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-gray-800 border border-gray-700 flex items-center justify-center text-xl">
                                        🚌
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-200">#{{ $hoja->bus->numero_disco }}</div>
                                        <div class="text-xs text-gray-500">{{ $hoja->bus->placa }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5 text-center">
                                <span class="px-3 py-1.5 rounded-lg bg-gray-900 border border-gray-800 text-gray-300 font-bold font-mono text-sm">
                                    {{ \Carbon\Carbon::parse($hoja->frecuencia->hora_salida)->format('H:i') }}
                                </span>
                            </td>
                            <td class="px-8 py-5 text-center">
                                <div class="flex flex-col items-center">
                                    <span class="font-black text-white text-lg">{{ $hoja->boletos->count() }}</span>
                                    <span class="text-[10px] text-gray-500 uppercase font-bold tracking-wider">Pasajeros</span>
                                </div>
                            </td>
                            <td class="px-8 py-5 text-center">
                                <span class="px-4 py-1.5 rounded-full text-xs font-bold shadow-sm inline-flex items-center gap-1.5
                                    {{ $hoja->estado === 'Pendiente'   ? 'bg-gray-800 text-gray-300 border border-gray-700' : '' }}
                                    {{ $hoja->estado === 'En Ruta'     ? 'bg-brand-900/40 text-brand-400 border border-brand-800' : '' }}
                                    {{ $hoja->estado === 'Completada'  ? 'bg-emerald-900/40 text-emerald-400 border border-emerald-800' : '' }}
                                    {{ $hoja->estado === 'Cancelada'   ? 'bg-red-900/40 text-red-400 border border-red-800' : '' }}">
                                    @if($hoja->estado === 'En Ruta')
                                        <span class="w-1.5 h-1.5 rounded-full bg-brand-500 animate-pulse"></span>
                                    @elseif($hoja->estado === 'Completada')
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    @endif
                                    {{ $hoja->estado }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-8 py-16 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-20 h-20 bg-gray-900 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-10 h-10 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 12H4M8 16l-4-4 4-4"></path></svg>
                                    </div>
                                    <p class="text-lg font-bold text-gray-400">No hay hojas de ruta</p>
                                    <p class="text-sm text-gray-600 mt-1">No se han programado frecuencias para el día de hoy.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.admin>