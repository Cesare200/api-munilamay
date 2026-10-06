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
    Schema::create('acuerdo_concejos', function (Blueprint $table) {
        $table->id();
        $table->integer('numero');
        $table->integer('anio');
        $table->date('fecha');
        $table->string('pdf'); // Guarda la ruta limpia del archivo físico
        $table->string('status')->default('borrador'); // Borrador o Publicado
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acuerdo_concejos');
    }
};
