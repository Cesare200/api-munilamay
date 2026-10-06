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
    Schema::create('directivas', function (Blueprint $table) {
        $table->id();
        $table->string('titulo'); // Ejemplo: DIRECTIVA N° 006-2023-MDL
        $table->text('descripcion'); // El texto detallado de la directiva
        $table->date('fecha');
        $table->string('pdf'); // Ruta limpia del archivo físico
        $table->string('status')->default('borrador'); // Borrador o Publicado
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('directivas');
    }
};
