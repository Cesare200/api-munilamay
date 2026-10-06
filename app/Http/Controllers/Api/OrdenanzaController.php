<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ordenanza;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request; // <-- IMPORTANTE: Añadir esta línea

class OrdenanzaController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        // Iniciamos la consulta base a la tabla ordenanzas
        $query = Ordenanza::query()->where('status', 'publicado');

        // 1. Filtro por Año (si viene en la URL: ?anio=2017)
        if ($request->has('anio') && !empty($request->anio)) {
            $query->where('anio', $request->anio);
        }

        // 2. Buscador por Número (si viene en la URL: ?buscar=8)
        if ($request->has('buscar') && !empty($request->buscar)) {
            $query->where('numero', $request->buscar);
        }

        // Trae los resultados filtrados y ordenados por fecha más reciente
        $ordenanzas = $query->orderBy('fecha', 'desc')->get();

        // Mapeamos los datos para añadir la URL absoluta del PDF real
        $resultado = $ordenanzas->map(function ($ordenanza) {
            return [
                'id' => $ordenanza->id,
                'numero' => $ordenanza->numero,
                'anio' => $ordenanza->anio,
                'fecha' => $ordenanza->fecha,
                'pdf_url' => asset('storage/' . $ordenanza->pdf), // URL para descargar el PDF real
                'created_at' => $ordenanza->created_at,
            ];
        });

        // Retorna la respuesta en formato JSON
        return response()->json($resultado, 200);
    }
}
