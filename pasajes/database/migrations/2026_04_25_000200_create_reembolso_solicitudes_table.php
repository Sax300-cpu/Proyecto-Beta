<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reembolso_solicitudes', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('boleto_id')->constrained('boletos')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->text('motivo');
            $table->enum('estado', ['Pendiente', 'Atendida', 'Rechazada'])->default('Pendiente');
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->unique('boleto_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reembolso_solicitudes');
    }
};
