<?php

namespace App\Livewire;

use App\Jobs\EnviarBoletoAprobado;
use App\Jobs\GenerarPdfBoleto;
use App\Models\Comprobante;
use Livewire\Component;
use Livewire\WithPagination;

class ValidacionPagos extends Component
{
    use WithPagination;

    public string $filtroEstado  = 'Pendiente';
    public ?string $observaciones = null;
    public ?int $comprobanteActivo = null;

    public function aprobar(int $comprobanteId): void
    {
        $this->autorizar();

        $comprobante = Comprobante::with('boleto.user')->findOrFail($comprobanteId);

        $comprobante->update([
            'estado'       => 'Aprobado',
            'validado_por' => auth()->id(),
            'validado_at'  => now(),
            'observaciones'=> $this->observaciones,
        ]);

        $comprobante->boleto->update(['estado' => 'Validado']);

        // Disparar jobs en background
        GenerarPdfBoleto::dispatch($comprobante->boleto);
        EnviarBoletoAprobado::dispatch($comprobante->boleto);

        $this->comprobanteActivo = null;
        $this->observaciones     = null;
        session()->flash('success', 'Comprobante aprobado. El boleto fue activado y se notificó al cliente.');
        $this->dispatch('toast', message: '✅ Pago aprobado exitosamente. Boleto enviado al cliente.', type: 'success');
    }

    public function rechazar(int $comprobanteId): void
    {
        $this->autorizar();

        $comprobante = Comprobante::with('boleto')->findOrFail($comprobanteId);

        $comprobante->update([
            'estado'       => 'Rechazado',
            'validado_por' => auth()->id(),
            'validado_at'  => now(),
            'observaciones'=> $this->observaciones,
        ]);

        $comprobante->boleto->update(['estado' => 'Rechazado']);

        $this->comprobanteActivo = null;
        $this->observaciones     = null;
        session()->flash('error', 'Comprobante rechazado. El boleto fue invalidado.');
        $this->dispatch('toast', message: '❌ Comprobante rechazado. El boleto fue invalidado.', type: 'error');
    }

    public function abrirModal(int $comprobanteId): void
    {
        $this->comprobanteActivo = $comprobanteId;
        $this->observaciones     = null;
    }

    public function cerrarModal(): void
    {
        $this->comprobanteActivo = null;
    }

    private function autorizar(): void
    {
        abort_unless(
            auth()->user()->hasAnyRole(['Admin', 'Oficinista']),
            403,
            'No tienes permiso para validar comprobantes.'
        );
    }

    public function render()
    {
        $comprobantes = Comprobante::with([
            'boleto.hojaRuta.frecuencia.ruta',
            'boleto.asiento',
            'boleto.user',
            'validadoPor',
        ])
        ->where('estado', $this->filtroEstado)
        ->whereHas('boleto.hojaRuta.frecuencia.ruta.cooperativa', function ($q) {
            // Oficinistas solo ven comprobantes de su cooperativa
            if (auth()->user()->hasRole('Oficinista')) {
                $q->where('id', auth()->user()->cooperativa_id);
            }
        })
        ->latest()
        ->paginate(10);

        $comprobanteModal = $this->comprobanteActivo
            ? Comprobante::with('boleto.user')->find($this->comprobanteActivo)
            : null;

        return view('livewire.validacion-pagos', [
            'comprobantes'   => $comprobantes,
            'comprobanteModal' => $comprobanteModal,
        ]);
    }
}
