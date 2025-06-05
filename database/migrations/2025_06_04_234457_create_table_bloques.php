<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bloques', function (Blueprint $table) {
            $table->string('IDBloque')->primary();
            $table->string('nombre_bloque');
            $table->string('IDproyecto');
            $table->foreign('IDproyecto')->references('IDproyecto')->on('proyectos')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bloques');
    }
};
