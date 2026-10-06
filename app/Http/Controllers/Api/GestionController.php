<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gestion;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class GestionController extends Controller
{
    public function show(): JsonResponse
    {
        $gestion = Gestion::first();

        if (!$gestion) {
            return response()->json([
                'status' => 'error',
                'message' => 'No se encontró información de la gestión.'
            ], 404);
        }

        $alcalde = $gestion->alcalde;
        if (!empty($alcalde['foto'])) {
            $alcalde['foto_url'] = Storage::disk('public')->url($alcalde['foto']);
        }

        $concejo = collect($gestion->concejo)->map(function ($item) {
            if (!empty($item['foto'])) {
                $item['foto_url'] = Storage::disk('public')->url($item['foto']);
            }
            return $item;
        });

        $fotos = collect($gestion->fotos_gestion)->map(function ($foto) {
            return Storage::disk('public')->url($foto);
        });

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $gestion->id,
                'eslogan' => $gestion->eslogan,
                'alcalde' => $alcalde,
                'concejo' => $concejo,
                'mision' => $gestion->mision,
                'vision' => $gestion->vision,
                'historia' => $gestion->historia,
                'fotos_gestion' => $fotos,
                'updated_at' => $gestion->updated_at,
            ]
        ], 200);
    }
}