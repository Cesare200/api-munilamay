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
        Schema::create('convocatorias__c_a_s', function (Blueprint $table) {
            $table->id(); // ID numérico relacional estándar por rendimiento
            $table->string('codigo_cas')->unique(); // Almacenará el formato EJ: 001-2026
            $table->string('proceso'); // Almacenará el formato EJ: CAS N°001-2026-MDL
            $table->integer('anio');
            $table->date('fecha');
            $table->string('tipo'); // EJ: Convocatoria, Resultados, etc.
            $table->string('titulo');
            $table->string('pdf'); 
            $table->string('status')->default('Nuevo'); // Estado base administrable
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('convocatorias__c_a_s');
    }
};
