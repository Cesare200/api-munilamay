<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstitucionalSetting extends Model
{
    use HasFactory;

    protected $table = 'institucional_settings';

    protected $fillable = [
        'telefono_principal',
        'email_principal',
        'direccion_corta',
        'direccion_completa',
        'mapa_url',
        'facebook_url',
        'whatsapp_numero',
        'tiktok_url',
        'youtube_url',
        'logo',
        'video_hero',
        'video_hero_url',
        'hero_titulo',
        'hero_textos_rotativos',
        'hero_boton_url',
        'emergencia_pnp',
        'emergencia_serenazgo',
        'emergencia_defensa_civil',
        'horario_dias',
        'horario_manana',
        'horario_tarde',
        'horario_feriados',
        'accesos_interes',
    ];

    protected $casts = [
        'hero_textos_rotativos' => 'array',
        'accesos_interes' => 'array',
    ];

    /**
     * Singleton: siempre recupera o crea la única fila de configuración
     */
    public static function instance(): self
    {
        return static::firstOrCreate([], [
            'telefono_principal' => '957 756 538',
            'email_principal' => 'mesadepartes@munilamay.gob.pe',
            'direccion_corta' => 'Plaza De Armas Lamay',
            'direccion_completa' => 'Municipalidad Distrital de Lamay, Plaza de Armas S/N, Lamay - Calca - Cusco',
            'mapa_url' => 'https://maps.app.goo.gl/jEXoZTB74FY2fewa6',
            'facebook_url' => 'https://www.facebook.com/profile.php?id=61562288742277',
            'whatsapp_numero' => '957756538',
            'tiktok_url' => 'https://www.tiktok.com/@municipalidadlamay?lang=es',
            'hero_titulo' => 'LAMAY',
            'hero_textos_rotativos' => [
                ['texto' => 'El encanto del valle'],
                ['texto' => 'Sr. Glicerio Delgado Loaiza'],
                ['texto' => 'Alcalde'],
                ['texto' => 'Gestión 2023 - 2026'],
                ['texto' => 'Trabajando por Lamay'],
            ],
            'emergencia_pnp' => '957 756 621',
            'emergencia_serenazgo' => '957 756 611',
            'emergencia_defensa_civil' => '957 756 610',
            'horario_dias' => 'Lunes a Viernes',
            'horario_manana' => '8:00 a.m. - 1:00 p.m.',
            'horario_tarde' => '2:00 p.m. - 5:00 p.m.',
            'horario_feriados' => 'Feriados: Cerrado',
        ]);
    }
}