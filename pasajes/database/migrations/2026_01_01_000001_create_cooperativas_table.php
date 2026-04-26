<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cooperativas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('ruc', 13)->unique();
            $table->string('logo_url')->nullable();
            $table->string('direccion')->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('color_primario', 7)->default('#1e40af');
            $table->string('color_secundario', 7)->default('#3b82f6');
            $table->string('facebook_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('whatsapp', 20)->nullable();
            $table->string('email_soporte')->nullable();
            $table->string('cuenta_bancaria')->nullable();
            $table->string('banco')->nullable();
            $table->string('titular_cuenta')->nullable();
            $table->boolean('activa')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cooperativas');
    }
};
