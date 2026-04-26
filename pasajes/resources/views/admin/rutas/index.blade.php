<x-layouts.admin>
    <x-slot:title>Gestión de Rutas</x-slot:title>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex justify-between items-end mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-white">Catálogo de Rutas</h1>
                <p class="text-gray-400 mt-1">Administra los orígenes y destinos de tu cooperativa</p>
            </div>
            
            <button x-data x-on:click="$dispatch('open-modal', 'nueva-ruta')" 
                class="px-5 py-2.5 bg-brand-600 hover:bg-brand-500 text-white font-bold rounded-xl transition">
                + Nueva Ruta
            </button>
        </div>

        <div class="bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-400 uppercase bg-gray-900/50 border-b border-gray-800">
                        <tr>
                            <th class="px-6 py-4">Origen</th>
                            <th class="px-6 py-4">Destino</th>
                            <th class="px-6 py-4">Estado</th>
                            <th class="px-6 py-4 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        @foreach($rutas as $ruta)
                        <tr class="hover:bg-gray-800/50 transition">
                            <td class="px-6 py-4 font-bold text-white text-lg">
                                {{ $ruta->origen }}
                            </td>
                            <td class="px-6 py-4 font-bold text-white text-lg">
                                {{ $ruta->destino }}
                            </td>
                            <td class="px-6 py-4">
                                @if($ruta->activa)
                                    <span class="text-green-400 font-bold text-xs flex items-center gap-1">
                                        <div class="w-2 h-2 rounded-full bg-green-500"></div> Activa
                                    </span>
                                @else
                                    <span class="text-red-400 font-bold text-xs flex items-center gap-1">
                                        <div class="w-2 h-2 rounded-full bg-red-500"></div> Inactiva
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <form method="POST" action="{{ route('admin.rutas.destroy', $ruta->id) }}" onsubmit="return confirm('¿Seguro que deseas eliminar esta ruta?');">
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
            {{ $rutas->links() }}
        </div>
    </div>

    {{-- Modal Crear Ruta --}}
    <div x-data="{ show: false }" 
         x-on:open-modal.window="if ($event.detail === 'nueva-ruta') show = true"
         x-show="show" 
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 p-4" x-cloak>
        
        <div class="bg-gray-900 border border-gray-700 rounded-2xl w-full max-w-md overflow-hidden" @click.away="show = false">
            <div class="px-6 py-4 border-b border-gray-800 flex justify-between items-center">
                <h3 class="font-bold text-lg text-white">Registrar Nueva Ruta</h3>
                <button @click="show = false" class="text-gray-500 hover:text-white">✕</button>
            </div>
            
            <form method="POST" action="{{ route('admin.rutas.store') }}" class="p-6 space-y-4">
                @csrf
                
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Ciudad de Origen</label>
                    <input type="text" name="origen" required placeholder="Ej: Ambato"
                        class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:ring-brand-500 focus:border-brand-500">
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Ciudad de Destino</label>
                    <input type="text" name="destino" required placeholder="Ej: Quito"
                        class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:ring-brand-500 focus:border-brand-500">
                </div>

                <div class="pt-4 flex justify-end gap-3">
                    <button type="button" @click="show = false" class="px-5 py-2.5 bg-gray-800 hover:bg-gray-700 text-white rounded-xl transition">
                        Cancelar
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-500 text-white font-bold rounded-xl transition">
                        Guardar Ruta
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>

