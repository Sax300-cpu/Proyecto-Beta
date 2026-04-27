<div class="p-4">
    <h1 class="text-xl font-bold text-white mb-1">Panel del Chofer</h1>
    <p class="text-gray-400 text-sm mb-6">Escanea QR o vende boletos en ruta</p>

    <div class="bg-gray-800 rounded-2xl p-4 mb-5 border border-gray-700">
        <h2 class="font-semibold text-white mb-3 flex items-center gap-2">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
            Escaner con Camara
        </h2>
        <div id="qr-reader" wire:ignore class="rounded-xl overflow-hidden bg-black"></div>
        <p class="text-xs text-gray-500 mt-2">Permite acceso a camara y apunta al QR del boleto.</p>
    </div>

    {{-- Scanner QR --}}
    <div class="bg-gray-800 rounded-2xl p-5 mb-5 border border-gray-700">
        <h2 class="font-semibold text-white mb-3 flex items-center gap-2">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            Validar Boleto
        </h2>

        <div class="flex gap-2">
            <input wire:model="qrInput" type="text"
                placeholder="Código QR del boleto..."
                class="flex-1 bg-gray-900 border border-gray-600 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-yellow-500 text-sm"
                wire:keydown.enter="escanear">
            <button wire:click="escanear"
                class="px-4 py-3 bg-yellow-500 hover:bg-yellow-400 rounded-xl font-semibold text-black transition text-sm">
                Validar
            </button>
        </div>
        <p class="text-xs text-gray-500 mt-2 flex items-center gap-1">
            <svg class="w-4 h-4 text-yellow-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
            Cámara QR: usa la app de cámara del celular y copia el texto aquí, o integra un lector de hardware.
        </p>
    </div>

    {{-- Resultado de escaneo --}}
    @if($mensaje)
    <div class="rounded-2xl p-4 mb-5 border
        {{ $tipoMensaje === 'success' ? 'bg-green-900/50 border-green-700 text-green-300' : '' }}
        {{ $tipoMensaje === 'error'   ? 'bg-red-900/50 border-red-700 text-red-300' : '' }}
        {{ $tipoMensaje === 'warning' ? 'bg-yellow-900/50 border-yellow-700 text-yellow-300' : '' }}">
        <p class="font-semibold text-base">{{ $mensaje }}</p>
    </div>
    @endif

    @if($boletoEncontrado)
    <div class="bg-gray-800 rounded-2xl p-5 mb-5 border border-gray-700">
        <h3 class="font-bold text-white mb-3">Información del Boleto</h3>
        <div class="space-y-2 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-400">Pasajero</span>
                <span class="font-semibold">{{ $boletoEncontrado->nombre_pasajero }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-400">Cédula</span>
                <span>{{ $boletoEncontrado->cedula_pasajero }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-400">Tipo</span>
                <span class="{{ $boletoEncontrado->tipo_pasajero !== 'Normal' ? 'text-yellow-400 font-semibold' : '' }}">
                    {{ $boletoEncontrado->tipo_pasajero }}
                </span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-400">Ruta</span>
                <span>{{ $boletoEncontrado->origen_abordaje }} → {{ $boletoEncontrado->destino_desembarque }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-400">Asiento</span>
                <span class="font-bold text-yellow-400">{{ $boletoEncontrado->asiento->numero }} ({{ $boletoEncontrado->asiento->tipo }})</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-400">Estado</span>
                <span class="px-2 py-0.5 rounded-full text-xs font-bold
                    {{ $boletoEncontrado->estado === 'Validado' ? 'bg-green-800 text-green-300' : '' }}
                    {{ $boletoEncontrado->estado === 'Abordado' ? 'bg-yellow-900/60 text-yellow-300' : '' }}
                    {{ $boletoEncontrado->estado === 'Rechazado' ? 'bg-red-800 text-red-300' : '' }}">
                    {{ $boletoEncontrado->estado }}
                </span>
            </div>
        </div>

        @if($boletoEncontrado->estado === 'Validado')
        <div class="flex gap-3 mt-4">
            <button wire:click="marcarAbordado"
                class="flex-1 flex items-center justify-center gap-2 py-3 bg-green-600 hover:bg-green-500 rounded-xl font-bold text-white text-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Marcar Abordado
            </button>
            <button wire:click="marcarNoShow"
                class="flex-1 flex items-center justify-center gap-2 py-3 bg-gray-700 hover:bg-gray-600 rounded-xl font-medium text-gray-200 text-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                No Show
            </button>
        </div>
        @endif
    </div>
    @endif

    {{-- Venta en ruta --}}
    @if($hojaRutaActiva)
    <button wire:click="abrirModalVenta"
        class="w-full py-4 bg-yellow-500 hover:bg-yellow-400 rounded-2xl font-bold text-black text-base transition flex items-center justify-center gap-2 mb-4">
        <svg class="w-6 h-6 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
        Vender Boleto en Ruta
    </button>
    @else
    <div class="bg-gray-800/50 border border-gray-700 rounded-2xl p-4 text-center text-gray-500 text-sm">
        No tienes hoja de ruta activa para hoy.
    </div>
    @endif

    {{-- Modal Venta en Ruta --}}
    @if($modalVentaAbierto)
    <div class="fixed inset-0 bg-black/80 z-50 flex items-end sm:items-center justify-center p-4">
        <div class="bg-gray-900 border border-gray-700 rounded-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
            <div class="p-5">
                <div class="flex justify-between items-center mb-5">
                    <h2 class="font-bold text-lg">Venta en Ruta</h2>
                    <button wire:click="cerrarModalVenta" class="text-gray-500 hover:text-white">✕</button>
                </div>

                @if($errorVenta)
                <div class="bg-red-900/50 border border-red-700 text-red-300 px-4 py-3 rounded-xl mb-4 text-sm">
                    {{ $errorVenta }}
                </div>
                @endif

                <div class="space-y-3">
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Nombre del pasajero *</label>
                        <input wire:model="nombre_pasajero" type="text"
                            class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Cédula (10 dígitos) *</label>
                        <input wire:model="cedula_pasajero" type="text" maxlength="10"
                            class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Fecha de nacimiento *</label>
                        <input wire:model="fecha_nacimiento_pasajero" type="date"
                            max="{{ now()->subYears(13)->format('Y-m-d') }}"
                            class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Tipo pasajero</label>
                        <select wire:model="tipo_pasajero"
                            class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:ring-2 focus:ring-yellow-500">
                            <option value="Normal">Normal</option>
                            <option value="Niño">Niño (50% desc.)</option>
                            <option value="Tercera Edad">Tercera Edad (50% desc.)</option>
                            <option value="Discapacitado">Discapacitado (50% desc.)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Asiento disponible *</label>
                        <select wire:model="asiento_id"
                            class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:ring-2 focus:ring-yellow-500">
                            <option value="">Seleccionar...</option>
                            @foreach($asientosDisponibles as $asiento)
                            <option value="{{ $asiento->id }}">{{ $asiento->numero }} — {{ $asiento->tipo }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-sm text-gray-400 mb-1">Origen abordaje</label>
                            <input wire:model="origen_abordaje" type="text"
                                class="w-full bg-gray-800 border border-gray-700 rounded-xl px-3 py-2.5 text-white text-sm focus:outline-none focus:ring-2 focus:ring-yellow-500">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-1">Destino</label>
                            <input wire:model="destino_desembarque" type="text"
                                class="w-full bg-gray-800 border border-gray-700 rounded-xl px-3 py-2.5 text-white text-sm focus:outline-none focus:ring-2 focus:ring-yellow-500">
                        </div>
                    </div>

                    <button wire:click="venderEnRuta"
                        class="w-full py-3 bg-yellow-500 hover:bg-yellow-400 rounded-xl font-bold text-black transition mt-2">
                        Registrar Venta
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
    document.addEventListener('livewire:init', () => {
        if (window.__pasajesQrReady) {
            return;
        }

        window.__pasajesQrReady = true;
        const readerElement = document.getElementById('qr-reader');
        if (!readerElement || typeof Html5Qrcode === 'undefined') {
            return;
        }

        const scanner = new Html5Qrcode('qr-reader');
        const config = { fps: 10, qrbox: { width: 230, height: 230 } };

        scanner.start(
            { facingMode: 'environment' },
            config,
            (decodedText) => {
                const component = window.Livewire?.find('{{ $this->getId() }}');
                if (component) {
                    component.call('scanFromCamera', decodedText);
                }
            },
            () => {}
        ).catch(() => {
            // Si no hay permisos de camara, queda disponible el ingreso manual.
        });
    });
</script>
