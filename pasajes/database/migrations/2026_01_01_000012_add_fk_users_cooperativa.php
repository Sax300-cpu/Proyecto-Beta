<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ahora que cooperativas ya existe, agregamos la FK
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('cooperativa_id')
                  ->references('id')
                  ->on('cooperativas')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['cooperativa_id']);
        });
    }
};
