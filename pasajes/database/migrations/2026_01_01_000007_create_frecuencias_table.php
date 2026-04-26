<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('frecuencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ruta_id')->constrained('rutas')->cascadeOnDelete();
            $table->time('hora_salida');
            $table->string('resolucion_ant')->comment('Número de resolución ANT simulado');
            $table->boolean('es_directa')->default(true);
            $table->boolean('activa')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('frecuencias');
    }
};
