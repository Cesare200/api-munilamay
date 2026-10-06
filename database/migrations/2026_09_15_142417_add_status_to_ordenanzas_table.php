<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ordenanzas', function (Blueprint $table) {
            // Por defecto, toda ordenanza nueva se guardará como 'borrador'
            $table->string('status')->default('borrador')->after('pdf');
        });
    }

    public function down(): void
    {
        Schema::table('ordenanzas', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
