<?php

namespace Database\Seeders;

use App\Models\Asiento;
use App\Models\Bus;
use App\Models\CategoriaBus;
use App\Models\Cooperativa;
use App\Models\Frecuencia;
use App\Models\HojaRuta;
use App\Models\Parada;
use App\Models\Ruta;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ─── Roles ────────────────────────────────────────────
        $roleAdmin      = Role::firstOrCreate(['name' => 'Admin']);
        $roleOficinista = Role::firstOrCreate(['name' => 'Oficinista']);
        $roleChofer     = Role::firstOrCreate(['name' => 'Chofer']);
        $roleUsuario    = Role::firstOrCreate(['name' => 'Usuario_Final']);

        // ─── Cooperativa ──────────────────────────────────────
        $cooperativa = Cooperativa::firstOrCreate(
            ['ruc' => '1890045123001'],
            [
                'nombre'          => 'Cooperativa de Transportes Baños',
                'direccion'       => 'Terminal Terrestre Baños de Agua Santa',
                'telefono'        => '032740123',
                'email'           => 'info@coopbanos.com',
                'color_primario'  => '#111827', // Tailwind Gray-900 (Negro elegante)
                'color_secundario'=> '#EAB308', // Tailwind Yellow-500 (Acento)
                'cuenta_bancaria' => '2200123456',
                'banco'           => 'Banco Pichincha',
                'titular_cuenta'  => 'Coop. Transportes Baños',
                'email_soporte'   => 'soporte@coopbanos.com',
                'whatsapp'        => '0987654321',
                'logo_url'        => '/images/logo.png',
            ]
        );

        // ─── Usuarios ─────────────────────────────────────────
        $admin = User::firstOrCreate(
            ['email' => 'admin@cooperativa.com'],
            [
                'name'             => 'Administrador Principal',
                'password'         => Hash::make('password'),
                'cooperativa_id'   => $cooperativa->id,
                'cedula'           => '1801234567',
                'fecha_nacimiento' => '1985-03-15',
                'telefono'         => '0987000001',
            ]
        );
        $admin->assignRole($roleAdmin);

        $oficinista = User::firstOrCreate(
            ['email' => 'oficinista@cooperativa.com'],
            [
                'name'             => 'María González',
                'password'         => Hash::make('password'),
                'cooperativa_id'   => $cooperativa->id,
                'cedula'           => '1801234568',
                'fecha_nacimiento' => '1992-07-20',
                'telefono'         => '0987000002',
            ]
        );
        $oficinista->assignRole($roleOficinista);

        $chofer = User::firstOrCreate(
            ['email' => 'chofer@cooperativa.com'],
            [
                'name'             => 'Carlos Pérez',
                'password'         => Hash::make('password'),
                'cooperativa_id'   => $cooperativa->id,
                'cedula'           => '1801234569',
                'fecha_nacimiento' => '1980-11-05',
                'telefono'         => '0987000003',
            ]
        );
        $chofer->assignRole($roleChofer);

        $usuario = User::firstOrCreate(
            ['email' => 'usuario@gmail.com'],
            [
                'name'             => 'Juan Villacís',
                'password'         => Hash::make('password'),
                'cedula'           => '1801234570',
                'fecha_nacimiento' => '2000-05-10',
                'telefono'         => '0987000004',
            ]
        );
        $usuario->assignRole($roleUsuario);

        // ─── Categorías de Bus ─────────────────────────────────
        $catNormal = CategoriaBus::firstOrCreate(
            ['nombre' => 'Normal'],
            ['descripcion' => 'Bus estándar interprovincial', 'precio_base' => 3.50]
        );
        $catVip = CategoriaBus::firstOrCreate(
            ['nombre' => 'VIP'],
            ['descripcion' => 'Bus con asientos reclinables y servicio a bordo', 'precio_base' => 6.00]
        );

        // ─── Buses ────────────────────────────────────────────
        $bus1 = Bus::firstOrCreate(
            ['placa' => 'PBG-1234'],
            [
                'cooperativa_id'    => $cooperativa->id,
                'categoria_bus_id'  => $catNormal->id,
                'numero_disco'      => 'CB-001',
                'chasis'            => 'Mercedes-Benz OF-1722',
                'carroceria'        => 'Carrocerías Pichincha',
                'marca_chasis'      => 'Mercedes-Benz',
                'capacidad_asientos'=> 40,
                'habilitado'        => true,
            ]
        );

        $bus2 = Bus::firstOrCreate(
            ['placa' => 'PBG-5678'],
            [
                'cooperativa_id'    => $cooperativa->id,
                'categoria_bus_id'  => $catVip->id,
                'numero_disco'      => 'CB-002',
                'chasis'            => 'Volvo B8R',
                'carroceria'        => 'Marcopolo',
                'marca_chasis'      => 'Volvo',
                'capacidad_asientos'=> 44,
                'habilitado'        => true,
            ]
        );

        // ─── Asientos Bus 1 (40 asientos) ─────────────────────
        if ($bus1->asientos()->count() === 0) {
            for ($i = 1; $i <= 40; $i++) {
                $fila   = (int) ceil($i / 4);
                $col    = $i % 4;
                $tipo   = in_array($col, [1, 0]) ? 'Ventana' : 'Pasillo';
                if ($i > 36) $tipo = 'Fondo';
                Asiento::create([
                    'bus_id'    => $bus1->id,
                    'numero'    => (string) $i,
                    'tipo'      => $tipo,
                    'piso'      => 1,
                    'habilitado'=> true,
                ]);
            }
        }

        // ─── Asientos Bus 2 (44 asientos, 2 pisos) ────────────
        if ($bus2->asientos()->count() === 0) {
            for ($i = 1; $i <= 44; $i++) {
                $piso   = $i <= 22 ? 1 : 2;
                $col    = $i % 4;
                $tipo   = in_array($col, [1, 0]) ? 'Ventana' : 'Pasillo';
                if ($i > 40) $tipo = 'Fondo';
                Asiento::create([
                    'bus_id'    => $bus2->id,
                    'numero'    => (string) $i,
                    'tipo'      => $tipo,
                    'piso'      => $piso,
                    'habilitado'=> true,
                ]);
            }
        }

        // ─── Paradas ──────────────────────────────────────────
        $ambato  = Parada::firstOrCreate(['nombre' => 'Terminal Ambato'],  ['ciudad' => 'Ambato',  'provincia' => 'Tungurahua']);
        $latacunga = Parada::firstOrCreate(['nombre' => 'Terminal Latacunga'], ['ciudad' => 'Latacunga','provincia' => 'Cotopaxi']);
        $quito   = Parada::firstOrCreate(['nombre' => 'Terminal Quitumbe']  ,['ciudad' => 'Quito',   'provincia' => 'Pichincha']);
        $riobamba= Parada::firstOrCreate(['nombre' => 'Terminal Riobamba'], ['ciudad' => 'Riobamba','provincia' => 'Chimborazo']);
        $cuenca  = Parada::firstOrCreate(['nombre' => 'Terminal Cuenca'],   ['ciudad' => 'Cuenca',  'provincia' => 'Azuay']);

        // ─── Rutas ────────────────────────────────────────────
        $rutaAQ = Ruta::firstOrCreate(
            ['cooperativa_id' => $cooperativa->id, 'origen' => 'Ambato', 'destino' => 'Quito'],
            ['activa' => true]
        );
        $rutaAC = Ruta::firstOrCreate(
            ['cooperativa_id' => $cooperativa->id, 'origen' => 'Ambato', 'destino' => 'Cuenca'],
            ['activa' => true]
        );

        // ─── Frecuencias ──────────────────────────────────────
        $frecAQ1 = Frecuencia::firstOrCreate(
            ['ruta_id' => $rutaAQ->id, 'hora_salida' => '06:00:00'],
            ['resolucion_ant' => 'ANT-2019-0456', 'es_directa' => false, 'activa' => true]
        );
        $frecAQ2 = Frecuencia::firstOrCreate(
            ['ruta_id' => $rutaAQ->id, 'hora_salida' => '14:00:00'],
            ['resolucion_ant' => 'ANT-2019-0457', 'es_directa' => true, 'activa' => true]
        );
        $frecAC = Frecuencia::firstOrCreate(
            ['ruta_id' => $rutaAC->id, 'hora_salida' => '08:00:00'],
            ['resolucion_ant' => 'ANT-2020-0123', 'es_directa' => false, 'activa' => true]
        );

        // ─── Paradas de la Frecuencia (Ambato→Quito con paradas) ─
        if ($frecAQ1->paradas()->count() === 0) {
            $frecAQ1->paradas()->attach([
                $latacunga->id => ['orden' => 1, 'tiempo_estimado_llegada' => '07:00:00', 'precio_desde_origen' => 1.50],
                $quito->id     => ['orden' => 2, 'tiempo_estimado_llegada' => '09:00:00', 'precio_desde_origen' => 3.50],
            ]);
        }
        if ($frecAC->paradas()->count() === 0) {
            $frecAC->paradas()->attach([
                $riobamba->id => ['orden' => 1, 'tiempo_estimado_llegada' => '09:30:00', 'precio_desde_origen' => 2.00],
                $cuenca->id   => ['orden' => 2, 'tiempo_estimado_llegada' => '14:00:00', 'precio_desde_origen' => 6.50],
            ]);
        }

        // ─── Hoja de Ruta para hoy ────────────────────────────
        HojaRuta::firstOrCreate(
            ['frecuencia_id' => $frecAQ1->id, 'bus_id' => $bus1->id, 'fecha' => today()],
            ['chofer_id' => $chofer->id, 'estado' => 'Pendiente']
        );
        HojaRuta::firstOrCreate(
            ['frecuencia_id' => $frecAQ2->id, 'bus_id' => $bus2->id, 'fecha' => today()],
            ['chofer_id' => $chofer->id, 'estado' => 'Pendiente']
        );
    }
}
