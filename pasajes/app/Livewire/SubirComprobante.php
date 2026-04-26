<?php

namespace App\Livewire;

use App\Models\Boleto;
use App\Models\Comprobante;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class SubirComprobante extends Component
{
    use WithFileUploads;

    public int    $boletoId;
    public        $imagen;
    public bool   $subido = false;
    public ?string $error = null;

    protected function rules(): array
    {
        return [
            'imagen' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ];
    }

    protected function messages(): array
    {
        return [
            'imagen.required' => 'Debe seleccionar una imagen del comprobante.',
            'imagen.image'    => 'El archivo debe ser una imagen.',
            'imagen.mimes'    => 'Solo se aceptan imágenes JPG, JPEG, PNG o WEBP.',
            'imagen.max'      => 'La imagen no puede superar los 5 MB.',
        ];
    }

    public function guardar(): void
    {
        $this->validate();

        $boleto = Boleto::where('id', $this->boletoId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($boleto->comprobante) {
            $this->error = 'Ya existe un comprobante para este boleto.';
            return;
        }

        $ruta = $this->imagen->store('comprobantes', 'public');

        Comprobante::create([
            'boleto_id'  => $boleto->id,
            'imagen_url' => $ruta,
            'estado'     => 'Pendiente',
        ]);

        $this->subido = true;
        $this->imagen = null;
        session()->flash('success', 'Comprobante subido. Está en espera de validación por el oficinista.');
    }

    public function render()
    {
        return view('livewire.subir-comprobante');
    }
}
