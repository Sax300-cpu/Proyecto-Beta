<x-layouts.app>
    <x-slot:title>Mis Boletos</x-slot:title>

    <div class="max-w-4xl mx-auto px-4 py-10">
        <h1 class="text-3xl font-extrabold text-white mb-6">Mis Boletos</h1>

        @if($boletos->isEmpty())
        <div class="text-center py-16 text-gray-500">
            <div class="text-5xl mb-4">🎫</div>
            <p class="text-lg">Aún no tienes boletos.</p>
            <a href="{{ route('home') }}" class="mt-4 inline-block px-6 py-3 bg-brand-600 hover:bg-brand-500 text-white font-bold rounded-xl transition">
                Buscar frecuencias
            </a>
        </div>
        @else
        <div class="space-y-4">
            @foreach($boletos as $boleto)
            <a href="{{ route('boleto.ver', $boleto->id) }}"
               class="block bg-gray-900 border border-gray-800 hover:border-brand-700 rounded-2xl p-5 transition group">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <p class="font-bold text-xl text-white group-hover:text-brand-400 transition">
                            {{ $boleto->origen_abordaje }} → {{ $boleto->destino_desembarque }}
                        </p>
                        <p class="text-gray-400 text-sm mt-1">
                            {{ $boleto->hojaRuta->fecha->format('d/m/Y') }} •
                            {{ \Carbon\Carbon::parse($boleto->hojaRuta->frecuencia->hora_salida)->format('H:i') }} •
                            Asiento {{ $boleto->asiento->numero }}
                        </p>
                        <p class="text-gray-500 text-sm">{{ $boleto->hojaRuta->frecuencia->ruta->cooperativa->nombre }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="font-bold text-green-400 text-lg">${{ $boleto->precio }}</span>
                        <span class="px-3 py-1 rounded-full text-xs font-bold
                            {{ $boleto->estado === 'Validado'  ? 'bg-green-900 text-green-300' : '' }}
                            {{ $boleto->estado === 'Pendiente' ? 'bg-yellow-900 text-yellow-300' : '' }}
                            {{ $boleto->estado === 'Rechazado' ? 'bg-red-900 text-red-300' : '' }}
                            {{ $boleto->estado === 'Abordado'  ? 'bg-brand-900 text-brand-300' : '' }}">
                            {{ $boleto->estado }}
                        </span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        <div class="mt-6">{{ $boletos->links() }}</div>
        @endif
    </div>
</x-layouts.app>

