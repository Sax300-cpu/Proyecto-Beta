<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bus_id')->constrained('buses')->cascadeOnDelete();
            $table->string('numero', 5); // A1, A2, B1...
            $table->enum('tipo', ['Ventana', 'Pasillo', 'Fondo']);
            $table->integer('piso')->default(1);
            $table->boolean('habilitado')->default(true);
            $table->unique(['bus_id', 'numero']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asientos');
    }
};
