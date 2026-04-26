<?php

namespace App\Livewire;

use App\Models\Asiento;
use App\Models\Boleto;
use App\Models\HojaRuta;
use App\Services\BoletoService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SelectorAsientos extends Component
{
    public int    $hojaRutaId;
    public ?int   $asientoSeleccionado = null;
    public array  $asientosOcupados    = [];

    // Datos del pasajero
    public string $nombre_pasajero     = '';
    public string $cedula_pasajero     = '';
    public string $tipo_pasajero       = 'Normal';
    public string $fecha_nacimiento_pasajero = '';
    public string $origen_abordaje     = '';
    public string $destino_desembarque = '';

    public bool   $confirmando         = false;
    public ?string $error              = null;
    public ?int   $boletoGeneradoId   = null;

    protected function rules(): array
    {
        return [
            'asientoSeleccionado'         => 'required|integer',
            'nombre_pasajero'             => 'required|string|max:255',
            'cedula_pasajero'             => 'required|string|size:10',
            'tipo_pasajero'               => 'required|in:Normal,Niño,Tercera Edad,Discapacitado',
            'fecha_nacimiento_pasajero'   => 'required|date|before:' . now()->subYears(13)->format('Y-m-d'),
            'origen_abordaje'             => 'required|string|max:100',
            'destino_desembarque'         => 'required|string|max:100',
        ];
    }

    protected function messages(): array
    {
        return [
            'asientoSeleccionado.required'       => 'Debe seleccionar un asiento.',
            'cedula_pasajero.size'               => 'La cédula debe tener 10 dígitos.',
            'fecha_nacimiento_pasajero.before'   => 'El pasajero debe ser mayor de 13 años.',
        ];
    }

    public function mount(int $hojaRutaId): void
    {
        $this->hojaRutaId = $hojaRutaId;
        $this->actualizarOcupados();

        $hojaRuta = HojaRuta::with('frecuencia.ruta')->find($hojaRutaId);
        if ($hojaRuta) {
            $this->origen_abordaje     = $hojaRuta->frecuencia->ruta->origen;
            $this->destino_desembarque = $hojaRuta->frecuencia->ruta->destino;
        }
    }

    public function actualizarOcupados(): void
    {
        $this->asientosOcupados = Boleto::where('hoja_ruta_id', $this->hojaRutaId)
            ->whereIn('estado', ['Pendiente', 'Validado', 'Abordado'])
            ->pluck('asiento_id')
            ->toArray();
    }

    public function seleccionarAsiento(int $asientoId): void
    {
        // Refresca ocupados en tiempo real antes de seleccionar
        $this->actualizarOcupados();

        if (in_array($asientoId, $this->asientosOcupados)) {
            $this->error = 'Este asiento acaba de ser reservado. Por favor selecciona otro.';
            $this->asientoSeleccionado = null;
            return;
        }

        $this->error              = null;
        $this->asientoSeleccionado = $asientoId;
    }

    public function confirmar(): void
    {
        $this->validate();
        $this->confirmando = true;
    }

    public function comprar(): void
    {
        $this->validate();
        $this->error = null;

        try {
            $service = app(BoletoService::class);
            $boleto  = $service->vender([
                'hoja_ruta_id'                => $this->hojaRutaId,
                'asiento_id'                  => $this->asientoSeleccionado,
                'user_id'                     => Auth::id(),
                'nombre_pasajero'             => $this->nombre_pasajero,
                'cedula_pasajero'             => $this->cedula_pasajero,
                'tipo_pasajero'               => $this->tipo_pasajero,
                'fecha_nacimiento_pasajero'   => $this->fecha_nacimiento_pasajero,
                'origen_abordaje'             => $this->origen_abordaje,
                'destino_desembarque'         => $this->destino_desembarque,
            ], Auth::user());

            $this->boletoGeneradoId = $boleto->id;
            $this->confirmando      = false;
            $this->actualizarOcupados();

            $this->dispatch('boleto-generado', boletoId: $boleto->id);
            session()->flash('success', '¡Boleto generado exitosamente!');
        } catch (\Exception $e) {
            $this->error      = $e->getMessage();
            $this->confirmando = false;
            $this->actualizarOcupados(); // refresca para mostrar el cambio
        }
    }

    public function cancelarConfirmacion(): void
    {
        $this->confirmando = false;
    }

    public function render()
    {
        $hojaRuta = HojaRuta::with(['bus.asientos' => fn ($q) => $q->where('habilitado', true)->orderBy('numero')])->findOrFail($this->hojaRutaId);
        $asientos = $hojaRuta->bus->asientos;

        return view('livewire.selector-asientos', [
            'hojaRuta' => $hojaRuta,
            'asientos' => $asientos,
        ]);
    }
}
