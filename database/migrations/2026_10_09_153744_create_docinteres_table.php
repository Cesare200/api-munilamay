<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('docinteres')) {
            Schema::create('docinteres', function (Blueprint $table) {
                $table->id();
                $table->string('titulo', 191);
                $table->text('descripcion');
                $table->date('fecha');
                $table->string('pdf', 191);
                $table->string('status', 191)->default('borrador');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('docinteres');
    }
};