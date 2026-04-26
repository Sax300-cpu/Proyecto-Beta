<x-layouts.app>
    <x-slot:title>Validar Boleto - Chofer</x-slot:title>

    <div class="max-w-2xl mx-auto px-4 py-8">
        <div class="mb-6">
            <a href="{{ route('chofer.escaner') }}" class="text-brand-400 hover:text-brand-300 flex items-center gap-2 mb-4">
                ← Volver al Escáner
            </a>
            <h1 class="text-3xl font-extrabold text-white">Validación de Boleto</h1>
        </div>

        @if(session('success'))
        <div class="bg-green-900/50 border border-green-700 text-green-300 px-4 py-3 rounded-xl mb-6">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-900/50 border border-red-700 text-red-300 px-4 py-3 rounded-xl mb-6">
            {{ session('error') }}
        </div>
        @endif

        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6">
            <div class="flex items-start justify-between mb-6 border-b border-gray-800 pb-4">
                <div>
                    <h2 class="text-2xl font-bold text-white">{{ $boleto->nombre_pasajero }}</h2>
                    <p class="text-gray-400">C.I: {{ $boleto->cedula_pasajero }}</p>
                </div>
                <div class="text-right">
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold
                        {{ $boleto->estado === 'Validado' ? 'bg-green-900 text-green-300' : '' }}
                        {{ $boleto->estado === 'Abordado' ? 'bg-brand-900 text-brand-300' : '' }}
                        {{ in_array($boleto->estado, ['Pendiente', 'Cancelado', 'No Show']) ? 'bg-red-900 text-red-300' : '' }}">
                        Estado Actual: {{ $boleto->estado }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-8">
                <div class="bg-gray-800 p-4 rounded-xl">
                    <p class="text-xs text-gray-400">Origen</p>
                    <p class="text-lg font-bold text-white">{{ $boleto->origen_abordaje }}</p>
                </div>
                <div class="bg-gray-800 p-4 rounded-xl">
                    <p class="text-xs text-gray-400">Destino</p>
                    <p class="text-lg font-bold text-white">{{ $boleto->destino_desembarque }}</p>
                </div>
                <div class="bg-gray-800 p-4 rounded-xl">
                    <p class="text-xs text-gray-400">Asiento</p>
                    <p class="text-lg font-bold text-white">#{{ $boleto->asiento->numero }}</p>
                </div>
                <div class="bg-gray-800 p-4 rounded-xl">
                    <p class="text-xs text-gray-400">Tipo</p>
                    <p class="text-lg font-bold text-white">{{ $boleto->tipo_pasajero }}</p>
                </div>
            </div>

            @if($boleto->estado === 'Validado')
                <div class="flex gap-4">
                    {{-- Usaremos el Livewire Component para procesar si se desea --}}
                    {{-- O directamente creamos forms que apunten a una ruta. Como no tenemos ruta POST en ChoferController, actualicemos el estado --}}
                    <form method="POST" action="{{ route('chofer.validar-qr.accion', $boleto->id) }}" class="flex-1">
                        @csrf
                        <input type="hidden" name="accion" value="abordado">
                        <button type="submit" class="w-full py-4 bg-brand-600 hover:bg-brand-500 text-white font-bold rounded-xl text-lg transition shadow-lg shadow-brand-500/20">
                            ✓ Marcar como Abordado
                        </button>
                    </form>
                    <form method="POST" action="{{ route('chofer.validar-qr.accion', $boleto->id) }}" class="w-1/3">
                        @csrf
                        <input type="hidden" name="accion" value="noshow">
                        <button type="submit" class="w-full py-4 bg-gray-800 hover:bg-red-900 hover:text-red-300 text-gray-400 font-bold rounded-xl transition">
                            No Show
                        </button>
                    </form>
                </div>
            @elseif($boleto->estado === 'Abordado')
                <div class="bg-brand-900/20 border border-brand-800 p-4 rounded-xl text-center">
                    <p class="text-brand-400 font-semibold">El pasajero ya registró su abordaje.</p>
                </div>
            @else
                <div class="bg-red-900/20 border border-red-800 p-4 rounded-xl text-center">
                    <p class="text-red-400 font-semibold">El boleto no es válido para abordar.</p>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>

