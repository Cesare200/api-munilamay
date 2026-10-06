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
    Schema::create('ordenanzas', function (Blueprint $table) {
        $table->id();
        $table->integer('numero');
        $table->integer('anio');
        $table->date('fecha');
        $table->string('pdf'); // Almacenará la ruta del PDF real cargado en el servidor
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordenanzas');
    }
};
