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
        Schema::table('convocatorias__c_a_s', function (Blueprint $table) {
            $table->dropUnique('convocatorias__c_a_s_codigo_cas_unique');
            $table->index('codigo_cas', 'convocatorias__c_a_s_codigo_cas_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('convocatorias__c_a_s', function (Blueprint $table) {
            $table->dropIndex('convocatorias__c_a_s_codigo_cas_index');
            $table->unique('codigo_cas', 'convocatorias__c_a_s_codigo_cas_unique');
        });
    }
};
