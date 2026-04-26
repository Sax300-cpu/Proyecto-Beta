<div>
    <div class="p-6 space-y-4">
        <div class="flex items-center gap-2">
            <select wire:model="nuevaParadaId" class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:ring-brand-500 focus:border-brand-500">
                <option value="">Seleccione una parada</option>
                @foreach($paradasDisponibles as $parada)
                    <option value="{{ $parada->id }}">{{ $parada->nombre }} ({{ $parada->ciudad }})</option>
                @endforeach
            </select>
            <button wire:click="addParada" class="px-4 py-2.5 bg-brand-600 hover:bg-brand-500 text-white font-bold rounded-xl transition">
                Añadir
            </button>
        </div>

        @if (count($paradasSeleccionadas) > 0)
            <div class="space-y-3">
                <h4 class="text-white font-bold mt-4">Paradas en Ruta:</h4>
                @foreach($paradasSeleccionadas as $index => $parada)
                    <div class="flex items-center gap-2 bg-gray-800 border border-gray-700 rounded-xl p-3">
                        <span class="text-gray-400 font-semibold">{{ $index + 1 }}.</span>
                        <p class="text-white flex-1">{{ $parada["nombre"] }}</p>
                        
                        <div>
                            <label class="block text-xs text-gray-400">Tiempo Llegada (HH:MM)</label>
                            <input type="time" wire:model.defer="paradasSeleccionadas.{{ $index }}.tiempo_estimado_llegada"
                                class="w-32 bg-gray-900 border border-gray-700 rounded-lg px-2 py-1 text-white text-xs">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-400">Precio Desde Origen ($)</label>
                            <input type="number" step="0.01" wire:model.defer="paradasSeleccionadas.{{ $index }}.precio_desde_origen"
                                class="w-24 bg-gray-900 border border-gray-700 rounded-lg px-2 py-1 text-white text-xs">
                        </div>

                        <button wire:click="removeParada({{ $parada["id"] }})" class="text-red-400 hover:text-red-300 ml-2">✕</button>
                    </div>
                @endforeach
            </div>

            <div class="pt-4 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch(\'close-modal\', \'gestionar-paradas-{{ $ruta->id }}\' )" class="px-5 py-2.5 bg-gray-800 hover:bg-gray-700 text-white rounded-xl transition">
                    Cancelar
                </button>
                <button wire:click="saveParadas" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-500 text-white font-bold rounded-xl transition">
                    Guardar Paradas
                </button>
            </div>
        @else
            <p class="text-gray-500 text-center py-4">Aún no se han añadido paradas a esta ruta.</p>
        @endif
    </div>
</div>