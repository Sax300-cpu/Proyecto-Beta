<x-layouts.admin>
    <x-slot:title>Gestión de Hojas de Ruta</x-slot:title>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex justify-between items-end mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-white">Hojas de Ruta</h1>
                <p class="text-gray-400 mt-1">Programa y administra las salidas de los buses</p>
            </div>
            
            <button x-data x-on:click="$dispatch('open-modal', 'nueva-hoja')" 
                class="px-5 py-2.5 bg-brand-600 hover:bg-brand-500 text-white font-bold rounded-xl transition">
                + Nueva Salida
            </button>
        </div>

        @if(session('success'))
        <div class="bg-green-900/50 border border-green-700 text-green-300 px-4 py-3 rounded-xl mb-6">
            {{ session('success') }}
        </div>
        @endif
        
        @if($errors->any())
        <div class="bg-red-900/50 border border-red-700 text-red-300 px-4 py-3 rounded-xl mb-6">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-400 uppercase bg-gray-900/50 border-b border-gray-800">
                        <tr>
                            <th class="px-6 py-4">Fecha / Hora</th>
                            <th class="px-6 py-4">Ruta</th>
                            <th class="px-6 py-4">Bus (Placa)</th>
                            <th class="px-6 py-4">Chofer</th>
                            <th class="px-6 py-4">Estado</th>
                            <th class="px-6 py-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        @forelse($hojas as $hoja)
                        <tr class="hover:bg-gray-800/50 transition">
                            <td class="px-6 py-4">
                                <div class="font-medium text-white">{{ $hoja->fecha->format('d/m/Y') }}</div>
                                <div class="text-gray-500">{{ \Carbon\Carbon::parse($hoja->frecuencia->hora_salida)->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                {{ $hoja->frecuencia->ruta->origen }} → {{ $hoja->frecuencia->ruta->destino }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-white">{{ $hoja->bus->numero_disco }}</div>
                                <div class="text-xs text-gray-500">{{ $hoja->bus->placa }}</div>
                            </td>
                            <td class="px-6 py-4">{{ $hoja->chofer?->name ?? 'Sin asignar' }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-bold
                                    {{ $hoja->estado === 'Pendiente' ? 'bg-gray-800 text-gray-300' : '' }}
                                    {{ $hoja->estado === 'En Ruta' ? 'bg-brand-900 text-brand-300' : '' }}
                                    {{ $hoja->estado === 'Completada' ? 'bg-green-900 text-green-300' : '' }}
                                    {{ $hoja->estado === 'Cancelada' ? 'bg-red-900 text-red-300' : '' }}">
                                    {{ $hoja->estado }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <form method="POST" action="{{ route('admin.hojas-ruta.estado', $hoja->id) }}" class="flex items-center gap-2">
                                    @csrf @method('PATCH')
                                    <select name="estado" class="bg-gray-800 border border-gray-700 text-white text-xs rounded-lg px-2 py-1 focus:ring-brand-500 focus:border-brand-500">
                                        <option value="Pendiente" {{ $hoja->estado == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                                        <option value="En Ruta" {{ $hoja->estado == 'En Ruta' ? 'selected' : '' }}>En Ruta</option>
                                        <option value="Completada" {{ $hoja->estado == 'Completada' ? 'selected' : '' }}>Completada</option>
                                        <option value="Cancelada" {{ $hoja->estado == 'Cancelada' ? 'selected' : '' }}>Cancelada</option>
                                    </select>
                                    <button type="submit" class="text-brand-400 hover:text-brand-300 text-xs font-semibold">Actualizar</button>
                                </form>
                                <button x-data x-on:click="$dispatch('open-modal', 'cambiar-bus-{{ $hoja->id }}')" class="text-yellow-400 hover:text-yellow-300 text-xs font-semibold mt-1">Cambiar Bus</button>
                            </td>
                        </tr>

                        {{-- Modal Cambiar Bus --}}
                        <div x-data="{ show: false }" 
                             x-on:open-modal.window="if ($event.detail === 'cambiar-bus-{{ $hoja->id }}') show = true"
                             x-show="show" 
                             class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 p-4" x-cloak>
                            
                            <div class="bg-gray-900 border border-gray-700 rounded-2xl w-full max-w-lg overflow-hidden text-left" @click.away="show = false">
                                <div class="px-6 py-4 border-b border-gray-800 flex justify-between items-center">
                                    <h3 class="font-bold text-lg text-white">Cambiar Bus para Viaje #{{ $hoja->id }}</h3>
                                    <button @click="show = false" class="text-gray-500 hover:text-white">✕</button>
                                </div>
                                
                                <form method="POST" action="{{ route('admin.hojas-ruta.cambiar-bus', $hoja->id) }}" class="p-6 space-y-4">
                                    @csrf @method('PATCH')
                                    
                                    <div>
                                        <p class="text-sm text-gray-400 mb-4">Selecciona un bus de reemplazo. Esta acción notificará a los pasajeros si es necesario.</p>
                                        <label class="block text-sm text-gray-400 mb-1">Nuevo Bus</label>
                                        <select name="bus_id" required class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:ring-brand-500 focus:border-brand-500">
                                            @foreach($buses as $bus)
                                                <option value="{{ $bus->id }}" @selected($bus->id == $hoja->bus_id)>
                                                    Disco {{ $bus->numero_disco }} - {{ $bus->placa }} ({{ $bus->categoria->nombre }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="pt-4 flex justify-end gap-3">
                                        <button type="button" @click="show = false" class="px-5 py-2.5 bg-gray-800 hover:bg-gray-700 text-white rounded-xl transition">
                                            Cancelar
                                        </button>
                                        <button type="submit" class="px-5 py-2.5 bg-yellow-600 hover:bg-yellow-500 text-white font-bold rounded-xl transition">
                                            Confirmar Cambio
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">No hay hojas de ruta registradas.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="mt-6">
            {{ $hojas->links() }}
        </div>
    </div>

    {{-- Modal Crear Hoja de Ruta --}}
    <div x-data="{ show: false }" 
         x-on:open-modal.window="if ($event.detail === 'nueva-hoja') show = true"
         x-show="show" 
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 p-4" x-cloak>
        
        <div class="bg-gray-900 border border-gray-700 rounded-2xl w-full max-w-lg overflow-hidden" @click.away="show = false">
            <div class="px-6 py-4 border-b border-gray-800 flex justify-between items-center">
                <h3 class="font-bold text-lg text-white">Nueva Hoja de Ruta</h3>
                <button @click="show = false" class="text-gray-500 hover:text-white">✕</button>
            </div>
            
            <form method="POST" action="{{ route('admin.hojas-ruta.store') }}" class="p-6 space-y-4">
                @csrf
                
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Fecha de Salida</label>
                    <input type="date" name="fecha" value="{{ old('fecha', today()->format('Y-m-d')) }}" required min="{{ today()->format('Y-m-d') }}"
                        class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>

                <div>
                    <label class="block text-sm text-gray-400 mb-1">Frecuencia</label>
                    <select name="frecuencia_id" required class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:ring-2 focus:ring-brand-500">
                        <option value="">Seleccione una frecuencia...</option>
                        @foreach($frecuencias as $frec)
                            <option value="{{ $frec->id }}" {{ old('frecuencia_id') == $frec->id ? 'selected' : '' }}>
                                {{ $frec->ruta->origen }} → {{ $frec->ruta->destino }} ({{ \Carbon\Carbon::parse($frec->hora_salida)->format('H:i') }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm text-gray-400 mb-1">Bus</label>
                    <select name="bus_id" required class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:ring-2 focus:ring-brand-500">
                        <option value="">Seleccione un bus...</option>
                        @foreach($buses as $bus)
                            <option value="{{ $bus->id }}" {{ old('bus_id') == $bus->id ? 'selected' : '' }}>
                                Disco {{ $bus->numero_disco }} - {{ $bus->placa }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm text-gray-400 mb-1">Chofer (Opcional)</label>
                    <select name="chofer_id" class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:ring-2 focus:ring-brand-500">
                        <option value="">Sin asignar</option>
                        @foreach($choferes as $chofer)
                            <option value="{{ $chofer->id }}" {{ old('chofer_id') == $chofer->id ? 'selected' : '' }}>
                                {{ $chofer->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="pt-4 flex justify-end gap-3">
                    <button type="button" @click="show = false" class="px-5 py-2.5 bg-gray-800 hover:bg-gray-700 text-white rounded-xl transition">
                        Cancelar
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-500 text-white font-bold rounded-xl transition">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>

</x-layouts.admin>

