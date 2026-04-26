<x-layouts.chofer>
    <x-slot:title>Vender Boleto en Ruta</x-slot:title>

    <div class="max-w-2xl mx-auto px-4 py-8">
        <div class="mb-6">
            <h1 class="text-3xl font-extrabold text-white">Vender en Ruta</h1>
            <p class="text-gray-400 mt-1">Hoja de Ruta #{{ $hojaRuta->id }} ({{ $hojaRuta->frecuencia->ruta->origen }} &rarr; {{ $hojaRuta->frecuencia->ruta->destino }})</p>
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

        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6">
            <form method="POST" action="{{ route('chofer.vender-en-ruta.store', $hojaRuta->id) }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm text-gray-400 mb-1">Asiento</label>
                    <select name="asiento_id" required class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:ring-brand-500 focus:border-brand-500">
                        <option value="">Seleccione un asiento</option>
                        @foreach($asientosDisponibles as $asiento)
                            <option value="{{ $asiento->id }}">#{{ $asiento->numero }} ({{ $asiento->tipo }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Nombre Pasajero</label>
                        <input type="text" name="nombre_pasajero" required
                            class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:ring-brand-500 focus:border-brand-500">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Cédula Pasajero</label>
                        <input type="text" name="cedula_pasajero" required
                            class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:ring-brand-500 focus:border-brand-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm text-gray-400 mb-1">Tipo Pasajero</label>
                    <select name="tipo_pasajero" required class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:ring-brand-500 focus:border-brand-500">
                        <option value="Normal">Normal</option>
                        <option value="Niño">Niño</option>
                        <option value="Tercera Edad">Tercera Edad</option>
                        <option value="Discapacitado">Discapacitado</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Origen Abordaje</label>
                        <select name="origen_abordaje" required class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:ring-brand-500 focus:border-brand-500">
                            <option value="{{ $hojaRuta->frecuencia->ruta->origen }}">{{ $hojaRuta->frecuencia->ruta->origen }} (Origen de Ruta)</option>
                            @foreach($paradas as $parada)
                                <option value="{{ $parada->nombre }}">{{ $parada->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Destino Desembarque</label>
                        <select name="destino_desembarque" required class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:ring-brand-500 focus:border-brand-500">
                            <option value="{{ $hojaRuta->frecuencia->ruta->destino }}">{{ $hojaRuta->frecuencia->ruta->destino }} (Destino de Ruta)</option>
                            @foreach($paradas as $parada)
                                <option value="{{ $parada->nombre }}">{{ $parada->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm text-gray-400 mb-1">Precio ($)</label>
                    <input type="number" step="0.01" name="precio" required min="0.01"
                        class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:ring-brand-500 focus:border-brand-500">
                </div>

                <div class="pt-4 flex justify-end">
                    <button type="submit" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-500 text-white font-bold rounded-xl transition">
                        Vender Boleto
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.chofer>