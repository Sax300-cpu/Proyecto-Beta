<x-layouts.app>
    <x-slot:title>Boleto #{{ $boleto->id }}</x-slot:title>

    <div class="max-w-xl mx-auto px-4 py-10">
        
        {{-- Contenedor del Boleto --}}
        <div class="relative bg-gray-900 border border-gray-800 rounded-3xl shadow-2xl shadow-brand-900/10 mb-8 mt-4 isolate overflow-hidden group">
            
            {{-- Fondo con patrón sutil --}}
            <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMSIgY3k9IjEiIHI9IjEiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsMC4wNSkiLz48L3N2Zz4=')] opacity-50 z-[-1]"></div>

            {{-- Header --}}
            <div class="bg-gradient-to-r from-brand-600 to-indigo-700 p-8 text-center relative">
                @if($boleto->hojaRuta->frecuencia->ruta->cooperativa->logo_url)
                    <img src="{{ asset('storage/' . $boleto->hojaRuta->frecuencia->ruta->cooperativa->logo_url) }}" alt="Logo" class="w-16 h-16 rounded-full mx-auto mb-3 border-2 border-white/20 bg-white object-contain shadow-lg shadow-black/20">
                @endif
                <h2 class="text-white font-black text-2xl tracking-tight">{{ $boleto->hojaRuta->frecuencia->ruta->cooperativa->nombre }}</h2>
                <p class="text-brand-200 text-sm font-medium mt-1 uppercase tracking-widest">Boleto Digital de Transporte</p>
                
                <div class="absolute top-4 right-4">
                    <span class="px-3 py-1 rounded-full text-xs font-bold shadow-md
                        {{ $boleto->estado === 'Validado'  ? 'bg-emerald-500 text-white' : '' }}
                        {{ $boleto->estado === 'Pendiente' ? 'bg-yellow-500 text-white' : '' }}
                        {{ $boleto->estado === 'Rechazado' ? 'bg-red-500 text-white' : '' }}
                        {{ $boleto->estado === 'Abordado'  ? 'bg-brand-500 text-white' : '' }}">
                        {{ strtoupper($boleto->estado) }}
                    </span>
                </div>
            </div>

            {{-- Divisor Dentado (Estilo Ticket) --}}
            <div class="relative h-8 bg-gray-900 flex items-center justify-between -mt-4 z-10 px-0">
                <div class="w-8 h-8 rounded-full bg-gray-950 border-r border-gray-800 -translate-x-4 absolute left-0 z-20"></div>
                <div class="flex-1 border-t-2 border-dashed border-gray-700 mx-5 relative z-10"></div>
                <div class="w-8 h-8 rounded-full bg-gray-950 border-l border-gray-800 translate-x-4 absolute right-0 z-20"></div>
            </div>

            {{-- Cuerpo del boleto --}}
            <div class="p-8 pt-4">
                
                <div class="flex items-center justify-between mb-8 pb-8 border-b border-gray-800">
                    <div class="text-center flex-1">
                        <p class="text-gray-500 text-xs font-bold uppercase tracking-widest mb-1">Origen</p>
                        <p class="font-black text-2xl text-white">{{ $boleto->origen_abordaje }}</p>
                    </div>
                    <div class="px-4 text-gray-700">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </div>
                    <div class="text-center flex-1">
                        <p class="text-gray-500 text-xs font-bold uppercase tracking-widest mb-1">Destino</p>
                        <p class="font-black text-2xl text-white">{{ $boleto->destino_desembarque }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-y-6 gap-x-4 text-sm mb-8">
                    <div>
                        <p class="text-gray-500 font-semibold mb-0.5">Fecha de Viaje</p>
                        <p class="font-bold text-white text-lg">{{ $boleto->hojaRuta->fecha->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 font-semibold mb-0.5">Hora de Salida</p>
                        <p class="font-bold text-white text-lg">{{ \Carbon\Carbon::parse($boleto->hojaRuta->frecuencia->hora_salida)->format('H:i') }}</p>
                    </div>
                    <div class="col-span-2 bg-gray-800/50 rounded-xl p-3 border border-gray-800 flex justify-between items-center">
                        <div>
                            <p class="text-gray-500 font-semibold mb-0.5">Asiento</p>
                            <p class="font-black text-brand-400 text-2xl">{{ $boleto->asiento->numero }} <span class="text-sm font-medium text-gray-400 ml-1">({{ $boleto->asiento->tipo }})</span></p>
                        </div>
                        <div class="text-right">
                            <p class="text-gray-500 font-semibold mb-0.5">Bus / Disco</p>
                            <p class="font-bold text-white">{{ $boleto->hojaRuta->bus->placa }} (#{{ $boleto->hojaRuta->bus->numero_disco }})</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-gray-500 font-semibold mb-0.5">Pasajero</p>
                        <p class="font-bold text-white">{{ $boleto->nombre_pasajero }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 font-semibold mb-0.5">Cédula</p>
                        <p class="font-bold text-white">{{ $boleto->cedula_pasajero }}</p>
                    </div>
                </div>

                {{-- QR Code Section --}}
                @if($boleto->estado === 'Validado' || $boleto->estado === 'Abordado')
                <div class="border-t border-gray-800 pt-8 text-center pb-2">
                    <p class="text-sm font-medium text-gray-400 mb-4">Presenta este código al chofer</p>
                    <div class="inline-block bg-white p-3 rounded-2xl shadow-lg border-4 border-gray-100">
                        {!! QrCode::size(160)->generate(route('chofer.validar-qr', $boleto->qr_code)) !!}
                    </div>
                    <p class="text-xs text-gray-600 mt-4 font-mono font-bold tracking-widest">{{ substr($boleto->qr_code, 0, 18) }}...</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Acciones y Comprobantes --}}
        @if($boleto->estado === 'Validado' || $boleto->estado === 'Abordado')
            @if($boleto->pdf_url)
            <a href="{{ Storage::url($boleto->pdf_url) }}" target="_blank"
               class="mt-2 flex items-center justify-center gap-2 w-full py-3.5 bg-gray-800 hover:bg-gray-700 text-white font-bold rounded-2xl transition shadow-lg border border-gray-700">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Descargar Boleto en PDF
            </a>
            @endif
        @endif

        {{-- Si está pendiente, mostrar subir comprobante --}}
        @if($boleto->estado === 'Pendiente' && !$boleto->comprobante)
        <div class="bg-gray-900 border border-yellow-700/50 rounded-2xl p-6 mt-6 shadow-xl">
            <div class="flex items-start gap-3 mb-4">
                <svg class="w-8 h-8 text-yellow-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <div>
                    <h3 class="font-bold text-yellow-500 text-lg">Boleto Pendiente de Pago</h3>
                    <p class="text-sm text-gray-400 mt-1">Puedes pagar en linea con PayPal Sandbox o realizar transferencia y subir comprobante.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('checkout.paypal.create', $boleto->id) }}" class="mb-5">
                @csrf
                <button type="submit"
                    class="w-full py-3.5 bg-brand-600 hover:bg-brand-500 text-black font-bold rounded-2xl transition shadow-lg border border-brand-500/70">
                    Pagar ahora con PayPal Sandbox
                </button>
            </form>
            
            <div class="bg-gray-800 rounded-xl p-4 mb-5 border border-gray-700 text-sm">
                <p class="text-gray-400 mb-1">Monto a depositar: <span class="text-white font-bold text-lg ml-1">${{ number_format($boleto->precio, 2) }}</span></p>
                <div class="h-px bg-gray-700 my-2"></div>
                <p class="text-gray-400">Banco: <span class="text-white font-semibold ml-1">{{ $boleto->hojaRuta->frecuencia->ruta->cooperativa->banco ?? 'No registrado' }}</span></p>
                <p class="text-gray-400">Cuenta: <span class="text-white font-semibold ml-1">{{ $boleto->hojaRuta->frecuencia->ruta->cooperativa->cuenta_bancaria ?? 'No registrado' }}</span></p>
                <p class="text-gray-400">Titular: <span class="text-white font-semibold ml-1">{{ $boleto->hojaRuta->frecuencia->ruta->cooperativa->titular_cuenta ?? 'No registrado' }}</span></p>
            </div>

            <p class="text-xs text-gray-500 mb-4">Tambien puedes completar el pago por transferencia y subir tu comprobante:</p>
            
            <livewire:subir-comprobante :boletoId="$boleto->id" />
        </div>
        @elseif($boleto->comprobante && $boleto->comprobante->estado === 'Pendiente')
        <div class="bg-brand-900/30 border border-brand-800 rounded-2xl p-6 mt-6 text-center">
            <div class="mb-2 flex justify-center">
                <svg class="w-10 h-10 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h3 class="font-bold text-brand-400">Comprobante en Revisión</h3>
            <p class="text-sm text-brand-200/70 mt-1">Hemos recibido tu comprobante. Un oficinista lo validará pronto y tu boleto se activará automáticamente.</p>
        </div>
        @elseif($boleto->comprobante && $boleto->comprobante->estado === 'Rechazado')
        <div class="bg-red-900/30 border border-red-800 rounded-2xl p-6 mt-6">
            <div class="flex items-start gap-3 mb-4">
                <svg class="w-8 h-8 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div>
                    <h3 class="font-bold text-red-500 text-lg">Comprobante Rechazado</h3>
                    <p class="text-sm text-red-200 mt-1">Motivo: {{ $boleto->comprobante->observaciones ?? 'El comprobante no es válido.' }}</p>
                </div>
            </div>
            <p class="text-sm text-gray-400 mb-4 font-medium">Por favor, vuelve a subir un comprobante válido.</p>
            <livewire:subir-comprobante :boletoId="$boleto->id" />
        </div>
        @endif

    </div>
</x-layouts.app>

