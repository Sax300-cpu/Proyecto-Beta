<div class="max-w-7xl mx-auto px-4 py-8">

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-white">Validación de Pagos</h1>
            <p class="text-gray-400 mt-1">Aprueba o rechaza comprobantes de transferencia</p>
        </div>
        <div class="flex gap-2">
            @foreach(['Pendiente', 'Aprobado', 'Rechazado'] as $estado)
            <button wire:click="$set('filtroEstado', '{{ $estado }}')"
                class="px-4 py-2 rounded-xl text-sm font-medium transition
                    {{ $filtroEstado === $estado
                        ? 'bg-brand-600 text-white'
                        : 'bg-gray-800 text-gray-400 hover:bg-gray-700' }}">
                {{ $estado }}
            </button>
            @endforeach
        </div>
    </div>

    {{-- Mensajes Flash --}}
    @if(session('success'))
    <div class="bg-green-900/50 border border-green-700 text-green-300 px-4 py-3 rounded-xl mb-6">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="bg-red-900/50 border border-red-700 text-red-300 px-4 py-3 rounded-xl mb-6">{{ session('error') }}</div>
    @endif

    {{-- Tabla de comprobantes --}}
    @if($comprobantes->isEmpty())
    <div class="text-center py-16 text-gray-500">
        <div class="text-5xl mb-4">📋</div>
        <p>No hay comprobantes con estado "{{ $filtroEstado }}".</p>
    </div>
    @else
    <div class="space-y-4">
        @foreach($comprobantes as $comp)
        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-5 flex flex-col sm:flex-row gap-5">

            {{-- Imagen del comprobante --}}
            <div class="flex-shrink-0">
                <a href="{{ Storage::url($comp->imagen_url) }}" target="_blank" rel="noopener">
                    <img src="{{ Storage::url($comp->imagen_url) }}"
                         alt="Comprobante #{{ $comp->id }}"
                         class="w-32 h-32 object-cover rounded-xl border border-gray-700 hover:opacity-80 transition cursor-zoom-in">
                </a>
            </div>

            {{-- Información --}}
            <div class="flex-1">
                <div class="flex flex-wrap gap-x-6 gap-y-1 text-sm mb-3">
                    <div>
                        <span class="text-gray-500">Pasajero:</span>
                        <span class="font-semibold ml-1">{{ $comp->boleto->nombre_pasajero }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Ruta:</span>
                        <span class="ml-1">{{ $comp->boleto->origen_abordaje }} → {{ $comp->boleto->destino_desembarque }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Fecha viaje:</span>
                        <span class="ml-1">{{ $comp->boleto->hojaRuta->fecha->format('d/m/Y') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Precio:</span>
                        <span class="font-bold text-green-400 ml-1">${{ $comp->boleto->precio }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Cliente:</span>
                        <span class="ml-1">{{ $comp->boleto->user?->email ?? 'Sin usuario' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Enviado:</span>
                        <span class="ml-1">{{ $comp->created_at->diffForHumans() }}</span>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold
                        {{ $comp->estado === 'Pendiente' ? 'bg-yellow-900 text-yellow-300' : '' }}
                        {{ $comp->estado === 'Aprobado'  ? 'bg-green-900 text-green-300' : '' }}
                        {{ $comp->estado === 'Rechazado' ? 'bg-red-900 text-red-300' : '' }}">
                        {{ $comp->estado }}
                    </span>

                    @if($comp->estado === 'Pendiente')
                    <button wire:click="abrirModal({{ $comp->id }})"
                        class="ml-2 px-4 py-1.5 bg-brand-700 hover:bg-brand-600 text-white text-sm font-semibold rounded-lg transition">
                        Revisar
                    </button>
                    @elseif($comp->validadoPor)
                    <span class="text-xs text-gray-500 ml-2">
                        por {{ $comp->validadoPor->name }} — {{ $comp->validado_at?->format('d/m/Y H:i') }}
                    </span>
                    @endif
                </div>

                @if($comp->observaciones)
                <p class="mt-2 text-xs text-gray-400 italic">Obs: {{ $comp->observaciones }}</p>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $comprobantes->links() }}
    </div>
    @endif

    {{-- Modal de Validación --}}
    @if($comprobanteModal)
    <div class="fixed inset-0 bg-black/80 z-50 flex items-center justify-center p-4" wire:key="modal-{{ $comprobanteModal->id }}">
        <div class="bg-gray-900 border border-gray-700 rounded-2xl w-full max-w-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-5">
                    <h2 class="font-bold text-xl">Revisar Comprobante #{{ $comprobanteModal->id }}</h2>
                    <button wire:click="cerrarModal" class="text-gray-500 hover:text-white text-xl">✕</button>
                </div>

                <img src="{{ Storage::url($comprobanteModal->imagen_url) }}"
                     alt="Comprobante"
                     class="w-full rounded-xl border border-gray-700 mb-5 max-h-72 object-contain bg-gray-800">

                <div class="text-sm text-gray-400 mb-4">
                    <p>Pasajero: <span class="text-white font-semibold">{{ $comprobanteModal->boleto->nombre_pasajero }}</span></p>
                    <p>Monto esperado: <span class="text-green-400 font-bold">${{ $comprobanteModal->boleto->precio }}</span></p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm text-gray-400 mb-1">Observaciones (opcional)</label>
                    <textarea wire:model="observaciones" rows="2"
                        class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 resize-none"
                        placeholder="Motivo de rechazo u observación..."></textarea>
                </div>

                <div class="flex gap-3">
                    <button wire:click="aprobar({{ $comprobanteModal->id }})"
                        wire:loading.attr="disabled"
                        class="flex-1 py-3 bg-green-600 hover:bg-green-500 text-white font-bold rounded-xl transition">
                        <span wire:loading.remove wire:target="aprobar">✅ Aprobar</span>
                        <span wire:loading wire:target="aprobar">Procesando...</span>
                    </button>
                    <button wire:click="rechazar({{ $comprobanteModal->id }})"
                        wire:loading.attr="disabled"
                        class="flex-1 py-3 bg-red-700 hover:bg-red-600 text-white font-bold rounded-xl transition">
                        <span wire:loading.remove wire:target="rechazar">❌ Rechazar</span>
                        <span wire:loading wire:target="rechazar">Procesando...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

