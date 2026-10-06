<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\acuerdo_concejo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AcuerdoConcejoController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = acuerdo_concejo::query()->where('status', 'publicado');

        if ($request->has('anio') && !empty($request->anio)) {
            $query->where('anio', $request->anio);
        }

        if ($request->has('buscar') && !empty($request->buscar)) {
            $query->where('numero', $request->buscar);
        }

        $acuerdos = $query->orderBy('fecha', 'desc')->get();

        $resultado = $acuerdos->map(function ($acuerdo) {
            return [
                'id' => $acuerdo->id,
                'numero' => $acuerdo->numero,
                'anio' => $acuerdo->anio,
                'fecha' => $acuerdo->fecha,
                'pdf_url' => asset('storage/' . $acuerdo->pdf),
                'created_at' => $acuerdo->created_at,
            ];
        });

        return response()->json($resultado, 200);
    }
}
