<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('procesos_seleccion', function (Blueprint $table) {
            $table->id();
            $table->string('filter', 20)->index(); // Ej: 2025-004
            $table->year('year');
            $table->string('number', 10);          // Ej: 004
            $table->date('date');
            $table->string('date_label')->nullable(); // Ej: Publicado: 23 de Diciembre - 2025
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file');                   // Archivo en storage
            $table->string('file_type', 10)->default('pdf');
            $table->string('status', 30)->default('publicado');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('procesos_seleccion');
    }
};