<?php

namespace App\Http\Livewire\Chofer;

use Livewire\Component;
use App\Models\HojaRuta;

class VenderRutaSelector extends Component
{
    public $hojasRuta;
    public $selectedHojaRutaId;

    public function mount()
    {
        // Solo hojas de ruta del chofer actual que estén "En Ruta"
        $this->hojasRuta = HojaRuta::where('chofer_id', auth()->id())
            ->where('estado', 'En Ruta')
            ->get();
    }

    public function updatedSelectedHojaRutaId($value)
    {
        if ($value) {
            return redirect()->route('chofer.vender-en-ruta', ['hojaRuta' => $value]);
        }
    }

    public function render()
    {
        return view('livewire.chofer.vender-ruta-selector');
    }
}