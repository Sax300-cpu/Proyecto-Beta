<x-layouts.admin>
    <x-slot:title>Gestión de Usuarios</x-slot:title>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex justify-between items-end mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-white">Usuarios del Sistema</h1>
                <p class="text-gray-400 mt-1">Administra los usuarios de tu cooperativa y sus roles</p>
            </div>
            
            <button x-data x-on:click="$dispatch(\'open-modal\', \'nuevo-usuario\')" 
                class="px-5 py-2.5 bg-brand-600 hover:bg-brand-500 text-white font-bold rounded-xl transition">
                + Nuevo Usuario
            </button>
        </div>

        <div class="bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-400 uppercase bg-gray-900/50 border-b border-gray-800">
                        <tr>
                            <th class="px-6 py-4">Usuario</th>
                            <th class="px-6 py-4">Contacto</th>
                            <th class="px-6 py-4">Cédula</th>
                            <th class="px-6 py-4">Roles</th>
                            <th class="px-6 py-4">Cooperativa</th>
                            <th class="px-6 py-4 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        @foreach($users as $usr)
                        <tr class="hover:bg-gray-800/50 transition">
                            <td class="px-6 py-4">
                                <div class="font-medium text-white">{{ $usr->name }}</div>
                                <div class="text-gray-500 text-xs">Registrado: {{ $usr->created_at->format(\'d/m/Y\') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-gray-300">{{ $usr->email }}</div>
                                <div class="text-gray-500 text-xs">{{ $usr->telefono ?? \'Sin teléfono\' }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-300">{{ $usr->cedula ?? \'-\' }}</td>
                            <td class="px-6 py-4">
                                @foreach($usr->getRoleNames() as $rol)
                                    <span class="px-3 py-1 bg-brand-900/50 text-brand-300 border border-brand-800 rounded-full text-xs font-medium">
                                        {{ $rol }}
                                    </span>
                                @endforeach
                            </td>
                            <td class="px-6 py-4 text-gray-300">{{ $usr->cooperativa->nombre ?? \'N/A\' }}</td>
                            <td class="px-6 py-4 text-right flex justify-end gap-2">
                                <button x-data x-on:click="$dispatch(\'open-modal\', \'editar-usuario-{{ $usr->id }}\' )" class="text-brand-400 hover:text-brand-300 text-xs font-semibold">Editar</button>
                                <form method="POST" action="{{ route(\'admin.usuarios.destroy\', $usr->id) }}" onsubmit="return confirm(\'¿Seguro que deseas eliminar a este usuario?\');">
                                    @csrf @method(\'DELETE\')
                                    <button type="submit" class="text-red-400 hover:text-red-300 text-xs font-semibold">Eliminar</button>
                                </form>
                            </td>
                        </tr>

                        {{-- Modal Editar Usuario --}}
                        <div x-data="{ show: false }" 
                             x-on:open-modal.window="if ($event.detail === \'editar-usuario-{{ $usr->id }}\\' ) show = true"
                             x-show="show" 
                             class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 p-4" x-cloak>
                            
                            <div class="bg-gray-900 border border-gray-700 rounded-2xl w-full max-w-lg overflow-hidden text-left" @click.away="show = false">
                                <div class="px-6 py-4 border-b border-gray-800 flex justify-between items-center">
                                    <h3 class="font-bold text-lg text-white">Editar Usuario: {{ $usr->name }}</h3>
                                    <button @click="show = false" class="text-gray-500 hover:text-white">✕</button>
                                </div>
                                
                                <form method="POST" action="{{ route(\'admin.usuarios.update\', $usr->id) }}" class="p-6 space-y-4">
                                    @csrf @method(\'PUT\')
                                    
                                    <div>
                                        <label class="block text-sm text-gray-400 mb-1">Nombre Completo</label>
                                        <input type="text" name="name" value="{{ $usr->name }}" required
                                            class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:ring-brand-500 focus:border-brand-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-400 mb-1">Correo Electrónico</label>
                                        <input type="email" name="email" value="{{ $usr->email }}" required
                                            class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:ring-brand-500 focus:border-brand-500">
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm text-gray-400 mb-1">Cédula</label>
                                            <input type="text" name="cedula" value="{{ $usr->cedula }}"
                                                class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:ring-brand-500 focus:border-brand-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm text-gray-400 mb-1">Teléfono</label>
                                            <input type="text" name="telefono" value="{{ $usr->telefono }}"
                                                class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:ring-brand-500 focus:border-brand-500">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-400 mb-1">Fecha de Nacimiento</label>
                                        <input type="date" name="fecha_nacimiento" value="{{ $usr->fecha_nacimiento?->format(\'Y-m-d\') }}"
                                            class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:ring-brand-500 focus:border-brand-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-400 mb-1">Cooperativa Asignada</label>
                                        <select name="cooperativa_id" class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:ring-brand-500 focus:border-brand-500">
                                            <option value="">N/A (Usuario Final)</option>
                                            @foreach($cooperativas as $coop)
                                                <option value="{{ $coop->id }}" @selected($coop->id == $usr->cooperativa_id)>{{ $coop->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-400 mb-1">Roles</label>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($roles as $rol)
                                                <label class="inline-flex items-center text-white">
                                                    <input type="checkbox" name="roles[]" value="{{ $rol->name }}" @checked($usr->hasRole($rol->name))
                                                        class="rounded bg-gray-800 border-gray-700 text-brand-600 focus:ring-brand-600">
                                                    <span class="ml-2 text-sm">{{ $rol->name }}</span>
                                                </label>
                                            @endforeach
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
            {{ $users->links() }}
        </div>
    </div>

    {{-- Modal Crear Usuario --}}
    <div x-data="{ show: false }" 
         x-on:open-modal.window="if ($event.detail === \'nuevo-usuario\') show = true"
         x-show="show" 
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 p-4" x-cloak>
        
        <div class="bg-gray-900 border border-gray-700 rounded-2xl w-full max-w-lg overflow-hidden" @click.away="show = false">
            <div class="px-6 py-4 border-b border-gray-800 flex justify-between items-center">
                <h3 class="font-bold text-lg text-white">Registrar Nuevo Usuario</h3>
                <button @click="show = false" class="text-gray-500 hover:text-white">✕</button>
            </div>
            
            <form method="POST" action="{{ route(\'admin.usuarios.store\') }}" class="p-6 space-y-4">
                @csrf
                
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Nombre Completo</label>
                    <input type="text" name="name" required placeholder="Ej: Juan Pérez"
                        class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:ring-brand-500 focus:border-brand-500">
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Correo Electrónico</label>
                    <input type="email" name="email" required placeholder="ejemplo@dominio.com"
                        class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:ring-brand-500 focus:border-brand-500">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Contraseña</label>
                        <input type="password" name="password" required
                            class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:ring-brand-500 focus:border-brand-500">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Confirmar Contraseña</label>
                        <input type="password" name="password_confirmation" required
                            class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:ring-brand-500 focus:border-brand-500">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Cédula</label>
                        <input type="text" name="cedula" placeholder="Ej: 1234567890"
                            class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:ring-brand-500 focus:border-brand-500">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Teléfono</label>
                        <input type="text" name="telefono" placeholder="Ej: 0987654321"
                            class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:ring-brand-500 focus:border-brand-500">
                    </div>
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Fecha de Nacimiento</label>
                    <input type="date" name="fecha_nacimiento"
                        class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:ring-brand-500 focus:border-brand-500">
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Cooperativa Asignada</label>
                    <select name="cooperativa_id" class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:ring-brand-500 focus:border-brand-500">
                        <option value="">N/A (Usuario Final)</option>
                        @foreach($cooperativas as $coop)
                            <option value="{{ $coop->id }}">{{ $coop->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Roles</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach($roles as $rol)
                            <label class="inline-flex items-center text-white">
                                <input type="checkbox" name="roles[]" value="{{ $rol->name }}"
                                    class="rounded bg-gray-800 border-gray-700 text-brand-600 focus:ring-brand-600">
                                <span class="ml-2 text-sm">{{ $rol->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="pt-4 flex justify-end gap-3">
                    <button type="button" @click="show = false" class="px-5 py-2.5 bg-gray-800 hover:bg-gray-700 text-white rounded-xl transition">
                        Cancelar
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-500 text-white font-bold rounded-xl transition">
                        Registrar Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>


