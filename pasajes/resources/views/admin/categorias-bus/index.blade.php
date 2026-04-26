<x-layouts.admin>
    <x-slot:title>Gestión de Categorías de Bus</x-slot:title>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex justify-between items-end mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-white">Categorías de Bus</h1>
                <p class="text-gray-400 mt-1">Administra las categorías de tus buses y su precio base</p>
            </div>
            
            <button x-data x-on:click="$dispatch('open-modal', 'nueva-categoria')" 
                class="px-5 py-2.5 bg-brand-600 hover:bg-brand-500 text-white font-bold rounded-xl transition">
                + Nueva Categoría
            </button>
        </div>

        <div class="bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-400 uppercase bg-gray-900/50 border-b border-gray-800">
                        <tr>
                            <th class="px-6 py-4">Nombre</th>
                            <th class="px-6 py-4">Descripción</th>
                            <th class="px-6 py-4">Precio Base</th>
                            <th class="px-6 py-4 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        @foreach($categorias as $cat)
                        <tr class="hover:bg-gray-800/50 transition">
                            <td class="px-6 py-4 font-bold text-white text-lg">
                                {{ $cat->nombre }}
                            </td>
                            <td class="px-6 py-4 text-gray-400">
                                {{ $cat->descripcion ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 font-bold text-green-400">
                                ${{ number_format($cat->precio_base, 2) }}
                            </td>
                            <td class="px-6 py-4 text-right flex justify-end gap-2">
                                <button x-data x-on:click="$dispatch('open-modal', 'editar-categoria-{{ $cat->id }}')" class="text-brand-400 hover:text-brand-300 text-xs font-semibold">Editar</button>
                                <form method="POST" action="{{ route('admin.categorias-bus.destroy', $cat->id) }}" onsubmit="return confirm('¿Seguro que deseas eliminar esta categoría?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-300 text-xs font-semibold">Eliminar</button>
                                </form>
                            </td>
                        </tr>

                        {{-- Modal Editar Categoría --}}
                        <div x-data="{ show: false }" 
                             x-on:open-modal.window="if ($event.detail === 'editar-categoria-{{ $cat->id }}') show = true"
                             x-show="show" 
                             class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 p-4" x-cloak>
                            
                            <div class="bg-gray-900 border border-gray-700 rounded-2xl w-full max-w-lg overflow-hidden text-left" @click.away="show = false">
                                <div class="px-6 py-4 border-b border-gray-800 flex justify-between items-center">
                                    <h3 class="font-bold text-lg text-white">Editar Categoría: {{ $cat->nombre }}</h3>
                                    <button @click="show = false" class="text-gray-500 hover:text-white">✕</button>
                                </div>
                                
                                <form method="POST" action="{{ route('admin.categorias-bus.update', $cat->id) }}" class="p-6 space-y-4">
                                    @csrf @method('PUT')
                                    
                                    <div>
                                        <label class="block text-sm text-gray-400 mb-1">Nombre</label>
                                        <input type="text" name="nombre" value="{{ $cat->nombre }}" required
                                            class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:ring-brand-500 focus:border-brand-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-400 mb-1">Descripción</label>
                                        <textarea name="descripcion" rows="3" 
                                            class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:ring-brand-500 focus:border-brand-500">{{ $cat->descripcion }}</textarea>
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-400 mb-1">Precio Base por Asiento ($)</label>
                                        <input type="number" step="0.01" name="precio_base" value="{{ $cat->precio_base }}" required
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
            {{ $categorias->links() }}
        </div>
    </div>

    {{-- Modal Crear Categoría --}}
    <div x-data="{ show: false }" 
         x-on:open-modal.window="if ($event.detail === 'nueva-categoria') show = true"
         x-show="show" 
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 p-4" x-cloak>
        
        <div class="bg-gray-900 border border-gray-700 rounded-2xl w-full max-w-lg overflow-hidden" @click.away="show = false">
            <div class="px-6 py-4 border-b border-gray-800 flex justify-between items-center">
                <h3 class="font-bold text-lg text-white">Crear Nueva Categoría de Bus</h3>
                <button @click="show = false" class="text-gray-500 hover:text-white">✕</button>
            </div>
            
            <form method="POST" action="{{ route('admin.categorias-bus.store') }}" class="p-6 space-y-4">
                @csrf
                
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Nombre de la Categoría</label>
                    <input type="text" name="nombre" required placeholder="Ej: VIP, Semi-Cama"
                        class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:ring-brand-500 focus:border-brand-500">
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Descripción (Opcional)</label>
                    <textarea name="descripcion" rows="3" placeholder="Características del bus o asientos"
                        class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:ring-brand-500 focus:border-brand-500"></textarea>
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Precio Base por Asiento ($)</label>
                    <input type="number" step="0.01" name="precio_base" value="0.00" required
                        class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:ring-brand-500 focus:border-brand-500">
                </div>

                <div class="pt-4 flex justify-end gap-3">
                    <button type="button" @click="show = false" class="px-5 py-2.5 bg-gray-800 hover:bg-gray-700 text-white rounded-xl transition">
                        Cancelar
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-500 text-white font-bold rounded-xl transition">
                        Guardar Categoría
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>


