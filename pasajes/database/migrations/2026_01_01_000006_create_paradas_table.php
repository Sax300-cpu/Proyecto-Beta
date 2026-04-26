<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paradas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('ciudad');
            $table->string('provincia')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paradas');
    }
};
