<x-layouts.admin>
    <x-slot:title>Gestión de Frecuencias</x-slot:title>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex justify-between items-end mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-white">Horarios y Frecuencias</h1>
                <p class="text-gray-400 mt-1">Asigna horarios de salida a las rutas aprobadas por la ANT</p>
            </div>
            
            <button x-data x-on:click="$dispatch('open-modal', 'nueva-frecuencia')" 
                class="px-5 py-2.5 bg-brand-600 hover:bg-brand-500 text-white font-bold rounded-xl transition">
                + Nueva Frecuencia
            </button>
        </div>

        <div class="bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-400 uppercase bg-gray-900/50 border-b border-gray-800">
                        <tr>
                            <th class="px-6 py-4">Ruta</th>
                            <th class="px-6 py-4">Hora de Salida</th>
                            <th class="px-6 py-4">Resolución ANT</th>
                            <th class="px-6 py-4">Tipo</th>
                            <th class="px-6 py-4 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        @foreach($frecuencias as $frec)
                        <tr class="hover:bg-gray-800/50 transition">
                            <td class="px-6 py-4 font-bold text-white text-lg">
                                {{ $frec->ruta->origen }} → {{ $frec->ruta->destino }}
                            </td>
                            <td class="px-6 py-4 font-bold text-brand-400 text-xl">
                                {{ \Carbon\Carbon::parse($frec->hora_salida)->format('H:i') }}
                            </td>
                            <td class="px-6 py-4 text-gray-400 font-mono text-xs">
                                {{ $frec->resolucion_ant ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4">
                                @if($frec->es_directa)
                                    <span class="px-3 py-1 bg-green-900/50 border border-green-800 rounded-full text-xs font-bold text-green-300">Directa</span>
                                @else
                                    <span class="px-3 py-1 bg-brand-900/50 border border-brand-800 rounded-full text-xs font-bold text-brand-300">Con Paradas</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <form method="POST" action="{{ route('admin.frecuencias.destroy', $frec->id) }}" onsubmit="return confirm('¿Seguro que deseas eliminar esta frecuencia?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-300 text-xs font-semibold">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="mt-6">
            {{ $frecuencias->links() }}
        </div>
    </div>

    {{-- Modal Crear Frecuencia --}}
    <div x-data="{ show: false }" 
         x-on:open-modal.window="if ($event.detail === 'nueva-frecuencia') show = true"
         x-show="show" 
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 p-4" x-cloak>
        
        <div class="bg-gray-900 border border-gray-700 rounded-2xl w-full max-w-lg overflow-hidden" @click.away="show = false">
            <div class="px-6 py-4 border-b border-gray-800 flex justify-between items-center">
                <h3 class="font-bold text-lg text-white">Registrar Frecuencia</h3>
                <button @click="show = false" class="text-gray-500 hover:text-white">✕</button>
            </div>
            
            <form method="POST" action="{{ route('admin.frecuencias.store') }}" class="p-6 space-y-4">
                @csrf
                
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Ruta Asignada</label>
                    <select name="ruta_id" required class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:ring-brand-500 focus:border-brand-500">
                        @foreach($rutas as $ruta)
                            <option value="{{ $ruta->id }}">{{ $ruta->origen }} → {{ $ruta->destino }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Hora de Salida</label>
                        <input type="time" name="hora_salida" required 
                            class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:ring-brand-500 focus:border-brand-500">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Resolución ANT</label>
                        <input type="text" name="resolucion_ant" value="{{ $nextResolucion }}" required
                            class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:ring-brand-500 focus:border-brand-500">
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <input type="checkbox" name="es_directa" id="es_directa" value="1" class="w-5 h-5 rounded border-gray-700 bg-gray-800 text-brand-600 focus:ring-brand-600">
                    <label for="es_directa" class="text-white text-sm">Es ruta directa (Sin paradas intermedias)</label>
                </div>

                <div class="pt-4 flex justify-end gap-3">
                    <button type="button" @click="show = false" class="px-5 py-2.5 bg-gray-800 hover:bg-gray-700 text-white rounded-xl transition">
                        Cancelar
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-500 text-white font-bold rounded-xl transition">
                        Guardar Frecuencia
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>

