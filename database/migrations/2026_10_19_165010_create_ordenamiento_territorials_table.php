<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ordenamiento_territorials', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->enum('categoria', [
                'tomo_1_diagnostico',
                'tomo_2_propuestas',
                'tomo_3_reglamento',
                'mapa_diagnostico',
                'mapa_propuesta'
            ]);
            $table->string('subcategoria')->nullable(); // Ej: Propuestas Específicas, Programa de Inversiones
            $table->text('descripcion')->nullable();
            $table->string('archivo_pdf');
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ordenamiento_territorials');
    }
};