<x-layouts.admin>
    <x-slot:title>Gestión de Buses</x-slot:title>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex justify-between items-end mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-white">Catálogo de Buses</h1>
                <p class="text-gray-400 mt-1">Administra la flota de tu cooperativa</p>
            </div>
            
            <button x-data x-on:click="$dispatch('open-modal', 'nuevo-bus')" 
                class="px-5 py-2.5 bg-brand-600 hover:bg-brand-500 text-white font-bold rounded-xl transition">
                + Nuevo Bus
            </button>
        </div>

        <div class="bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-400 uppercase bg-gray-900/50 border-b border-gray-800">
                        <tr>
                            <th class="px-6 py-4">Disco / Placa</th>
                            <th class="px-6 py-4">Detalles Técnicos</th>
                            <th class="px-6 py-4">Foto</th>
                            <th class="px-6 py-4">Categoría</th>
                            <th class="px-6 py-4">Capacidad</th>
                            <th class="px-6 py-4">Estado</th>
                            <th class="px-6 py-4 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        @foreach($buses as $bus)
                        <tr class="hover:bg-gray-800/50 transition">
                            <td class="px-6 py-4">
                                <div class="font-bold text-white text-lg">#{{ $bus->numero_disco }}</div>
                                <div class="text-gray-400">{{ $bus->placa }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-400">
                                {{ $bus->marca_chasis ?? 'N/A' }} <br>
                                <span class="text-xs">{{ $bus->carroceria ?? 'N/A' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($bus->foto_url)
                                    <img src="{{ asset('storage/' . $bus->foto_url) }}" alt="Foto" class="h-12 w-20 object-cover rounded-lg border border-gray-700">
                                @else
                                    <span class="text-xs text-gray-600 italic">Sin foto</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 bg-gray-800 border border-gray-700 rounded-full text-xs font-bold text-gray-300">
                                    {{ $bus->categoria->nombre }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-white font-medium">
                                {{ $bus->capacidad_asientos }} Asientos
                            </td>
                            <td class="px-6 py-4">
                                @if($bus->habilitado)
                                    <span class="text-green-400 font-bold text-xs flex items-center gap-1">
                                        <div class="w-2 h-2 rounded-full bg-green-500"></div> Activo
                                    </span>
                                @else
                                    <span class="text-red-400 font-bold text-xs flex items-center gap-1">
                                        <div class="w-2 h-2 rounded-full bg-red-500"></div> Inactivo
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right flex justify-end gap-2">
                                <button x-data x-on:click="$dispatch('open-modal', 'editar-bus-{{ $bus->id }}')" class="text-brand-400 hover:text-brand-300 text-xs font-semibold">Editar</button>
                                <form method="POST" action="{{ route('admin.buses.destroy', $bus->id) }}" onsubmit="return confirm('¿Seguro que deseas eliminar este bus?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-300 text-xs font-semibold">Eliminar</button>
                                </form>
                            </td>
                        </tr>

                        {{-- Modal Editar Bus --}}
                        <div x-data="{ show: false }" 
                             x-on:open-modal.window="if ($event.detail === 'editar-bus-{{ $bus->id }}') show = true"
                             x-show="show" 
                             class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 p-4" x-cloak>
                            
                            <div class="bg-gray-900 border border-gray-700 rounded-2xl w-full max-w-lg overflow-hidden text-left" @click.away="show = false">
                                <div class="px-6 py-4 border-b border-gray-800 flex justify-between items-center">
                                    <h3 class="font-bold text-lg text-white">Editar Bus #{{ $bus->numero_disco }}</h3>
                                    <button @click="show = false" class="text-gray-500 hover:text-white">✕</button>
                                </div>
                                
                                <form method="POST" action="{{ route('admin.buses.update', $bus->id) }}" enctype="multipart/form-data" class="p-6 space-y-4">
                                    @csrf @method('PUT')
                                    
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm text-gray-400 mb-1">Número de Disco</label>
                                            <input type="text" name="numero_disco" value="{{ $bus->numero_disco }}" required
                                                class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:ring-brand-500 focus:border-brand-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm text-gray-400 mb-1">Placa</label>
                                            <input type="text" name="placa" value="{{ $bus->placa }}" required
                                                class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:ring-brand-500 focus:border-brand-500">
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm text-gray-400 mb-1">Categoría</label>
                                            <select name="categoria_bus_id" required class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:ring-brand-500 focus:border-brand-500">
                                                @foreach($categorias as $cat)
                                                    <option value="{{ $cat->id }}" @selected($cat->id == $bus->categoria_bus_id)>{{ $cat->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm text-gray-400 mb-1">Estado</label>
                                            <label class="flex items-center mt-3 gap-2 text-white">
                                                <input type="checkbox" name="habilitado" value="1" @checked($bus->habilitado) class="rounded bg-gray-800 border-gray-700">
                                                Habilitado
                                            </label>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm text-gray-400 mb-1">Marca / Chasis</label>
                                            <input type="text" name="marca_chasis" value="{{ $bus->marca_chasis }}"
                                                class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:ring-brand-500 focus:border-brand-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm text-gray-400 mb-1">Carrocería</label>
                                            <input type="text" name="carroceria" value="{{ $bus->carroceria }}"
                                                class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:ring-brand-500 focus:border-brand-500">
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 gap-4">
                                        <div>
                                            <label class="block text-sm text-gray-400 mb-1">Foto del Bus (Dejar vacío para no cambiar)</label>
                                            <input type="file" name="foto" accept="image/*"
                                                class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:ring-brand-500 focus:border-brand-500">
                                        </div>
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
            {{ $buses->links() }}
        </div>
    </div>

    {{-- Modal Crear Bus --}}
    <div x-data="{ show: false }" 
         x-on:open-modal.window="if ($event.detail === 'nuevo-bus') show = true"
         x-show="show" 
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 p-4" x-cloak>
        
        <div class="bg-gray-900 border border-gray-700 rounded-2xl w-full max-w-lg overflow-hidden" @click.away="show = false">
            <div class="px-6 py-4 border-b border-gray-800 flex justify-between items-center">
                <h3 class="font-bold text-lg text-white">Registrar Nuevo Bus</h3>
                <button @click="show = false" class="text-gray-500 hover:text-white">✕</button>
            </div>
            
            <form method="POST" action="{{ route('admin.buses.store') }}" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Número de Disco</label>
                        <input type="text" name="numero_disco" required placeholder="Ej: 001"
                            class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:ring-brand-500 focus:border-brand-500">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Placa</label>
                        <input type="text" name="placa" required placeholder="Ej: PBG-1234"
                            class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:ring-brand-500 focus:border-brand-500">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Categoría</label>
                        <select name="categoria_bus_id" required class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:ring-brand-500 focus:border-brand-500">
                            @foreach($categorias as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Capacidad (Asientos)</label>
                        <input type="number" name="capacidad_asientos" required min="10" max="100" value="40"
                            class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:ring-brand-500 focus:border-brand-500">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Marca / Chasis</label>
                        <input type="text" name="marca_chasis" placeholder="Ej: Mercedes-Benz"
                            class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:ring-brand-500 focus:border-brand-500">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Carrocería</label>
                        <input type="text" name="carroceria" placeholder="Ej: Marcopolo"
                            class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:ring-brand-500 focus:border-brand-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Foto del Bus</label>
                        <input type="file" name="foto" accept="image/*"
                            class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:ring-brand-500 focus:border-brand-500">
                    </div>
                </div>

                <div class="pt-4 flex justify-end gap-3">
                    <button type="button" @click="show = false" class="px-5 py-2.5 bg-gray-800 hover:bg-gray-700 text-white rounded-xl transition">
                        Cancelar
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-500 text-white font-bold rounded-xl transition">
                        Registrar Bus
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>

