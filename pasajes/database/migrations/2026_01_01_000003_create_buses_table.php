<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cooperativa_id')->constrained('cooperativas')->cascadeOnDelete();
            $table->foreignId('categoria_bus_id')->constrained('categorias_bus')->restrictOnDelete();
            $table->string('numero_disco')->unique();
            $table->string('placa')->unique();
            $table->string('chasis')->nullable();
            $table->string('carroceria')->nullable();
            $table->string('marca_chasis')->nullable();
            $table->string('foto_url')->nullable();
            $table->integer('capacidad_asientos');
            $table->boolean('habilitado')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buses');
    }
};
