<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categorias_bus', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // Normal, VIP, Ejecutivo
            $table->text('descripcion')->nullable();
            $table->decimal('precio_base', 8, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categorias_bus');
    }
};
