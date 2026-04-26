<x-layouts.admin>
    <x-slot:title>Perfil de Cooperativa</x-slot:title>

    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-white">Configuración de la Cooperativa</h1>
            <p class="text-gray-400 mt-1">Actualiza la información pública y bancaria de tu empresa</p>
        </div>

        <form method="POST" action="{{ route('admin.cooperativa.update') }}" enctype="multipart/form-data" class="bg-gray-900 border border-gray-800 rounded-2xl p-6 md:p-8 space-y-8">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Datos Generales --}}
                <div class="space-y-6">
                    <h2 class="text-xl font-bold text-white border-b border-gray-800 pb-2">Datos Generales</h2>
                    
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Nombre Comercial</label>
                        <input type="text" name="nombre" value="{{ old('nombre', $cooperativa->nombre) }}" required
                            class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-400 mb-1">RUC</label>
                        <input type="text" value="{{ $cooperativa->ruc }}" disabled
                            class="w-full bg-gray-800/50 border border-gray-700/50 rounded-xl px-4 py-2.5 text-gray-500 cursor-not-allowed">
                        <p class="text-xs text-gray-500 mt-1">El RUC no puede modificarse. Contacta a soporte.</p>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Dirección Principal</label>
                        <input type="text" name="direccion" value="{{ old('direccion', $cooperativa->direccion) }}"
                            class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Logo de la Cooperativa</label>
                        <input type="file" name="logo" accept="image/*"
                            class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:ring-2 focus:ring-brand-500">
                        @if($cooperativa->logo_url)
                            <div class="mt-2">
                                <span class="text-xs text-gray-400">Logo actual:</span>
                                <img src="{{ asset('storage/' . $cooperativa->logo_url) }}" alt="Logo" class="h-16 mt-1 rounded">
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Contacto y Redes --}}
                <div class="space-y-6">
                    <h2 class="text-xl font-bold text-white border-b border-gray-800 pb-2">Contacto y Redes Sociales</h2>
                    
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Teléfono Fijo</label>
                        <input type="text" name="telefono" value="{{ old('telefono', $cooperativa->telefono) }}"
                            class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-400 mb-1">WhatsApp (Para ventas/soporte)</label>
                        <input type="text" name="whatsapp" value="{{ old('whatsapp', $cooperativa->whatsapp) }}"
                            class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Correo Electrónico (Público)</label>
                        <input type="email" name="email" value="{{ old('email', $cooperativa->email) }}"
                            class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Correo Soporte Técnico</label>
                        <input type="email" name="email_soporte" value="{{ old('email_soporte', $cooperativa->email_soporte) }}"
                            class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-400 mb-1">Facebook URL</label>
                            <input type="url" name="facebook_url" value="{{ old('facebook_url', $cooperativa->facebook_url) }}"
                                class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:ring-2 focus:ring-brand-500">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-1">Instagram URL</label>
                            <input type="url" name="instagram_url" value="{{ old('instagram_url', $cooperativa->instagram_url) }}"
                                class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:ring-2 focus:ring-brand-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 pt-2">
                        <div>
                            <label class="block text-sm text-gray-400 mb-1">Color Primario</label>
                            <div class="flex items-center gap-2">
                                <input type="color" name="color_primario" value="{{ old('color_primario', $cooperativa->color_primario ?? '#3b82f6') }}"
                                    class="h-10 w-10 rounded bg-gray-800 border border-gray-700 cursor-pointer p-0.5">
                                <span class="text-xs text-gray-500">Marca/Botones</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-1">Color Secundario</label>
                            <div class="flex items-center gap-2">
                                <input type="color" name="color_secundario" value="{{ old('color_secundario', $cooperativa->color_secundario ?? '#1e40af') }}"
                                    class="h-10 w-10 rounded bg-gray-800 border border-gray-700 cursor-pointer p-0.5">
                                <span class="text-xs text-gray-500">Acentos</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Datos Bancarios --}}
            <div class="space-y-6 pt-6 border-t border-gray-800">
                <h2 class="text-xl font-bold text-white border-b border-gray-800 pb-2">Datos Bancarios para Transferencias</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Banco</label>
                        <input type="text" name="banco" value="{{ old('banco', $cooperativa->banco) }}" placeholder="Ej. Banco Pichincha"
                            class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Número de Cuenta</label>
                        <input type="text" name="cuenta_bancaria" value="{{ old('cuenta_bancaria', $cooperativa->cuenta_bancaria) }}"
                            class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Titular de la Cuenta</label>
                        <input type="text" name="titular_cuenta" value="{{ old('titular_cuenta', $cooperativa->titular_cuenta) }}"
                            class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-2">Esta información se mostrará a los clientes al momento de pagar por transferencia.</p>
            </div>

            <div class="pt-6 flex justify-end gap-3">
                <a href="{{ route('admin.dashboard') }}" class="px-6 py-3 bg-gray-800 hover:bg-gray-700 text-white font-medium rounded-xl transition">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-3 bg-brand-600 hover:bg-brand-500 text-white font-bold rounded-xl shadow-lg shadow-brand-500/20 transition">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</x-layouts.admin>
