<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('interaccion_mercado')) {
            Schema::create('interaccion_mercado', function (Blueprint $table) {
                $table->id();
                $table->string('numero', 50)->nullable(); // Ej: 004-2025, 001-2025
                $table->year('anio');
                $table->text('titulo');
                $table->text('descripcion')->nullable();
                $table->date('fecha');
                $table->string('pdf');
                $table->string('status', 30)->default('publicado');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('interaccion_mercado');
    }
};