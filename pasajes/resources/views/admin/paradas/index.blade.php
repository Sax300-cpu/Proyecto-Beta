<x-layouts.admin>
    <x-slot:title>Gestión de Paradas</x-slot:title>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex justify-between items-end mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-white">Gestión de Paradas</h1>
                <p class="text-gray-400 mt-1">Administra los puntos de parada intermedios</p>
            </div>
            
            <button x-data x-on:click="$dispatch(\'open-modal\', \'nueva-parada\')" 
                class="px-5 py-2.5 bg-brand-600 hover:bg-brand-500 text-white font-bold rounded-xl transition">
                + Nueva Parada
            </button>
        </div>

        <div class="bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-400 uppercase bg-gray-900/50 border-b border-gray-800">
                        <tr>
                            <th class="px-6 py-4">Nombre</th>
                            <th class="px-6 py-4">Ciudad</th>
                            <th class="px-6 py-4">Provincia</th>
                            <th class="px-6 py-4 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        @foreach($paradas as $parada)
                        <tr class="hover:bg-gray-800/50 transition">
                            <td class="px-6 py-4 font-bold text-white text-lg">
                                {{ $parada->nombre }}
                            </td>
                            <td class="px-6 py-4 text-gray-400">
                                {{ $parada->ciudad }}
                            </td>
                            <td class="px-6 py-4 text-gray-400">
                                {{ $parada->provincia ?? \'N/A\' }}
                            </td>
                            <td class="px-6 py-4 text-right flex justify-end gap-2">
                                <button x-data x-on:click="$dispatch(\'open-modal\', \'editar-parada-{{ $parada->id }}\' )" class="text-brand-400 hover:text-brand-300 text-xs font-semibold">Editar</button>
                                <form method="POST" action="{{ route(\'admin.paradas.destroy\', $parada->id) }}" onsubmit="return confirm(\'¿Seguro que deseas eliminar esta parada?\
                                                                ');">
                                    @csrf @method(\'DELETE\')
                                    <button type="submit" class="text-red-400 hover:text-red-300 text-xs font-semibold">Eliminar</button>
                                </form>
                            </td>
                        </tr>

                        {{-- Modal Editar Parada --}}
                        <div x-data="{ show: false }" 
                             x-on:open-modal.window="if ($event.detail === \'editar-parada-{{ $parada->id }}\' ) show = true"
                             x-show="show" 
                             class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 p-4" x-cloak>
                            
                            <div class="bg-gray-900 border border-gray-700 rounded-2xl w-full max-w-lg overflow-hidden text-left" @click.away="show = false">
                                <div class="px-6 py-4 border-b border-gray-800 flex justify-between items-center">
                                    <h3 class="font-bold text-lg text-white">Editar Parada: {{ $parada->nombre }}</h3>
                                    <button @click="show = false" class="text-gray-500 hover:text-white">✕</button>
                                </div>
                                
                                <form method="POST" action="{{ route(\'admin.paradas.update\', $parada->id) }}" class="p-6 space-y-4">
                                    @csrf @method(\'PUT\')
                                    
                                    <div>
                                        <label class="block text-sm text-gray-400 mb-1">Nombre</label>
                                        <input type="text" name="nombre" value="{{ $parada->nombre }}" required
                                            class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:ring-brand-500 focus:border-brand-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-400 mb-1">Ciudad</label>
                                        <input type="text" name="ciudad" value="{{ $parada->ciudad }}" required
                                            class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:ring-brand-500 focus:border-brand-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-400 mb-1">Provincia</label>
                                        <input type="text" name="provincia" value="{{ $parada->provincia }}"
                                            class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:ring-brand-500 focus:border-brand-500">
                                    </div>

                                    <div class="pt-4 flex justify-end gap-3">
                                        <button type="button" @click="show = false" class="px-5 py-2.5 bg-gray-800 hover:bg-gray-700 text-white rounded-xl transition">
                                            Cancelar
                                        </button>
                                        <button type="submit" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-500 text-white font-bold rounded-xl transition">
                                            Guardar Cambios
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="mt-6">
            {{ $paradas->links() }}
        </div>
    </div>

    {{-- Modal Crear Parada --}}
    <div x-data="{ show: false }" 
         x-on:open-modal.window="if ($event.detail === \'nueva-parada\') show = true"
         x-show="show" 
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 p-4" x-cloak>
        
        <div class="bg-gray-900 border border-gray-700 rounded-2xl w-full max-w-lg overflow-hidden" @click.away="show = false">
            <div class="px-6 py-4 border-b border-gray-800 flex justify-between items-center">
                <h3 class="font-bold text-lg text-white">Crear Nueva Parada</h3>
                <button @click="show = false" class="text-gray-500 hover:text-white">✕</button>
            </div>
            
            <form method="POST" action="{{ route(\'admin.paradas.store\') }}" class="p-6 space-y-4">
                @csrf
                
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Nombre de la Parada</label>
                    <input type="text" name="nombre" required placeholder="Ej: Terminal Terrestre"
                        class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:ring-brand-500 focus:border-brand-500">
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Ciudad</label>
                    <input type="text" name="ciudad" required placeholder="Ej: Ambato"
                        class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:ring-brand-500 focus:border-brand-500">
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Provincia (Opcional)</label>
                    <input type="text" name="provincia" placeholder="Ej: Tungurahua"
                        class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:ring-brand-500 focus:border-brand-500">
                </div>

                <div class="pt-4 flex justify-end gap-3">
                    <button type="button" @click="show = false" class="px-5 py-2.5 bg-gray-800 hover:bg-gray-700 text-white rounded-xl transition">
                        Cancelar
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-500 text-white font-bold rounded-xl transition">
                        Guardar Parada
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>