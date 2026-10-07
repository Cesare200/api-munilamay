<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instrumentos_gestion', function (Blueprint $table) {
            $table->id();
            $table->string('tipo', 50); // ROF, PAP, CAP, MCC, POI, PEI, etc.
            $table->string('nombre');
            $table->integer('anio');
            $table->text('descripcion')->nullable();
            $table->date('fecha');
            $table->string('aprobado_por')->nullable();
            $table->string('pdf')->nullable();
            $table->string('status', 50)->default('Publicado');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instrumentos_gestion');
    }
};