<x-layouts.admin>
    <x-slot:title>Gestión de Usuarios</x-slot:title>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex justify-between items-end mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-white">Usuarios del Sistema</h1>
                <p class="text-gray-400 mt-1">Lista de oficinistas, choferes y clientes registrados</p>
            </div>
        </div>

        <div class="bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-400 uppercase bg-gray-900/50 border-b border-gray-800">
                        <tr>
                            <th class="px-6 py-4">Usuario</th>
                            <th class="px-6 py-4">Contacto</th>
                            <th class="px-6 py-4">Cédula</th>
                            <th class="px-6 py-4">Rol</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        @foreach($usuarios as $usr)
                        <tr class="hover:bg-gray-800/50 transition">
                            <td class="px-6 py-4">
                                <div class="font-medium text-white">{{ $usr->name }}</div>
                                <div class="text-gray-500 text-xs">Registrado: {{ $usr->created_at->format('d/m/Y') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-gray-300">{{ $usr->email }}</div>
                                <div class="text-gray-500 text-xs">{{ $usr->telefono ?? 'Sin teléfono' }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-300">{{ $usr->cedula ?? '-' }}</td>
                            <td class="px-6 py-4">
                                @foreach($usr->getRoleNames() as $rol)
                                    <span class="px-3 py-1 bg-brand-900/50 text-brand-300 border border-brand-800 rounded-full text-xs font-medium">
                                        {{ $rol }}
                                    </span>
                                @endforeach
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="mt-6">
            {{ $usuarios->links() }}
        </div>
    </div>
</x-layouts.admin>
