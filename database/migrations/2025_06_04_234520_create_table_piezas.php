<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('piezas', function (Blueprint $table) {
            $table->string('IdPieza')->primary();
            $table->string('pieza');
            $table->decimal('peso_teorico', 10, 2)->nullable();
            $table->decimal('peso_real', 10, 2)->nullable();
            $table->string('estado');
            $table->string('IDBloque');
            $table->date('fecha_registro')->nullable();
            $table->string('registrado_por')->nullable();
            $table->foreign('IDBloque')->references('IDBloque')->on('bloques')->onDelete('cascade');
            $table->foreign('registrado_por')->references('usuario')->on('usuarios');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('piezas');
    }
};
