<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hojas_ruta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('frecuencia_id')->constrained('frecuencias')->restrictOnDelete();
            $table->foreignId('bus_id')->constrained('buses')->restrictOnDelete();
            $table->foreignId('chofer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('fecha');
            $table->enum('estado', ['Pendiente', 'En Ruta', 'Completada', 'Cancelada'])->default('Pendiente');
            $table->timestamp('hora_partida_real')->nullable();
            $table->timestamps();

            // Un bus no puede tener dos hojas de ruta el mismo día en la misma frecuencia
            $table->unique(['frecuencia_id', 'bus_id', 'fecha']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hojas_ruta');
    }
};
