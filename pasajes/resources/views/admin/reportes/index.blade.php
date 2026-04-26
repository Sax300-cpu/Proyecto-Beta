<x-layouts.admin>
    <x-slot:title>Reportes Cooperativa</x-slot:title>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-white">Reportes Operativos</h1>
            <p class="text-gray-400 mt-1">Analiza el desempeño de ventas y ocupación</p>
        </div>

        {{-- Filtros --}}
        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6 mb-8">
            <form method="GET" action="{{ route('admin.reportes.index') }}" class="flex flex-col md:flex-row items-end gap-4">
                <div class="flex-1">
                    <label class="block text-sm text-gray-400 mb-1">Fecha Inicio</label>
                    <input type="date" name="fecha_inicio" value="{{ $fechaInicio }}"
                        class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:ring-brand-500 focus:border-brand-500">
                </div>
                <div class="flex-1">
                    <label class="block text-sm text-gray-400 mb-1">Fecha Fin</label>
                    <input type="date" name="fecha_fin" value="{{ $fechaFin }}"
                        class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:ring-brand-500 focus:border-brand-500">
                </div>
                <button type="submit" class="px-6 py-2.5 bg-brand-600 hover:bg-brand-500 text-white font-bold rounded-xl transition shadow-lg shadow-brand-500/20">
                    Filtrar Reportes
                </button>
            </form>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            {{-- Resumen de Ventas --}}
            <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6">
                <h2 class="text-xl font-bold text-white mb-4">Ventas por Día</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-gray-400 uppercase border-b border-gray-800">
                            <tr>
                                <th class="px-4 py-3">Fecha</th>
                                <th class="px-4 py-3 text-right">Total Recaudado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800">
                            @foreach($ventasPorDia as $venta)
                            <tr>
                                <td class="px-4 py-3 text-white">{{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 text-right font-bold text-green-400">${{ number_format($venta->total, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Resumen de Ocupación --}}
            <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6">
                <h2 class="text-xl font-bold text-white mb-4">Ocupación por Ruta</h2>
                <div class="space-y-6">
                    @foreach($ocupacionRutas as $item)
                    <div>
                        <div class="flex justify-between items-end mb-2">
                            <div>
                                <p class="text-white font-bold">{{ $item['ruta'] }}</p>
                                <p class="text-xs text-gray-500">{{ $item['boletos'] }} boletos vendidos de {{ $item['capacidad'] }} asientos</p>
                            </div>
                            <span class="text-sm font-bold {{ $item['porcentaje'] > 70 ? 'text-green-400' : 'text-yellow-400' }}">
                                {{ $item['porcentaje'] }}%
                            </span>
                        </div>
                        <div class="w-full bg-gray-800 rounded-full h-2.5">
                            <div class="bg-brand-600 h-2.5 rounded-full" style="width: {{ $item['porcentaje'] }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>