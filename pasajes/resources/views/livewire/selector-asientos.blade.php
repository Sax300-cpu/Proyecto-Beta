<div class="max-w-5xl mx-auto px-4 py-8">

    <div class="mb-6">
        <h1 class="text-3xl font-extrabold text-white">Selecciona tu asiento</h1>
        <p class="text-gray-400 mt-1">
            {{ $hojaRuta->frecuencia->ruta->origen }} → {{ $hojaRuta->frecuencia->ruta->destino }} •
            {{ $hojaRuta->fecha->format('d/m/Y') }} •
            {{ \Carbon\Carbon::parse($hojaRuta->frecuencia->hora_salida)->format('H:i') }}
        </p>
    </div>

    {{-- Error global --}}
    @if($error)
    <div class="bg-red-900/50 border border-red-700 text-red-300 px-4 py-3 rounded-xl mb-6 flex items-center gap-2">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        {{ $error }}
    </div>
    @endif

    {{-- Boleto generado --}}
    @if($boletoGeneradoId)
    <div class="bg-green-900/50 border border-green-700 text-green-300 px-6 py-5 rounded-xl mb-6">
        <p class="font-bold text-green-200 text-lg">✅ ¡Boleto generado exitosamente!</p>
        <p class="mt-1">Tu boleto ha sido creado. Debes subir el comprobante de pago para activarlo.</p>
        <a href="{{ route('boleto.ver', $boletoGeneradoId) }}"
           class="mt-3 inline-block px-5 py-2.5 bg-green-600 hover:bg-green-500 rounded-xl font-semibold transition">
            Ver mi boleto →
        </a>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        {{-- Grilla de Asientos --}}
        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6">
            <div class="flex items-center justify-between mb-5">
                <h2 class="font-bold text-lg">Mapa de Asientos</h2>
                <button wire:click="actualizarOcupados"
                    class="text-xs text-brand-400 hover:text-brand-300 flex items-center gap-1 transition">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Actualizar
                </button>
            </div>

            {{-- Leyenda --}}
            <div class="flex flex-wrap gap-4 mb-5 text-xs text-gray-400">
                <div class="flex items-center gap-1.5">
                    <div class="w-4 h-4 bg-brand-600 rounded-sm"></div> Disponible
                </div>
                <div class="flex items-center gap-1.5">
                    <div class="w-4 h-4 bg-green-500 rounded-sm"></div> Tu selección
                </div>
                <div class="flex items-center gap-1.5">
                    <div class="w-4 h-4 bg-gray-700 rounded-sm border border-gray-600"></div> Ocupado
                </div>
            </div>

            {{-- Grid de asientos --}}
            <div class="grid grid-cols-5 gap-y-4 gap-x-2 max-w-xs mx-auto bg-gray-800/30 p-6 rounded-3xl border border-gray-800">
                
                {{-- Frente del bus visual (Volante) --}}
                <div class="col-span-5 flex justify-between items-center mb-4 border-b border-gray-700 pb-4">
                    <div class="w-10 h-10"></div> {{-- Espacio vacío lado puerta --}}
                    <div class="text-xs text-gray-500 font-semibold tracking-widest uppercase">Frente</div>
                    <div class="w-10 h-10 bg-gray-800 rounded-full border border-gray-700 flex items-center justify-center" title="Volante">
                        <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v9m0 0l-6 6m6-6l6 6"/></svg>
                    </div>
                </div>

                @foreach($asientos->groupBy('piso') as $piso => $asientosPiso)
                    @if($loop->index > 0)
                    <div class="col-span-5 border-t border-dashed border-gray-700 my-4 py-2 text-center text-xs text-gray-500 font-bold uppercase tracking-widest">
                        Piso 2
                    </div>
                    @endif
                    @foreach($asientosPiso->chunk(4) as $fila)
                        @foreach($fila as $index => $asiento)
                            @php
                                $ocupado   = in_array($asiento->id, $asientosOcupados);
                                $seleccionado = $asientoSeleccionado === $asiento->id;
                            @endphp

                            {{-- Pasillo central --}}
                            @if($index === 2)
                            <div class="w-10 h-12 flex items-center justify-center">
                                <div class="h-full w-px bg-gray-800/50"></div>
                            </div>
                            @endif

                            <button
                                wire:click="seleccionarAsiento({{ $asiento->id }})"
                                @disabled($ocupado)
                                title="Asiento {{ $asiento->numero }} ({{ $asiento->tipo }})"
                                class="seat-available w-10 h-12 relative flex flex-col items-center justify-end pb-1 border-b-4 rounded-t-xl rounded-b-md transition-all shadow-md group
                                    {{ $ocupado
                                        ? 'bg-red-950/80 border-red-900 text-red-500 cursor-not-allowed opacity-70'
                                        : ($seleccionado
                                            ? 'bg-brand-500 border-brand-700 text-white ring-2 ring-brand-400 ring-offset-2 ring-offset-gray-900 transform -translate-y-1'
                                            : 'bg-emerald-900/60 border-emerald-800 text-emerald-300 hover:bg-emerald-600 hover:border-emerald-700 hover:-translate-y-1 cursor-pointer') }}">
                                
                                {{-- Cabecera del asiento --}}
                                <div class="absolute top-1 w-6 h-2 rounded-full opacity-50 {{ $ocupado ? 'bg-red-800' : ($seleccionado ? 'bg-white' : 'bg-emerald-700') }}"></div>
                                
                                <span class="font-bold text-sm">{{ $asiento->numero }}</span>
                            </button>
                        @endforeach
                    @endforeach
                @endforeach
            </div>
        </div>

        {{-- Formulario del Pasajero --}}
        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6">
            <h2 class="font-bold text-lg mb-5">Datos del Pasajero</h2>

            @if($asientoSeleccionado)
            @php $asientoObj = $asientos->firstWhere('id', $asientoSeleccionado); @endphp
            <div class="bg-brand-900/40 border border-brand-800 rounded-xl px-4 py-2 mb-5 text-sm text-brand-200">
                ✅ Asiento {{ $asientoObj?->numero }} ({{ $asientoObj?->tipo }}) seleccionado
            </div>
            @endif

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Nombre completo *</label>
                    <input wire:model="nombre_pasajero" type="text"
                        class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-brand-500">
                    @error('nombre_pasajero') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Cédula (10 dígitos) *</label>
                    <input wire:model="cedula_pasajero" type="text" maxlength="10"
                        class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-brand-500">
                    @error('cedula_pasajero') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Fecha de nacimiento *</label>
                    <input wire:model="fecha_nacimiento_pasajero" type="date"
                        max="{{ now()->subYears(13)->format('Y-m-d') }}"
                        class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:ring-2 focus:ring-brand-500">
                    @error('fecha_nacimiento_pasajero') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    <p class="text-xs text-gray-500 mt-1">Debe ser mayor de 13 años</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Tipo de pasajero</label>
                    <select wire:model="tipo_pasajero"
                        class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:ring-2 focus:ring-brand-500">
                        <option value="Normal">Normal</option>
                        <option value="Niño">Niño (50% descuento)</option>
                        <option value="Tercera Edad">Tercera Edad (50% descuento)</option>
                        <option value="Discapacitado">Discapacitado (50% descuento)</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Origen de abordaje</label>
                        <input wire:model="origen_abordaje" type="text"
                            class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Destino</label>
                        <input wire:model="destino_desembarque" type="text"
                            class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                </div>

                {{-- Confirmación --}}
                @if($confirmando)
                <div class="bg-yellow-900/40 border border-yellow-700 rounded-xl p-4 mt-2">
                    <p class="font-semibold text-yellow-200 mb-2">¿Confirmar la compra?</p>
                    <p class="text-sm text-yellow-300">Asiento: <strong>{{ $asientoObj?->numero ?? '-' }}</strong> • Pasajero: <strong>{{ $nombre_pasajero }}</strong></p>
                    <div class="flex gap-3 mt-3">
                        <button wire:click="comprar"
                            class="flex-1 py-2.5 bg-green-600 hover:bg-green-500 rounded-xl font-bold text-white transition">
                            ✅ Confirmar
                        </button>
                        <button wire:click="cancelarConfirmacion"
                            class="flex-1 py-2.5 bg-gray-700 hover:bg-gray-600 rounded-xl font-medium text-gray-200 transition">
                            Cancelar
                        </button>
                    </div>
                </div>
                @else
                <button wire:click="confirmar"
                    wire:loading.attr="disabled"
                    class="w-full py-3 bg-brand-600 hover:bg-brand-500 text-white font-bold rounded-xl transition-all duration-200 transform hover:scale-[1.02] shadow-lg hover:shadow-brand-500/25 mt-2 flex items-center justify-center gap-2 disabled:opacity-50 disabled:pointer-events-none"
                    {{ !$asientoSeleccionado ? 'disabled' : '' }}>
                    
                    <span wire:loading.remove wire:target="confirmar">
                        {{ !$asientoSeleccionado ? 'Selecciona un asiento primero' : 'Comprar Boleto' }}
                    </span>
                    
                    <span wire:loading wire:target="confirmar" class="flex items-center gap-2">
                        <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Procesando...
                    </span>
                </button>
                @endif
            </div>
        </div>
    </div>
</div>
