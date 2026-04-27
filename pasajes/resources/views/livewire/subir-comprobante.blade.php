<div class="max-w-md mx-auto">
    @if($subido)
    <div class="bg-green-900/50 border border-green-700 text-green-300 px-5 py-4 rounded-xl">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <p class="font-bold text-green-200">Comprobante enviado</p>
        </div>
        <p class="text-sm mt-1">Tu comprobante está en revisión. Recibirás un correo cuando sea validado.</p>
    </div>
    @else
    <div class="bg-gray-900 border border-gray-800 rounded-2xl p-5">
        <h3 class="font-semibold text-white mb-1">Subir Comprobante de Transferencia</h3>
        <p class="text-sm text-gray-400 mb-4">Fotografía o captura de pantalla del comprobante bancario</p>

        @error('imagen') <p class="text-red-400 text-sm mb-3">{{ $message }}</p> @enderror

        <div x-data="{ preview: null }">
            <label class="block w-full border-2 border-dashed border-gray-700 hover:border-brand-600 rounded-xl p-6 text-center cursor-pointer transition"
                x-on:change="
                    const file = $event.target.files[0];
                    if(file) { const reader = new FileReader(); reader.onload = e => preview = e.target.result; reader.readAsDataURL(file); }">
                <input type="file" wire:model="imagen" accept="image/*" class="sr-only">

                <template x-if="!preview">
                    <div>
                        <svg class="mx-auto w-10 h-10 text-gray-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-gray-400 text-sm">Toca aquí para seleccionar imagen</p>
                        <p class="text-gray-600 text-xs mt-1">JPG, PNG, WEBP — máx 5 MB</p>
                    </div>
                </template>

                <template x-if="preview">
                    <img :src="preview" class="mx-auto max-h-40 rounded-lg object-contain">
                </template>
            </label>

            <div wire:loading wire:target="imagen" class="text-center text-brand-400 text-sm mt-2">
                Cargando imagen...
            </div>
        </div>

        @if($error)
        <p class="text-red-400 text-sm mt-3">{{ $error }}</p>
        @endif

        <button wire:click="guardar" wire:loading.attr="disabled"
            class="mt-4 w-full py-3 bg-brand-600 hover:bg-brand-500 text-white font-bold rounded-xl transition">
            <span wire:loading.remove>Enviar Comprobante</span>
            <span wire:loading>Subiendo...</span>
        </button>
    </div>
    @endif
</div>

