<x-layouts.app>
    <x-slot:title>Inicio</x-slot:title>

    {{-- Hero --}}
    <div class="relative bg-gradient-to-br from-brand-900 via-gray-950 to-gray-950 overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 left-1/4 w-96 h-96 bg-brand-400 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-1/4 w-64 h-64 bg-brand-600 rounded-full blur-3xl"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 py-16 text-center">
            <h1 class="text-5xl sm:text-6xl font-black text-white mb-4 leading-tight">
                Viaja por el<br><span class="text-brand-400">Ecuador</span>
            </h1>
            <p class="text-xl text-gray-400 max-w-xl mx-auto mb-8">
                Compra tu pasaje en línea y recibe tu boleto digital con QR al instante.
            </p>
        </div>
    </div>

    {{-- Buscador principal --}}
    <livewire:busqueda-frecuencias />
</x-layouts.app>
