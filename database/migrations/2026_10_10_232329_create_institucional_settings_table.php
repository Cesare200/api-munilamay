<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('institucional_settings')) {
            Schema::create('institucional_settings', function (Blueprint $table) {
                $table->id();
                // Contacto Principal
                $table->string('telefono_principal', 50)->nullable();
                $table->string('email_principal', 150)->nullable();
                $table->string('direccion_corta', 150)->nullable();
                $table->text('direccion_completa')->nullable();
                $table->text('mapa_url')->nullable();

                // Redes Sociales
                $table->text('facebook_url')->nullable();
                $table->string('whatsapp_numero', 50)->nullable();
                $table->text('tiktok_url')->nullable();
                $table->text('youtube_url')->nullable();

                // Hero & Video
                $table->string('logo')->nullable();
                $table->string('video_hero')->nullable();
                $table->text('video_hero_url')->nullable(); // Opcional si prefieres URL externa
                $table->string('hero_titulo')->nullable();
                $table->json('hero_textos_rotativos')->nullable(); // Lista de frases rotativas
                $table->text('hero_boton_url')->nullable();

                // Números de Emergencia
                $table->string('emergencia_pnp', 50)->nullable();
                $table->string('emergencia_serenazgo', 50)->nullable();
                $table->string('emergencia_defensa_civil', 50)->nullable();

                // Horario de Atención
                $table->string('horario_dias')->nullable(); // Ej: Lunes a Viernes
                $table->string('horario_manana')->nullable(); // Ej: 8:00 a.m. - 1:00 p.m.
                $table->string('horario_tarde')->nullable(); // Ej: 2:00 p.m. - 5:00 p.m.
                $table->string('horario_feriados')->nullable(); // Ej: Feriados: Cerrado

                // Accesos de Interés (Repetidor de logos + enlaces)
                $table->json('accesos_interes')->nullable();

                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('institucional_settings');
    }
};