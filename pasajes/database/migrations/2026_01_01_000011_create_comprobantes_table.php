<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comprobantes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('boleto_id')->constrained('boletos')->cascadeOnDelete();
            $table->string('imagen_url');
            $table->foreignId('validado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('estado', ['Pendiente', 'Aprobado', 'Rechazado'])->default('Pendiente');
            $table->text('observaciones')->nullable();
            $table->timestamp('validado_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comprobantes');
    }
};
