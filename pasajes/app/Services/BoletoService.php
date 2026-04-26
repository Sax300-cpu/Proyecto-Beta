<?php

namespace App\Services;

use App\Models\Boleto;
use App\Models\HojaRuta;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BoletoService
{
    /**
     * Crea un boleto de forma transaccional, evitando condiciones de carrera
     * (double-booking). Usa SELECT FOR UPDATE para bloquear el asiento.
     *
     * @throws \Exception Si el asiento ya está ocupado.
     */
    public function vender(array $datos, User $vendedor): Boleto
    {
        return DB::transaction(function () use ($datos, $vendedor) {

            // Lock exclusivo sobre la hoja de ruta para evitar race conditions
            $hojaRuta = HojaRuta::lockForUpdate()->findOrFail($datos['hoja_ruta_id']);

            // Verificar que el asiento no esté ocupado dentro del lock
            $asientoOcupado = Boleto::where('hoja_ruta_id', $hojaRuta->id)
                ->where('asiento_id', $datos['asiento_id'])
                ->whereIn('estado', ['Pendiente', 'Validado', 'Abordado'])
                ->lockForUpdate()
                ->exists();

            if ($asientoOcupado) {
                throw new \Exception('El asiento seleccionado ya fue reservado por otro usuario. Por favor seleccione otro.');
            }

            // Verificar que el bus no haya partido (para oficinistas)
            if ($vendedor->hasRole('Oficinista') && $hojaRuta->yaPartio()) {
                throw new \Exception('El bus ya partió. No se pueden vender boletos para esta frecuencia.');
            }

            $precio = $this->calcularPrecio($datos['tipo_pasajero'], $hojaRuta);

            $boleto = Boleto::create([
                'hoja_ruta_id'        => $hojaRuta->id,
                'asiento_id'          => $datos['asiento_id'],
                'user_id'             => $datos['user_id'] ?? null,
                'vendido_por'         => $vendedor->id,
                'nombre_pasajero'     => $datos['nombre_pasajero'],
                'cedula_pasajero'     => $datos['cedula_pasajero'],
                'tipo_pasajero'       => $datos['tipo_pasajero'],
                'origen_abordaje'     => $datos['origen_abordaje'],
                'destino_desembarque' => $datos['destino_desembarque'],
                'precio'              => $precio,
                'qr_code'             => Str::uuid()->toString(),
                'estado'              => $vendedor->hasAnyRole(['Admin', 'Oficinista', 'Chofer'])
                                            ? 'Validado'
                                            : 'Pendiente',
                'vendido_en_ruta'     => $hojaRuta->yaPartio(),
            ]);

            return $boleto;
        });
    }

    private function calcularPrecio(string $tipoPasajero, HojaRuta $hojaRuta): float
    {
        $precioBase = $hojaRuta->frecuencia->ruta->cooperativa->id
            ? (float) $hojaRuta->bus->categoria->precio_base
            : 3.50;

        // Descuentos por tipo de pasajero (verificación física por oficinista/chofer)
        return match ($tipoPasajero) {
            'Niño'         => $precioBase * 0.50,  // 50% descuento
            'Tercera Edad' => $precioBase * 0.50,  // 50% descuento (LGDIS Ecuador)
            'Discapacitado'=> $precioBase * 0.50,  // 50% descuento
            default        => $precioBase,
        };
    }
}
