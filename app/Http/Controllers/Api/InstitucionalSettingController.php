<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InstitucionalSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class InstitucionalSettingController extends Controller
{
    public function index(): JsonResponse
    {
        $settings = InstitucionalSetting::instance();
        $data = $settings->toArray();

        // Convertir rutas locales en URLs absolutas públicas
        $disk = Storage::disk('public');

        $data['logo_url'] = $settings->logo ? $disk->url($settings->logo) : null;
        $data['video_hero_url'] = $settings->video_hero
            ? $disk->url($settings->video_hero)
            : ($settings->video_hero_url ?: null);

        if (! empty($data['accesos_interes']) && is_array($data['accesos_interes'])) {
            foreach ($data['accesos_interes'] as &$acceso) {
                if (! empty($acceso['imagen'])) {
                    $acceso['imagen_url'] = $disk->url($acceso['imagen']);
                }
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ], 200);
    }
}