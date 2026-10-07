<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('decretos', function (Blueprint $table) {
            $table->id();
            $table->string('numero', 100);
            $table->year('anio');
            $table->date('fecha');
            $table->string('pdf')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('decretos');
    }
};