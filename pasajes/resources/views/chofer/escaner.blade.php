<x-layouts.chofer>
    <x-slot:title>Escáner de Boletos</x-slot:title>

    <div class="max-w-2xl mx-auto px-4 py-8">
        <div class="flex justify-between items-end mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-white">Escáner QR</h1>
                <p class="text-gray-400 mt-1">Valida boletos o vende en ruta</p>
            </div>
            <livewire:chofer.vender-ruta-selector />
        </div>
        
        <livewire:escaner-qr />
    </div>
</x-layouts.chofer>