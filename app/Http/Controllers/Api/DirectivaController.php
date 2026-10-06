<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Directiva;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DirectivaController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Directiva::query()->where('status', 'publicado');

        // Filtrar por texto coincidente en título o descripción (?buscar=Conducta)
        if ($request->has('buscar') && !empty($request->buscar)) {
            $query->where(function($q) use ($request) {
                $q->where('titulo', 'like', '%' . $request->buscar . '%')
                  ->orWhere('descripcion', 'like', '%' . $request->buscar . '%');
            });
        }

        // Filtrar opcional por año de la fecha (?anio=2023)
        if ($request->has('anio') && !empty($request->anio)) {
            $query->whereYear('fecha', $request->anio);
        }

        $directivas = $query->orderBy('fecha', 'desc')->get();

        $resultado = $directivas->map(function ($directiva) {
            return [
                'id' => $directiva->id,
                'titulo' => $directiva->titulo,
                'descripcion' => $directiva->descripcion,
                'fecha' => $directiva->fecha,
                'pdf_url' => asset('storage/' . $directiva->pdf),
                'created_at' => $directiva->created_at,
            ];
        });

        return response()->json($resultado, 200);
    }
}
