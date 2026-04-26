<?php

namespace App\Livewire;

use App\Models\Boleto;
use App\Models\HojaRuta;
use App\Services\BoletoService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EscanerQr extends Component
{
    public string  $qrInput        = '';
    public ?Boleto $boletoEncontrado = null;
    public ?string $mensaje         = null;
    public ?string $tipoMensaje     = null; // 'success' | 'error' | 'warning'

    // Para venta en ruta
    public bool    $modalVentaAbierto     = false;
    public ?int    $hojaRutaActiva        = null;
    public string  $nombre_pasajero       = '';
    public string  $cedula_pasajero       = '';
    public string  $tipo_pasajero         = 'Normal';
    public string  $fecha_nacimiento_pasajero = '';
    public ?int    $asiento_id            = null;
    public string  $origen_abordaje       = '';
    public string  $destino_desembarque   = '';
    public ?string $errorVenta            = null;
    public ?string $ultimoQrEscaneado     = null;

    public function mount(): void
    {
        // Hoja de ruta activa del chofer logueado
        $this->hojaRutaActiva = HojaRuta::where('chofer_id', Auth::id())
            ->where('fecha', today())
            ->whereIn('estado', ['Pendiente', 'En Ruta'])
            ->value('id');
    }

    public function escanear(): void
    {
        if (empty(trim($this->qrInput))) {
            $this->mensaje     = 'Ingrese un código QR válido.';
            $this->tipoMensaje = 'error';
            return;
        }

        $boleto = Boleto::with([
            'hojaRuta.frecuencia.ruta',
            'asiento',
            'user',
        ])->where('qr_code', trim($this->qrInput))->first();

        if (! $boleto) {
            $this->boletoEncontrado = null;
            $this->mensaje     = '❌ Código QR no encontrado en el sistema.';
            $this->tipoMensaje = 'error';
            return;
        }

        $this->boletoEncontrado = $boleto;

        if ($boleto->estado === 'Abordado') {
            $this->mensaje     = '⚠️ Este boleto ya fue utilizado anteriormente.';
            $this->tipoMensaje = 'warning';
            return;
        }

        if ($boleto->estado !== 'Validado') {
            $this->mensaje     = '❌ Este boleto no está validado (Estado: ' . $boleto->estado . ').';
            $this->tipoMensaje = 'error';
            return;
        }

        $this->mensaje     = '✅ Boleto válido. Pasajero autorizado para abordar.';
        $this->tipoMensaje = 'success';
    }

    public function scanFromCamera(string $qrText): void
    {
        $qrText = trim($qrText);

        if ($qrText === '' || $qrText === $this->ultimoQrEscaneado) {
            return;
        }

        $this->ultimoQrEscaneado = $qrText;
        $this->qrInput = $qrText;
        $this->escanear();
    }

    public function marcarAbordado(): void
    {
        abort_unless(auth()->user()->hasRole('Chofer'), 403);

        if (! $this->boletoEncontrado) return;

        $this->boletoEncontrado->update(['estado' => 'Abordado']);
        $this->boletoEncontrado->refresh();
        $this->qrInput = '';
        session()->flash('success', 'Pasajero registrado como abordado.');
    }

    public function marcarNoShow(): void
    {
        abort_unless(auth()->user()->hasRole('Chofer'), 403);

        if (! $this->boletoEncontrado) return;

        $this->boletoEncontrado->update(['estado' => 'No Show']);
        $this->boletoEncontrado = null;
        $this->qrInput = '';
        session()->flash('info', 'Pasajero registrado como No Show.');
    }

    public function abrirModalVenta(): void
    {
        $this->modalVentaAbierto = true;
        $this->errorVenta        = null;
    }

    public function cerrarModalVenta(): void
    {
        $this->modalVentaAbierto = false;
    }

    public function venderEnRuta(): void
    {
        abort_unless(auth()->user()->hasRole('Chofer'), 403);

        $this->validate([
            'nombre_pasajero'           => 'required|string|max:255',
            'cedula_pasajero'           => 'required|string|size:10',
            'tipo_pasajero'             => 'required|in:Normal,Niño,Tercera Edad,Discapacitado',
            'fecha_nacimiento_pasajero' => 'required|date|before:' . now()->subYears(13)->format('Y-m-d'),
            'asiento_id'                => 'required|exists:asientos,id',
            'origen_abordaje'           => 'required|string|max:100',
            'destino_desembarque'       => 'required|string|max:100',
        ]);

        try {
            $service = app(BoletoService::class);
            $service->vender([
                'hoja_ruta_id'              => $this->hojaRutaActiva,
                'asiento_id'                => $this->asiento_id,
                'nombre_pasajero'           => $this->nombre_pasajero,
                'cedula_pasajero'           => $this->cedula_pasajero,
                'tipo_pasajero'             => $this->tipo_pasajero,
                'fecha_nacimiento_pasajero' => $this->fecha_nacimiento_pasajero,
                'origen_abordaje'           => $this->origen_abordaje,
                'destino_desembarque'       => $this->destino_desembarque,
            ], Auth::user());

            $this->modalVentaAbierto = false;
            $this->reset('nombre_pasajero', 'cedula_pasajero', 'asiento_id', 'origen_abordaje', 'destino_desembarque');
            session()->flash('success', 'Boleto vendido exitosamente en ruta.');
        } catch (\Exception $e) {
            $this->errorVenta = $e->getMessage();
        }
    }

    public function render()
    {
        $asientosDisponibles = collect();

        if ($this->hojaRutaActiva) {
            $hojaRuta = HojaRuta::find($this->hojaRutaActiva);
            if ($hojaRuta) {
                $asientosDisponibles = $hojaRuta->asientosDisponibles();
            }
        }

        return view('livewire.escaner-qr', [
            'asientosDisponibles' => $asientosDisponibles,
        ])->layout('layouts.chofer');
    }
}
