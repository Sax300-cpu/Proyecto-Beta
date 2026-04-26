<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('frecuencia_paradas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('frecuencia_id')->constrained('frecuencias')->cascadeOnDelete();
            $table->foreignId('parada_id')->constrained('paradas')->cascadeOnDelete();
            $table->integer('orden');
            $table->time('tiempo_estimado_llegada')->nullable();
            $table->decimal('precio_desde_origen', 8, 2)->default(0);
            $table->timestamps();

            $table->unique(['frecuencia_id', 'parada_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('frecuencia_paradas');
    }
};
