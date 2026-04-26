<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('comprobantes', function (Blueprint $table): void {
            $table->string('metodo_pago', 30)->nullable()->after('estado');
            $table->string('referencia_pago', 100)->nullable()->after('metodo_pago');
            $table->json('metadata')->nullable()->after('observaciones');
        });
    }

    public function down(): void
    {
        Schema::table('comprobantes', function (Blueprint $table): void {
            $table->dropColumn(['metodo_pago', 'referencia_pago', 'metadata']);
        });
    }
};