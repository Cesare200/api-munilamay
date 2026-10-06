<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ResolucionAlcaldia;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ResolucionAlcaldiaController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        // La API únicamente expone los registros publicados
        $query = ResolucionAlcaldia::query()->where('status', 'publicado');

        // Filtro opcional por Año (?anio=2026)
        if ($request->has('anio') && !empty($request->anio)) {
            $query->where('anio', $request->anio);
        }

        // Buscador opcional por Número (?buscar=45)
        if ($request->has('buscar') && !empty($request->buscar)) {
            $query->where('numero', $request->buscar);
        }

        $resoluciones = $query->orderBy('fecha', 'desc')->get();

        $resultado = $resoluciones->map(function ($resolucion) {
            return [
                'id' => $resolucion->id,
                'numero' => $resolucion->numero,
                'anio' => $resolucion->anio,
                'fecha' => $resolucion->fecha,
                'pdf_url' => asset('storage/' . $resolucion->pdf), // Enlace directo al PDF real
                'created_at' => $resolucion->created_at,
            ];
        });

        return response()->json($resultado, 200);
    }
}
