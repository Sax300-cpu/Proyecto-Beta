<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('boletos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hoja_ruta_id')->constrained('hojas_ruta')->restrictOnDelete();
            $table->foreignId('asiento_id')->constrained('asientos')->restrictOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('vendido_por')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nombre_pasajero');
            $table->string('cedula_pasajero', 13);
            $table->enum('tipo_pasajero', ['Normal', 'Niño', 'Tercera Edad', 'Discapacitado'])->default('Normal');
            $table->string('origen_abordaje');
            $table->string('destino_desembarque');
            $table->decimal('precio', 8, 2);
            $table->string('qr_code', 64)->unique();
            $table->enum('estado', ['Pendiente', 'Validado', 'Rechazado', 'Abordado', 'No Show'])->default('Pendiente');
            $table->string('pdf_url')->nullable();
            $table->boolean('vendido_en_ruta')->default(false);
            $table->timestamps();

            // Un asiento no puede venderse dos veces en la misma hoja de ruta
            $table->unique(['hoja_ruta_id', 'asiento_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('boletos');
    }
};
