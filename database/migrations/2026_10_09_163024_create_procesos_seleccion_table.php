<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
    {
        if (! Schema::hasTable('procesos_seleccion')) {
            Schema::create('procesos_seleccion', function (Blueprint $table) {
                $table->id();
                $table->string('filter', 20)->index();
                $table->year('year');
                $table->string('number', 10);
                $table->date('date');
                $table->string('date_label')->nullable();
                $table->string('title');
                $table->text('description')->nullable();
                $table->string('file');
                $table->string('file_type', 10)->default('pdf');
                $table->string('status', 30)->default('publicado');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('procesos_seleccion');
    }
};