<div>
    <select wire:model="selectedHojaRutaId" class="bg-gray-800 border border-gray-700 text-white text-xs rounded-xl px-4 py-2.5 focus:ring-brand-500 focus:border-brand-500">
        <option value="">+ Vender en Ruta (Seleccionar Viaje)</option>
        @foreach($hojasRuta as $hoja)
            <option value="{{ $hoja->id }}">
                {{ $hoja->frecuencia->ruta->origen }} - {{ $hoja->frecuencia->ruta->destino }} (#{{ $hoja->id }})
            </option>
        @endforeach
    </select>
</div>